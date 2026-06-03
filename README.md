# 🗺️ Travel Tracker — Laravel + SQLite

A simple travel tracking system to record trips by **air, sea, or land** with destination, purpose/project, amount, passengers, itinerary upload, and date.

---

## 🚀 Quick Setup (from scratch)

### 1. Create a new Laravel project
```bash
composer create-project laravel/laravel travel-tracker
cd travel-tracker
```

### 2. Copy these files into your project
Copy all provided files into their matching paths inside the Laravel project.

### 3. Configure SQLite
Edit `.env` — replace the DB section with:
```env
DB_CONNECTION=sqlite
# DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD — delete or comment these out
```

Create the SQLite database file:
```bash
touch database/database.sqlite
```

### 4. Run migrations
```bash
php artisan migrate
```

### 5. (Optional) Seed sample data
```bash
php artisan db:seed --class=TravelSeeder
```

### 6. Create storage symlink (for file uploads)
```bash
php artisan storage:link
```

### 7. Start the server
```bash
php artisan serve
```

Open: **http://localhost:8000**

---

## 📁 File Structure

```
app/
  Models/Travel.php                          ← Eloquent model
  Http/Controllers/TravelController.php      ← Full CRUD controller

database/
  migrations/..._create_travels_table.php    ← DB schema
  seeders/TravelSeeder.php                   ← Sample data

resources/views/
  layouts/app.blade.php                      ← Main layout
  travels/
    index.blade.php                          ← List + stats + filters
    create.blade.php                         ← Add new travel
    edit.blade.php                           ← Edit travel
    show.blade.php                           ← Detail view

routes/web.php                               ← Route definitions
```

---

## 📋 Features

| Feature | Details |
|---|---|
| Travel Mode | Air ✈️ / Sea 🚢 / Land 🚌 (visual selector) |
| Route | Origin → Destination |
| Purpose | Free-text project/purpose name |
| Date | Travel date + optional return date |
| Passengers | Count |
| Amount | Decimal cost in ₱ |
| Itinerary | Upload PDF, Word, Excel, or image (max 5MB) |
| Filters | Filter by mode, purpose, and date range |
| Dashboard | Summary stats (total trips, per mode, total cost, total pax) |

---

## 🔧 Extending the System

- **Export to Excel/CSV** — add `maatwebsite/excel` package
- **Authentication** — run `php artisan make:auth` or use Laravel Breeze
- **Reports** — add a `/reports` route with grouped queries
- **Approval workflow** — add a `status` column (draft/pending/approved)
