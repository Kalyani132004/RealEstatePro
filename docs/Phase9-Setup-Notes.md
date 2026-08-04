# RealEstatePro — Phase 9: Laravel Project Setup

This phase bootstraps the actual Laravel 12 application and wires up the **routing contract** that every Blade view built in Phases 3–8 already depends on. Controllers referenced in the route files below don't exist yet — they're built in **Phase 12** — but the route *names*, *URIs*, and *HTTP verbs* are final as of this phase and won't change.

---

## 1. Files delivered this phase

| File | Belongs at |
|---|---|
| `composer.json` | project root |
| `.env.example` | project root |
| `bootstrap/app.php` | `bootstrap/app.php` |
| `web.php` | `routes/web.php` |
| `auth.php` | `routes/auth.php` |
| `agent.php` | `routes/agent.php` |
| `admin.php` | `routes/admin.php` |
| `console.php` | `routes/console.php` |
| `filesystems.php` | `config/filesystems.php` |

---

## 2. Local installation steps

```bash
# 1. Create the project (or clone your repo containing all phases so far)
composer create-project laravel/laravel realestatepro "12.*"
cd realestatepro

# 2. Drop in every file delivered across Phases 1-9 into the matching folder
#    (composer.json, routes/*, bootstrap/app.php, config/filesystems.php,
#     resources/views/**, public/assets/**)

# 3. Install PHP dependencies
composer install

# 4. Environment
cp .env.example .env
php artisan key:generate

# 5. Configure your MySQL credentials in .env
#    DB_DATABASE=realestatepro / DB_USERNAME / DB_PASSWORD

# 6. Create the storage symlink (required — every image/video URL depends on it)
php artisan storage:link

# 7. Serve locally
php artisan serve
```

> No `npm install` / Vite build is required for this project — Bootstrap 5, Bootstrap Icons, Google Fonts, AOS, and Chart.js are all loaded via CDN `<link>`/`<script>` tags directly in the Blade layouts (see Phase 3–7), keeping the stack simple and matching the "HTML5/CSS3/Bootstrap 5/JS ES6" requirement without a frontend build pipeline.

At this point `php artisan serve` will boot, but visiting any page will throw a `Class not found` error for controllers — that's expected and resolved starting Phase 12. Phase 10 (migrations) and Phase 11 (models) come first so the database and Eloquent layer exist before the controllers that use them.

---

## 3. Full Route Reference (final contract)

### Public / Visitor
| Method | URI | Name | Controller (Phase 12) |
|---|---|---|---|
| GET | `/` | `home` | `HomeController@index` |
| GET | `/properties` | `properties.search` | `PropertyController@search` |
| GET | `/properties/{property:slug}` | `properties.show` | `PropertyController@show` |
| POST | `/enquiries` | `enquiries.store` | `EnquiryController@store` |

### Shared Authenticated (any role)
| Method | URI | Name |
|---|---|---|
| POST | `/saved-properties/toggle` | `saved-properties.toggle` |
| GET / PUT | `/user/profile` | `user.profile` / `user.profile.update` |
| GET / PUT | `/user/password` | `user.password` / `user.password.update` |

### Auth (guest)
| Method | URI | Name |
|---|---|---|
| GET / POST | `/register` | `register` |
| GET / POST | `/login` | `login` |
| GET / POST | `/forgot-password` | `password.request` / `password.email` |
| GET / POST | `/reset-password` | `password.reset` / `password.update` |

### Auth (authenticated)
| Method | URI | Name |
|---|---|---|
| POST | `/logout` | `logout` |
| GET | `/email/verify` | `verification.notice` |
| GET | `/email/verify/{id}/{hash}` | `verification.verify` |
| POST | `/email/verification-notification` | `verification.send` |

### User role (`role:user`)
| Method | URI | Name |
|---|---|---|
| GET | `/user/dashboard` | `user.dashboard` |
| GET | `/user/saved-properties` | `user.saved-properties` |
| GET | `/user/enquiries` | `user.enquiries` |

### Agent role (`role:agent`, prefix `/agent`)
| Method | URI | Name |
|---|---|---|
| GET | `/agent/dashboard` | `agent.dashboard` |
| GET/POST/PUT/DELETE | `/agent/properties/*` | `agent.properties.index/create/store/edit/update/destroy` (resource) |
| DELETE | `/agent/properties/gallery/{gallery}` | `agent.properties.gallery.destroy` |
| GET | `/agent/enquiries` | `agent.enquiries` |
| PATCH | `/agent/enquiries/{enquiry}/status` | `agent.enquiries.update-status` |

### Admin role (`role:admin`, prefix `/admin`)
| Method | URI | Name |
|---|---|---|
| GET | `/admin/dashboard` | `admin.dashboard` |
| GET | `/admin/reports` | `admin.reports` |
| GET/PATCH/DELETE | `/admin/users/*` | `admin.users.index/toggle-status/destroy` |
| GET/PATCH | `/admin/agents/*` | `admin.agents.index/toggle-verify` |
| GET/PATCH/DELETE | `/admin/properties/*` | `admin.properties.index/toggle-feature/destroy` |
| GET/POST/PUT/DELETE | `/admin/categories/*` | `admin.categories.index/store/update/destroy` (resource, no create/edit/show) |
| GET/POST/PUT/DELETE | `/admin/locations/*` | `admin.locations.index/store/update/destroy` (resource, no create/edit/show) |
| GET | `/admin/enquiries` | `admin.enquiries.index` |

---

## 4. Middleware note

`bootstrap/app.php` registers one alias: `'role' => App\Http\Middleware\EnsureUserHasRole::class`, used as `role:user` / `role:agent` / `role:admin` in the route files above. This replaces the three separate `EnsureUserRole` / `EnsureAgentRole` / `EnsureAdminRole` classes sketched in the Phase 1 folder structure — a single parameterized middleware is more DRY and easier to maintain, and is what actually gets implemented in **Phase 13 (Authentication Backend)**.

---

## 5. What's Next — Phase 10

Phase 10 will deliver the **Database Migrations** — one migration per table from the Phase 1 ER diagram (`users`, `agents`, `categories`, `locations`, `properties`, `galleries`, `amenities`, `amenity_property`, `enquiries`, `saved_properties`, `property_views`), in correct foreign-key dependency order.

Type **Continue** when you're ready for Phase 10.
