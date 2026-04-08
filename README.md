# Leads Management Dashboard

Production-ready Leads Management Dashboard built with Laravel, Blade, vanilla JavaScript, and SQLite/MySQL compatibility.

## Tech Stack

- Laravel (latest stable)
- Blade templating
- Eloquent ORM
- SQLite by default (can switch to MySQL through `.env`)
- Vanilla JavaScript (AJAX search/filter/pagination + delete)
- Clean custom CSS dashboard styling

## Features

- Full CRUD for leads
- Form Request validation with user-friendly messages
- Search by name/email
- Filter by status (`new`, `contacted`, `qualified`, `lost`)
- Combined search + filter + persistent pagination params
- Pagination (10 per page)
- AJAX enhancements:
  - live search/filter table refresh
  - AJAX pagination updates
  - AJAX delete with confirmation
- Reusable Blade layout and status badge component
- Lead service layer (`LeadService`) for query/filter logic
- Seed data with 20 realistic leads
- Feature tests for core CRUD and validation paths

## Setup Instructions

```bash
git clone <your-repo-url>
cd test-task-team-leads
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Testing

```bash
php artisan test
```

## Screenshots

- Dashboard list view: `docs/screenshots/dashboard-list.png` (placeholder)
- Create lead form: `docs/screenshots/create-lead.png` (placeholder)
- Edit lead form: `docs/screenshots/edit-lead.png` (placeholder)

## Project Structure (Key Parts)

```text
app/
  Http/
    Controllers/LeadController.php
    Requests/StoreLeadRequest.php
    Requests/UpdateLeadRequest.php
  Models/Lead.php
  Services/LeadService.php
database/
  migrations/*_create_leads_table.php
  seeders/LeadSeeder.php
  seeders/DatabaseSeeder.php
public/
  css/dashboard.css
  js/leads.js
resources/
  views/layouts/app.blade.php
  views/components/status-badge.blade.php
  views/leads/index.blade.php
  views/leads/_table.blade.php
  views/leads/create.blade.php
  views/leads/edit.blade.php
  views/leads/_form.blade.php
routes/
  web.php
tests/
  Feature/LeadCrudTest.php
```

## Notes

- `.env.example` is configured for SQLite by default.
- To use MySQL, update DB connection variables in `.env`.
