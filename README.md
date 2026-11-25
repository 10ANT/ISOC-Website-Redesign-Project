# ISOC Jamaica Website

Laravel 12 application for the Internet Society Jamaica Chapter: public information website and an administration dashboard for chapter leaders. Membership is handled by the Internet Society, so the site has no public sign-up or member logins.

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
| admin@isocjm.org | Administrator: everything, including adding and removing website users |
| editor@isocjm.org | Editor: content, events, resources, messages, settings |

Set `SEED_USER_PASSWORD` in `.env` before seeding to use a different password. **Change all passwords before going live.**

## What's included

| Area | URL | Notes |
|---|---|---|
| Home | `/` | Hero, focus areas, next event (automatic), latest news, featured resources, ways to get involved |
| About | `/about`, `/about/{page}`, `/about/leadership` | Who we are, mission, history, plans (editable pages), board, officers, founding members, Hall of Fame |
| Our Work | `/our-work` | Programmes with related events, news and resources |
| Events | `/events` | Upcoming and past, search and category filter. Status (upcoming, live, past) is worked out from the dates |
| News | `/news` | Categories, tags, year archive, search |
| Resources | `/resources` | Filter by topic and type. Files are stored privately and streamed. "Leaders only" files are hidden from the public |
| Membership | `/membership` | Explains how to join through the Internet Society; the "Join via ISOC" button uses the link in **Site Settings** |
| Get Involved / Contact | `/get-involved`, `/contact` | Request a meeting, request an event or speaker, support the Chapter, suggest a project, volunteer or ask a question. Everything saves to the admin inbox (with a honeypot and rate limiting) |
| Search | `/search` | Searches pages, programmes, events, news and resources |
| Admin | `/admin` | Manage news, events, resources, programmes, pages, people, categories, media library, messages and requests, website users, site settings |

Long-form content is written in Markdown with an editor toolbar. Raw HTML is stripped when the content is shown.

## Roles and access

- Only chapter leaders sign in, from the **Leader sign in** link in the footer. An administrator adds each leader under **Website Users → Add a leader** and picks Administrator or Editor.
- Any other account is refused at sign-in. **Suspended** accounts are signed out and can't sign in.
- `access-admin` gate: approved admins and editors. `manage-users` gate: approved admins only.

## Tests

```bash
php artisan test
```

The tests cover every public page, leader-only sign-in, access rules, leaders-only downloads, the request forms and every admin screen.

## To confirm with ISOC Jamaica before launch

- Official logo and photography. See [ASSETS_NEEDED.md](ASSETS_NEEDED.md).
- Board and officer positions. They were taken from the current isocjm.org and need to be checked.
- History milestones, plans, and the privacy policy wording. These are draft text.
- Social media links and contact details, under **Admin → Site Settings**.
- Production mail settings (`MAIL_*`) so password reset emails are delivered.
"# ISOC-Website-Redesign-Project" 
