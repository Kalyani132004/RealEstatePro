# 🏡 RealEstatePro

A modern **Real Estate Property Listing & Management System** built using **Laravel 12**, **PHP 8.3**, **MySQL**, and **Bootstrap 5**.

RealEstatePro is a web-based property platform where users can browse and search properties, agents can manage their own property listings and enquiries, and administrators can manage the platform.

---

# ✨ Features

- User Registration & Login
- Role Based Access Control
- User Dashboard
- Agent Dashboard
- Admin Dashboard
- Property Listing Management
- Property Search
- Property Filtering
- Property Categories
- Property Locations
- Featured Properties
- Recent Properties
- Property Details
- Property Image Upload
- Wishlist / Favourite Properties
- Property Enquiry System
- Enquiry Status Management
- Agent Enquiry Management
- User Profile Management
- Responsive Design
- Dark / Light Theme

---

# 👥 User Roles

## 👤 User / Buyer / Renter

- Register & Login
- Search Properties
- Filter Properties
- View Property Details
- Save Properties to Wishlist
- Send Property Enquiries
- Manage Profile
- Access User Dashboard

---

## 🏠 Agent

- Register as Agent
- Login
- Add Properties
- Edit Own Properties
- Delete Own Properties
- Upload Property Images
- View Own Property Listings
- Manage Property Enquiries
- Update Enquiry Status
- Access Agent Dashboard


---

# 🛠 Tech Stack

### Backend
- Laravel 12
- PHP 8.3
- Eloquent ORM

### Frontend
- Blade Templates
- Bootstrap 5
- HTML5
- CSS3
- JavaScript
- Bootstrap Icons

### Database
- MySQL

### Storage
- Laravel Storage

### Authentication
- Laravel Authentication
- Role Based Access Control

---

# 📂 Project Structure

app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
artisan
composer.json

---

# 🚀 Installation

### 1. Clone the Repository

git clone https://github.com/Kalyani132004/RealEstatePro.git

### 2. Go to Project Directory

cd RealEstatePro

### 3. Install Composer Packages

composer install

### 4. Create Environment File

copy .env.example .env

### 5. Generate Application Key

php artisan key:generate

### 6. Configure Database

Update your .env file with your MySQL database configuration.

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=realestatepro
DB_USERNAME=root
DB_PASSWORD=

### 7. Run Migrations

php artisan migrate

### 8. Create Storage Link

php artisan storage:link

### 9. Start Development Server

php artisan serve

### 10. Open in Browser

http://127.0.0.1:8000

---

# 🔐 Access Control

RealEstatePro uses role-based access control.

- User → Property browsing, wishlist and enquiries
- Agent → Own property and enquiry management

---

# 👩‍💻 Developer

**Kalyani Sonawane**

GitHub:
https://github.com/Kalyani132004