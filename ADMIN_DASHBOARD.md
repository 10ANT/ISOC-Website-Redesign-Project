# How the Admin Dashboard Was Built

The admin dashboard (`/admin`) is **not a purchased or downloaded admin template**. It was designed specifically for this project, as a lightweight layer on top of **Bootstrap 5**. The TrueHelp template we were given is a public-website template and has no admin screens, so the dashboard had to be built.

## What it's made of

| Piece | What it is | Where it lives |
|---|---|---|
| **Bootstrap 5.3** | The CSS/JS framework behind the grid, cards, tables, forms, buttons, badges, alerts and the user dropdown. It already ships with the TrueHelp template, so nothing extra was installed. | `public/assets/vendors/bootstrap/` |
| **`admin.css`** (about 100 lines) | All of the dashboard's own look: the dark sidebar, the white top bar, soft card borders, table header styling, stat cards, the media grid, and the ISOC colours on Bootstrap's buttons and focus states. | `public/assets/css/admin.css` |
| **Fonts** | **Manrope** for text and **Geologica** for headings, the same fonts as the TrueHelp template, so the admin feels like part of the same site. Loaded from Google Fonts. | `resources/views/layouts/admin.blade.php` |
| **Icons** | **Font Awesome 6 (Free)**, used for the sidebar, buttons and stat cards. Self-hosted. | `public/assets/vendors/fontawesome/` |
| **Editor** | **EasyMDE**, a simple open-source Markdown editor with a formatting toolbar and preview, used for long text (news articles, event descriptions, pages). Loaded from the jsDelivr CDN. | `resources/views/layouts/admin.blade.php` |
| **Colours** | The ISOC palette: navy `#0c1c2c` sidebar, ISOC blue `#1d69d3` for buttons and active links, yellow `#eeca4a` for sidebar icons. | top of `admin.css` |

## How it's structured

- **Layout** (`resources/views/layouts/admin.blade.php`): a fixed dark sidebar grouped into Overview, Content, People and Settings; a sticky top bar with the page title and the user menu; and a light grey content area. On phones the sidebar slides in from a menu button. The layout also sets up the Markdown editor, the "Are you sure?" prompt before deleting, and the "copy link" buttons.
- **Reusable form components** (`resources/views/components/admin/`): `input`, `textarea`, `select`, `checkbox` (switch style), `image` (upload with preview and remove) and `delete-button`. Every admin form is built from these, which is why all the forms look and behave the same.
- **Consistent page pattern:** each section (News, Events, Resources and so on) has
  - a **list page**: a search and filter row, an action button on the right, and a table in a card with view/edit/delete buttons;
  - a **form page**: the main content on the left (8 columns) and a "Publishing" card on the right with Save/Cancel, then a "Details" card.
- **Dashboard home** (`resources/views/admin/dashboard.blade.php`): quick-action buttons, four stat cards, latest messages and requests, upcoming events and draft news.
- **Website Users and My profile:** administrators add each leader (Administrator or Editor) with a starting password; every leader can change their own details and password under **My profile**. There is no public sign-up: membership is handled by the Internet Society.

## How it came about

1. The layout, `admin.css`, the form components and the News screens (`admin/posts/index` and `admin/posts/form`) were designed first, as the reference pattern.
2. The remaining screens (Events, Resources, Our Work, Pages, People, Categories, Announcements, Messages, Media, Members, Settings and the dashboard) were then built to follow that same pattern exactly, and a test was added that opens every admin screen to make sure each one loads.

The look follows the common "sidebar + top bar + cards" admin layout used by most modern admin panels. It isn't copied from any particular product: there are no third-party admin template files in the project, only Bootstrap plus our own small stylesheet.

## Reusing it elsewhere

Because it's just Bootstrap plus one stylesheet and a Blade layout, it's easy to reuse in another Laravel project. Copy:

- `resources/views/layouts/admin.blade.php`
- `resources/views/components/admin/*`
- `public/assets/css/admin.css`

Then change the colour variables at the top of `admin.css` and the sidebar links in the layout.
