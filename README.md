# PH Haiyan Advocacy Inc.

Premium Laravel 11 advocacy website for PH Haiyan Advocacy Inc., built with Blade, Tailwind CSS, Alpine.js, and a content model prepared for future admin or CMS expansion.

## Stack

- Laravel 11
- PHP 8.2+
- Blade templating
- Tailwind CSS
- Alpine.js
- MySQL-ready schema
- Vite asset pipeline

## What’s Included

- Premium responsive homepage with:
  - top bar
  - sticky dropdown navbar
  - hero section
  - mission pillars
  - about preview
  - what we do preview
  - featured videos
  - latest news
  - upcoming events
  - recent activities
  - impact CTA
  - full footer
- Full public pages:
  - Home
  - About PH Haiyan
  - What We Do
  - Gallery
  - Forums
  - Letters
  - Contact
- Supporting archive pages:
  - News
  - Events
  - Support the Mission
- Admin-ready content structure with migrations, models, and seeders for:
  - `pages`
  - `news_posts`
  - `events`
  - `activities`
  - `videos`
  - `gallery_items`
  - `letters`
  - `forum_topics`
  - `contact_messages`
- Contact form with Laravel validation and database persistence
- Local SVG brand and scene assets, so the project does not depend on external stock imagery
- Feature tests for public pages and contact message submission

## Local Installation

1. Install PHP dependencies:

```bash
composer install
```

2. Install frontend dependencies:

```bash
npm install
```

3. Copy environment values if needed:

```bash
copy .env.example .env
```

4. Generate an application key:

```bash
php artisan key:generate
```

5. Configure the database.

Local `.env` in this workspace is set to SQLite for quick startup.

If you want to use SQLite locally:

```bash
type nul > database\database.sqlite
```

If you want MySQL locally, update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ph_haiyan_advocacy
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations and seed the content:

```bash
php artisan migrate:fresh --seed
```

7. Start the app:

```bash
php artisan serve
```

8. Start Vite during development:

```bash
npm run dev
```

## Production Build

Compile frontend assets before deployment:

```bash
npm run build
```

## Tests

Run the Laravel test suite:

```bash
php artisan test
```

## Where to Edit Content

### Global organization and contact details

- `config/site.php`

Use this for:

- email
- phone
- address
- socials
- SEO defaults
- Google Search Console verification
- GA4 measurement ID
- top bar links
- footer quick links
- support actions
- inquiry types

### Seeded page content

- `database/seeders/PageSeeder.php`

Use this for:

- homepage hero copy
- mission pillars
- about page narrative
- values
- timeline
- what we do overview
- support page copy
- page hero text

### Seeded archive and module content

- `database/seeders/NewsPostSeeder.php`
- `database/seeders/EventSeeder.php`
- `database/seeders/ActivitySeeder.php`
- `database/seeders/VideoSeeder.php`
- `database/seeders/GalleryItemSeeder.php`
- `database/seeders/LetterSeeder.php`
- `database/seeders/ForumTopicSeeder.php`

After editing seeders, refresh the sample content with:

```bash
php artisan migrate:fresh --seed
```

### Layout and UI components

- `resources/views/layouts/app.blade.php`
- `resources/views/components/topbar.blade.php`
- `resources/views/components/navbar.blade.php`
- `resources/views/components/footer.blade.php`
- `resources/views/components/hero.blade.php`
- `resources/views/components/section-header.blade.php`
- `resources/views/components/cards/*.blade.php`

### Page templates

- `resources/views/home/index.blade.php`
- `resources/views/about/index.blade.php`
- `resources/views/what-we-do/index.blade.php`
- `resources/views/gallery/index.blade.php`
- `resources/views/forums/index.blade.php`
- `resources/views/letters/index.blade.php`
- `resources/views/contact/index.blade.php`
- `resources/views/news/index.blade.php`
- `resources/views/events/index.blade.php`
- `resources/views/support/index.blade.php`

### Styling and interactions

- `resources/css/app.css`
- `resources/js/app.js`
- `tailwind.config.js`

### Routing and controllers

- `routes/web.php`
- `app/Http/Controllers/*`

## Content Architecture Notes

The site is intentionally split into:

- config-managed global organization data
- seeded database content for page modules and archives
- reusable Blade components for presentation

That makes it easier to add a future admin panel, Filament dashboard, or custom CMS layer without rebuilding the frontend.

## Hostinger Deployment Notes

This project is prepared for a standard Laravel deployment on Hostinger.

### Recommended deployment steps

1. Upload the project or deploy through Git.
2. Run:

```bash
composer install --no-dev --optimize-autoloader
```

3. Set your production `.env` values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-hostinger-db-host
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

4. Generate an app key if needed:

```bash
php artisan key:generate
```

5. Run database migrations:

```bash
php artisan migrate --force
```

6. Seed the sample site content if needed:

```bash
php artisan db:seed --force
```

7. Build frontend assets before upload, or on the server if Node is available:

```bash
npm install
npm run build
```

8. Ensure the domain points to Laravel’s `public` directory.

### Suggested production settings

- `SESSION_DRIVER=file`
- `CACHE_STORE=file`
- `QUEUE_CONNECTION=database` or `sync`
- `MAIL_MAILER=smtp` after mail credentials are ready

### SEO follow-up checklist

- Set `APP_URL` to the live canonical domain before indexing.
- Run `php artisan migrate --force` so the SEO fields and image alt text fields are available.
- Confirm `/robots.txt` and `/sitemap.xml` load on the production domain.
- Add the Google Search Console verification token and GA4 measurement ID in Admin > Site Settings when ready.
- Refresh caches after deployment and content updates:

```bash
php artisan optimize
```

## Current Verification

Verified in this workspace with:

- `php artisan migrate:fresh --seed`
- `npm run build`
- `php artisan test`

## Notes

- `contact_messages` are stored in the database and ready for later email notifications or admin inbox workflows.
- The gallery, forums, and letters archives already use Alpine.js for lightweight interactivity.
- The content model is intentionally seed-driven for now, but structured so Eloquent-backed admin editing can be added cleanly.

## Future SEO Content Ideas

- What Climate Resilience Means in the Philippines
- Why Environmental Protection Matters for Coastal Communities
- Mangrove Reforestation and Community Resilience
- How Advocacy Helps Disaster Preparedness
- Youth Involvement in Environmental Action
