# RealEstatePro — Phase 20: AWS Deployment

This is the final phase. It covers the production architecture, every AWS service the app touches, and the exact steps to take RealEstatePro from a local Laravel project to a live, publicly reachable, production-hardened site.

---

## 1. Reference Architecture

```
                                   ┌─────────────────────┐
                                   │   Route 53 (DNS)     │
                                   └──────────┬───────────┘
                                              │
                                   ┌──────────▼───────────┐
                                   │  ACM SSL Certificate  │
                                   │  (or Certbot on EC2)  │
                                   └──────────┬───────────┘
                                              │
                     ┌────────────────────────▼────────────────────────┐
                     │        EC2 instance (Ubuntu 24.04 LTS)           │
                     │  Nginx → PHP-FPM 8.3 → Laravel 12 (RealEstatePro)│
                     │  Supervisor: queue:work (Phase 18 mail)          │
                     │  Cron: schedule:run (every minute)               │
                     └───────┬───────────────────┬───────────────────┬─┘
                             │                    │                   │
                   ┌─────────▼──────────┐ ┌───────▼────────┐ ┌────────▼────────┐
                   │   RDS for MySQL 8   │ │   S3 Bucket     │ │  SES (or SMTP)  │
                   │   (Multi-AZ opt.)   │ │  realestatepro- │ │  transactional  │
                   │                     │ │     media       │ │     email       │
                   └─────────────────────┘ └─────────────────┘ └─────────────────┘
```

This is a single-EC2-instance architecture — the right starting point for this project. Everything here also works unchanged behind an Application Load Balancer + Auto Scaling Group later (session driver is already `database`, not file-based, so it's multi-instance-safe from day one).

---

## 2. Files delivered this phase

| File | Belongs at |
|---|---|
| `nginx.conf` | server (reference only — copy content into `/etc/nginx/sites-available/realestatepro`) |
| `supervisor-worker.conf` | server, `/etc/supervisor/conf.d/realestatepro-worker.conf` |
| `deploy.sh` | `deploy/deploy.sh` (in the repo, executed on the server) |
| `aws-s3-iam-policy.json` | reference — attach to the IAM user/role the app uses |
| `deploy.yml` | `.github/workflows/deploy.yml` |

---

## 3. Step-by-step AWS setup

### 3.1 RDS — MySQL Database
1. RDS Console → **Create database** → Engine: MySQL 8.0 → Template: "Production" (or "Dev/Test" to save cost initially).
2. Instance class: `db.t3.micro` is enough to start; DB name: `realestatepro`.
3. **VPC security group**: only allow inbound port `3306` from your EC2 instance's security group — never `0.0.0.0/0`.
4. Enable automated backups (7-day retention minimum) and, for production, Multi-AZ for failover.
5. Copy the RDS endpoint into `.env`:
   ```
   DB_HOST=realestatepro.xxxxxxxxxx.ap-south-1.rds.amazonaws.com
   DB_DATABASE=realestatepro
   DB_USERNAME=admin
   DB_PASSWORD=<strong-generated-password>
   ```

### 3.2 S3 — Property Media Storage
1. S3 Console → **Create bucket** → name `realestatepro-media` (must be globally unique — add a suffix if taken) → same region as EC2/RDS.
2. Block Public Access: **keep all four boxes checked** — the app serves images via signed/`Storage::url()` calls, not direct public bucket browsing (Laravel's `s3` disk visibility is set to `public` per-object via ACL, which only exposes the specific uploaded objects, not the whole bucket).
3. Create an IAM user (or, better, an **IAM role attached to the EC2 instance** — no long-lived keys to manage) with the policy in `aws-s3-iam-policy.json`.
4. Switch storage over by changing exactly one `.env` line (no code changes — this is why Phase 9's `config/filesystems.php` was written the way it was):
   ```
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=...          # omit entirely if using an instance role
   AWS_SECRET_ACCESS_KEY=...      # omit entirely if using an instance role
   AWS_DEFAULT_REGION=ap-south-1
   AWS_BUCKET=realestatepro-media
   ```
5. Existing local `storage/app/public/...` files (if any test data was created locally) can be synced up with:
   ```bash
   aws s3 sync storage/app/public s3://realestatepro-media
   ```

### 3.3 SES — Transactional Email (Phase 18's Mailables)
1. SES Console → **Verify a domain** (realestatepro.com) — add the provided TXT/CNAME/DKIM records to Route 53.
2. Request production access (SES starts in "sandbox mode," which only sends to verified addresses) via Support Center → "Request production access."
3. Create SMTP credentials (SES Console → SMTP Settings → Create SMTP Credentials) and set:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=email-smtp.ap-south-1.amazonaws.com
   MAIL_PORT=587
   MAIL_USERNAME=<SES-SMTP-username>
   MAIL_PASSWORD=<SES-SMTP-password>
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=no-reply@realestatepro.com
   ```

### 3.4 EC2 — Application Server
1. Launch an Ubuntu 24.04 LTS instance (`t3.small` is a reasonable starting size for PHP-FPM + Nginx).
2. Security group: allow `22` (SSH, restrict to your IP), `80`/`443` (public).
3. Attach the IAM role from step 3.2 (Instance → Actions → Security → Modify IAM role).
4. SSH in and install the stack:
   ```bash
   sudo apt update && sudo apt upgrade -y
   sudo apt install -y nginx mysql-client git unzip supervisor

   # PHP 8.3 (via the Ondřej Surý PPA)
   sudo add-apt-repository ppa:ondrej/php -y
   sudo apt update
   sudo apt install -y php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml \
       php8.3-curl php8.3-gd php8.3-zip php8.3-bcmath php8.3-intl

   # Composer
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer

   # (Optional) ffmpeg for Phase 17's video poster-frame extraction —
   # the app works fine without it; this just enables that one nice-to-have.
   sudo apt install -y ffmpeg
   ```
5. Clone the repo and configure:
   ```bash
   sudo mkdir -p /var/www/realestatepro
   sudo chown $USER:$USER /var/www/realestatepro
   git clone <your-repo-url> /var/www/realestatepro
   cd /var/www/realestatepro

   cp .env.example .env
   # edit .env with the RDS/S3/SES values from steps 3.1-3.3, plus:
   #   APP_ENV=production
   #   APP_DEBUG=false
   #   APP_URL=https://realestatepro.com

   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force --seed
   php artisan storage:link   # harmless no-op once FILESYSTEM_DISK=s3, but safe to run
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```
6. Nginx: copy `deploy/nginx.conf`'s content to `/etc/nginx/sites-available/realestatepro`, symlink into `sites-enabled`, then:
   ```bash
   sudo ln -s /etc/nginx/sites-available/realestatepro /etc/nginx/sites-enabled/
   sudo rm -f /etc/nginx/sites-enabled/default
   sudo nginx -t && sudo systemctl reload nginx
   ```
7. SSL via Certbot (simplest option for a single EC2 instance):
   ```bash
   sudo apt install -y certbot python3-certbot-nginx
   sudo certbot --nginx -d realestatepro.com -d www.realestatepro.com
   ```
   (Certbot auto-renews via a systemd timer — no cron entry needed.)

### 3.5 Queue Worker & Scheduler
1. Copy `deploy/supervisor-worker.conf` to `/etc/supervisor/conf.d/realestatepro-worker.conf`, then:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start realestatepro-worker:*
   ```
   This keeps 2 `queue:work` processes alive for Phase 18's queued Mailables — without this running, enquiry/welcome/notification emails sit in the `jobs` table and never send.
2. Laravel's scheduler (used if you add any `Schedule::command(...)` entries to `routes/console.php` later — e.g. cleaning old `property_views` rows):
   ```bash
   crontab -e
   # add:
   * * * * * cd /var/www/realestatepro && php artisan schedule:run >> /dev/null 2>&1
   ```

### 3.6 Route 53 — DNS
Point an `A` record (or `ALIAS` if you later add a Load Balancer) for `realestatepro.com` and `www.realestatepro.com` at the EC2 instance's Elastic IP (allocate one so the IP survives instance stop/start).

---

## 4. CI/CD — `.github/workflows/deploy.yml`

On every push to `main`:
1. **Test job** spins up a real MySQL 8 service container (matching Phase 19's requirement for a MySQL-backed test DB, since SQLite can't support the properties table's fullText index), runs migrations, then `php artisan test`.
2. **Deploy job** (only runs if tests pass) SSHes into the EC2 instance and runs `deploy/deploy.sh`, which pulls the latest code, reinstalls dependencies, migrates, rebuilds caches, and restarts PHP-FPM + the queue workers.

Required GitHub repo secrets: `AWS_EC2_HOST`, `AWS_EC2_USER` (typically `ubuntu`), `AWS_EC2_SSH_KEY` (the private key matching the EC2 key pair).

---

## 5. Production checklist

- [ ] `APP_ENV=production`, `APP_DEBUG=false` (never leave debug mode on — it leaks stack traces and `.env` values)
- [ ] `APP_KEY` generated and unique per environment
- [ ] RDS security group only allows the EC2 instance's security group, not `0.0.0.0/0`
- [ ] `FILESYSTEM_DISK=s3` so uploaded media survives instance replacement
- [ ] SES out of sandbox mode (production access requested and approved)
- [ ] Supervisor queue worker running (`supervisorctl status`)
- [ ] SSL certificate installed and auto-renewing
- [ ] `AdminUserSeeder`'s default password changed immediately after first login
- [ ] RDS automated backups enabled
- [ ] CloudWatch alarms on EC2 CPU/disk and RDS storage (optional but recommended)

---

## Project Complete 🎉

All 20 phases are now delivered. Type **Continue** and I'll generate the complete project folder tree and package everything built across this conversation into a single downloadable ZIP.
