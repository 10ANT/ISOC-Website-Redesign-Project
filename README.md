# ISOC Jamaica Website

Laravel 12 application for the Internet Society Jamaica Chapter: public website, member portal and administration dashboard.

The front end uses the **TrueHelp** template's own markup, CSS and animations (owl carousels, GSAP split-text headings, WOW reveals, custom cursor). The home page uses the template's dark theme; inner pages use its light theme. The template's purple palette was re-mapped to ISOC black `#0c1c2c`, blue `#1d69d3` and yellow `#eeca4a` by `tools/recolor_theme.py`; `public/assets/css/isoc.css` pins the brand colours and adds the few ISOC-specific pieces.

**Photos:** see [ASSETS_NEEDED.md](ASSETS_NEEDED.md) for every image and file ISOC Jamaica needs to supply. The current photos are development placeholders from internetsociety.org.

## Requirements

- PHP 8.2+ with `pdo_sqlite` (or `pdo_mysql`), `fileinfo`, `mbstring`, `gd`
- Composer 2

No Node build step is needed. All CSS and JS is served from `public/assets`.

## Getting started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Then open http://127.0.0.1:8000.

### Using MySQL (XAMPP) instead of SQLite

Create a database, e.g. `isoc_jamaica`, then set this in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=isoc_jamaica
DB_USERNAME=root
DB_PASSWORD=
```

## Seeded accounts (local only, password `password`)

| Email | Role |
|---|---|
| admin@isocjm.org | Administrator: everything, including member management |
| editor@isocjm.org | Editor: content, events, resources, announcements, settings |
| member@isocjm.org | Approved member |
| pending@isocjm.org | Member awaiting approval |

Set `SEED_USER_PASSWORD` in `.env` before seeding to use a different password. **Change all passwords before going live.**

## What's included

| Area | URL | Notes |
|---|---|---|
| Home | `/` | Hero, focus areas, next event (automatic), latest news, featured resources, ways to get involved |
| About | `/about`, `/about/{page}`, `/about/leadership` | Who we are, mission, history, plans (editable pages), board, officers, founding members, Hall of Fame |
| Our Work | `/our-work` | Programmes with related events, news and resources |
| Events | `/events` | Upcoming and past, search and category filter. Status (upcoming, live, past) is worked out from the dates |
| News | `/news` | Categories, tags, year archive, search |
| Resources | `/resources` | Filter by topic and type. Files are stored privately and streamed. Members-only files need an approved member |
| Membership / Get Involved / Contact | `/membership`, `/get-involved`, `/contact` | Volunteer and contact forms save to the admin inbox (with a honeypot and rate limiting) |
| Search | `/search` | Searches pages, programmes, events, news and resources |
| Member portal | `/portal` | Dashboard, member announcements, members-only resources, profile, password |
| Admin | `/admin` | Manage news, events, resources, programmes, pages, people, categories, media library, announcements, messages, members, site settings |

Long-form content is written in Markdown with an editor toolbar. Raw HTML is stripped when the content is shown.

## Roles and access

- New registrations start as **pending** members. An administrator approves them from the dashboard or from **Members & Users**.
- **Suspended** accounts are signed out and can't sign in.
- `access-admin` gate: approved admins and editors. `manage-users` gate: approved admins only.

## Tests

```bash
php artisan test
```

The tests cover every public page, the member portal, access rules, members-only downloads, forms and every admin screen.

## To confirm with ISOC Jamaica before launch

- Official logo and photography. See [ASSETS_NEEDED.md](ASSETS_NEEDED.md).
- Board and officer positions. They were taken from the current isocjm.org and need to be checked.
- History milestones, plans, and the privacy policy wording. These are draft text.
- Social media links and contact details, under **Admin → Site Settings**.
- Production mail settings (`MAIL_*`) so password reset emails are delivered.
"# ISOC-Website-Redesign-Project" 
