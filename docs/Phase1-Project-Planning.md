# RealEstatePro — Phase 1: Project Planning

**Domain:** PropTech — Property Listing, Virtual Tour & Enquiry Portal
**Stack:** Laravel 12, PHP 8.3, MySQL, Blade, Bootstrap 5, AJAX, AWS

This document is the foundation for all remaining 19 phases. Every later phase (UI, migrations, models, controllers, etc.) will strictly follow the structure, naming, and schema defined here — so please review it carefully before typing **Continue**.

---

## 1. High-Level Architecture

```
Browser (Blade + Bootstrap 5 + AJAX)
        │
        ▼
Laravel 12 Application (MVC)
 ├── Routes (web.php, admin.php, agent.php, api.php)
 ├── Controllers (thin, delegate to Services/Requests)
 ├── Form Requests (validation)
 ├── Models (Eloquent + relationships)
 ├── Policies (authorization per role)
 ├── Mailables (Laravel Mail)
 └── Storage (public disk → S3 on AWS in Phase 20)
        │
        ▼
MySQL Database
```

**Roles:** `user` (visitor who registers), `agent`, `admin` — single `users` table + `role` enum, with an `agents` profile table extending agent-role users. This avoids duplicate auth tables and keeps Laravel's built-in Auth scaffolding intact.

**Design pattern:** Repository-light, Service-layer-light MVC — Controllers stay thin, Form Requests validate, Models hold relationships + scopes (query builder filters via local scopes), Blade components handle reusable UI. SOLID is respected via single-responsibility Form Requests/Controllers and interface-driven Mail/Notification classes.

---

## 2. Complete Module List

| # | Module | Roles | Key Screens |
|---|--------|-------|-------------|
| 1 | Home & Discovery | Visitor | Home, Search, Category listing |
| 2 | Property Search & Filters | Visitor | Search results w/ AJAX filters |
| 3 | Property Details | Visitor | Gallery, Virtual Tour, Floor Plan, Map, Enquiry |
| 4 | Authentication | Guest | Register, Login, Forgot/Reset Password, Email Verify |
| 5 | User Dashboard | User | Saved Properties, My Enquiries, Profile, Change Password |
| 6 | Agent Dashboard | Agent | Add/Edit/Delete Property, Upload Media, Manage Enquiries |
| 7 | Admin Dashboard | Admin | Manage Users/Agents/Properties/Categories/Locations/Enquiries, Reports |
| 8 | Enquiry Engine | All | Enquiry form → Mail → Agent/Admin notification |
| 9 | Media Management | Agent/Admin | Image gallery upload, video upload, floor plan upload |
| 10 | Notifications | All | Email (Laravel Mail), Toast (frontend) |

---

## 3. Database Design (MySQL)

> All tables use `id` (BIGINT UNSIGNED, PK, auto-increment), `created_at`, `updated_at` unless noted. Naming follows Laravel conventions (snake_case, plural table names) for zero-config Eloquent mapping.

### 3.1 `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar(255) | |
| email | varchar(255) unique | |
| email_verified_at | timestamp null | |
| password | varchar(255) | hashed |
| role | enum('user','agent','admin') default 'user' | |
| phone | varchar(20) null | |
| avatar | varchar(255) null | storage path |
| status | enum('active','blocked') default 'active' | |
| remember_token | varchar(100) null | |

### 3.2 `agents` (1-1 extension of a `users` row with role=agent)
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK → users.id (unique) | cascade delete |
| agency_name | varchar(255) null | |
| license_no | varchar(100) null | |
| bio | text null | |
| whatsapp | varchar(20) null | |
| experience_years | tinyint unsigned default 0 | |
| is_verified | boolean default false | admin-approved |

### 3.3 `categories`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar(100) | e.g. Apartment, Villa, Plot |
| slug | varchar(120) unique | |
| icon | varchar(100) null | bootstrap-icon class |
| description | text null | |

### 3.4 `locations`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| city | varchar(100) | |
| state | varchar(100) | |
| country | varchar(100) default 'India' | |
| zip_code | varchar(20) null | |
| latitude | decimal(10,7) null | |
| longitude | decimal(10,7) null | |

### 3.5 `properties`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| agent_id | bigint FK → agents.id | cascade |
| category_id | bigint FK → categories.id | restrict |
| location_id | bigint FK → locations.id | restrict |
| title | varchar(255) | |
| slug | varchar(280) unique | |
| description | longtext | |
| listing_type | enum('sale','rent') | |
| status | enum('available','pending','sold','rented') default 'available' | |
| price | decimal(14,2) | |
| area_sqft | decimal(10,2) | |
| bedrooms | tinyint unsigned default 0 | |
| bathrooms | tinyint unsigned default 0 | |
| floors | tinyint unsigned default 1 | |
| year_built | year null | |
| address | varchar(500) | |
| latitude | decimal(10,7) null | |
| longitude | decimal(10,7) null | |
| virtual_tour_video | varchar(255) null | storage path (mp4) |
| floor_plan_image | varchar(255) null | storage path (used by canvas) |
| cover_image | varchar(255) null | storage path |
| is_featured | boolean default false | |
| views_count | unsigned int default 0 | |
| deleted_at | timestamp null | **soft deletes** |

### 3.6 `galleries`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| property_id | bigint FK → properties.id | cascade |
| image_path | varchar(255) | |
| sort_order | smallint default 0 | |

### 3.7 `amenities`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| name | varchar(100) | e.g. Swimming Pool, Gym, Parking |
| icon | varchar(100) null | |

### 3.8 `amenity_property` (pivot)
| Column | Type |
|---|---|
| amenity_id | FK → amenities.id |
| property_id | FK → properties.id |

### 3.9 `enquiries`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| property_id | bigint FK → properties.id | cascade |
| user_id | bigint FK → users.id nullable | guest enquiries allowed |
| agent_id | bigint FK → agents.id | denormalized for fast agent queries |
| name | varchar(150) | |
| email | varchar(150) | |
| phone | varchar(20) | |
| message | text | |
| status | enum('new','contacted','closed') default 'new' | |

### 3.10 `saved_properties` (pivot: user "wishlist")
| Column | Type |
|---|---|
| id | bigint PK |
| user_id | FK → users.id |
| property_id | FK → properties.id |
| (unique user_id+property_id) | |

### 3.11 `property_views` (for Admin Reports)
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| property_id | FK → properties.id | |
| ip_address | varchar(45) null | |
| viewed_at | timestamp | |

### Laravel default tables (kept as-is)
`password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`.

---

## 4. Entity-Relationship Diagram

A Mermaid ER diagram is provided as a separate renderable file: **Phase1-ER-Diagram.mermaid**

Relationship summary:
- `User (1) ── (1) Agent` — an agent-role user has exactly one agent profile
- `Agent (1) ── (∞) Property`
- `Category (1) ── (∞) Property`
- `Location (1) ── (∞) Property`
- `Property (1) ── (∞) Gallery`
- `Property (∞) ── (∞) Amenity` via `amenity_property`
- `Property (1) ── (∞) Enquiry`
- `User (1) ── (∞) Enquiry` (nullable — guests can enquire)
- `User (∞) ── (∞) Property` via `saved_properties` (wishlist)
- `Property (1) ── (∞) PropertyView`

---

## 5. Eloquent Relationship Map (implemented in Phase 11)

```
User
 ├─ hasOne(Agent::class)
 ├─ hasMany(Enquiry::class)
 └─ belongsToMany(Property::class, 'saved_properties')->withTimestamps()

Agent
 ├─ belongsTo(User::class)
 └─ hasMany(Property::class)

Property
 ├─ belongsTo(Agent::class)
 ├─ belongsTo(Category::class)
 ├─ belongsTo(Location::class)
 ├─ hasMany(Gallery::class)
 ├─ hasMany(Enquiry::class)
 ├─ hasMany(PropertyView::class)
 ├─ belongsToMany(Amenity::class)
 └─ belongsToMany(User::class, 'saved_properties') // "savedByUsers"

Category  hasMany(Property::class)
Location  hasMany(Property::class)
Amenity   belongsToMany(Property::class)
Enquiry   belongsTo(Property::class), belongsTo(User::class), belongsTo(Agent::class)
Gallery   belongsTo(Property::class)
```

---

## 6. Complete Folder Structure (Laravel 12)

```
realestatepro/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   ├── HomeController.php
│   │   │   ├── PropertyController.php
│   │   │   ├── SearchController.php
│   │   │   ├── EnquiryController.php
│   │   │   ├── Auth/
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   ├── ResetPasswordController.php
│   │   │   │   └── VerificationController.php
│   │   │   ├── User/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── SavedPropertyController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   └── PasswordController.php
│   │   │   ├── Agent/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── PropertyController.php
│   │   │   │   ├── GalleryController.php
│   │   │   │   ├── VideoController.php
│   │   │   │   └── EnquiryController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── UserController.php
│   │   │       ├── AgentController.php
│   │   │       ├── PropertyController.php
│   │   │       ├── CategoryController.php
│   │   │       ├── LocationController.php
│   │   │       ├── EnquiryController.php
│   │   │       └── ReportController.php
│   │   ├── Middleware/
│   │   │   ├── EnsureUserRole.php
│   │   │   ├── EnsureAgentRole.php
│   │   │   └── EnsureAdminRole.php
│   │   └── Requests/
│   │       ├── Auth/RegisterRequest.php
│   │       ├── Property/StorePropertyRequest.php
│   │       ├── Property/UpdatePropertyRequest.php
│   │       ├── EnquiryRequest.php
│   │       ├── ProfileUpdateRequest.php
│   │       ├── CategoryRequest.php
│   │       └── LocationRequest.php
│   ├── Mail/
│   │   ├── EnquiryReceivedMail.php
│   │   ├── EnquiryNotifyAgentMail.php
│   │   └── WelcomeMail.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Agent.php
│   │   ├── Property.php
│   │   ├── Category.php
│   │   ├── Location.php
│   │   ├── Gallery.php
│   │   ├── Amenity.php
│   │   ├── Enquiry.php
│   │   └── PropertyView.php
│   ├── Policies/
│   │   ├── PropertyPolicy.php
│   │   └── EnquiryPolicy.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── View/Components/
│       ├── PropertyCard.php
│       ├── Navbar.php
│       └── ToastContainer.php
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   │   ├── UserFactory.php
│   │   ├── AgentFactory.php
│   │   ├── PropertyFactory.php
│   │   └── ... (one per model)
│   ├── migrations/
│   │   └── (Phase 10 — timestamped files)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── CategorySeeder.php
│       ├── LocationSeeder.php
│       ├── AmenitySeeder.php
│       └── AdminUserSeeder.php
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── app.css
│   │   │   └── theme.css
│   │   ├── js/
│   │   │   ├── app.js
│   │   │   ├── filters.js
│   │   │   ├── floorplan-canvas.js
│   │   │   └── theme-toggle.js
│   │   └── img/
│   └── storage/  (symlinked to storage/app/public)
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── auth.blade.php
│   │   │   ├── user-dashboard.blade.php
│   │   │   ├── agent-dashboard.blade.php
│   │   │   └── admin-dashboard.blade.php
│   │   ├── components/
│   │   │   ├── navbar.blade.php
│   │   │   ├── footer.blade.php
│   │   │   ├── property-card.blade.php
│   │   │   └── toast.blade.php
│   │   ├── home/
│   │   ├── properties/
│   │   │   ├── search.blade.php
│   │   │   └── show.blade.php
│   │   ├── auth/
│   │   ├── user/
│   │   ├── agent/
│   │   └── admin/
│   ├── lang/
│   └── views/emails/
├── routes/
│   ├── web.php
│   ├── auth.php
│   ├── agent.php
│   └── admin.php
├── storage/
│   └── app/public/
│       ├── properties/
│       ├── avatars/
│       └── videos/
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env.example
├── composer.json
└── package.json
```

---

## 7. Route Grouping Strategy (implemented Phase 12-13)

- `web.php` → Visitor + shared routes (home, search, property details, enquiry)
- `auth.php` → Breeze-style auth routes (register/login/forgot/verify)
- Prefix `/user` + middleware `['auth','verified','role:user']` → User dashboard
- Prefix `/agent` + middleware `['auth','verified','role:agent']` → Agent dashboard
- Prefix `/admin` + middleware `['auth','role:admin']` → Admin dashboard

---

## 8. What's Next — Phase 2

Phase 2 will deliver the **UI Design System**: color palette (light/dark), typography scale, spacing/radius tokens, icon set, and reusable component specs (glassmorphic cards, buttons, badges, navbar, toast) that every frontend phase (3–8) will reuse consistently.

Type **Continue** when you're ready for Phase 2.
