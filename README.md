# EPiTEK Solutions — corporate website

A fast, mobile-first PHP site for **EPiTEK Solutions**, an aviation and air-force engineering,
integration, training and sustainment company working across East Africa (Kenya, Uganda, Tanzania,
Rwanda, Ethiopia).

Built from the EPiTEK brand (navy `#294776`, accent blue `#207EB0`). Each subject has its own page:
capabilities, services, training, standards and company. Nothing on the site names or claims a
relationship with any third-party manufacturer or its products.

---

## 1. Requirements

- PHP **8.1+** (tested on 8.2.12) with `mbstring`
- Apache with `mod_rewrite`, `mod_headers`, `mod_deflate`, `mod_expires` (XAMPP defaults are fine)
- No build step, no Composer, no database, no external CDN

## 2. Run locally

**Option A — built-in server**

```bash
php -S 127.0.0.1:8123 -t .
```

Open <http://127.0.0.1:8123/>. (The `.htaccess` rules are Apache-only; the built-in server still
serves the directory indexes.)

**Option B — XAMPP**

Copy the folder to `htdocs/epitek` and open `http://localhost/epitek/`.
`BASE_PATH` detects the subfolder automatically, so links and assets work at either a domain root
or in a subfolder. `.htaccess` needs `AllowOverride All` for the clean-URL and deny rules.

## 3. Pages

| URL | File | Purpose |
|---|---|---|
| `/` | `index.php` | Short introduction, four capability cards, operating model |
| `/capabilities` | `capabilities/index.php` | Hub for the four capability areas |
| `/capabilities/air-force` | `capabilities/air-force/index.php` | Air force and uncrewed systems |
| `/capabilities/aviation-software` | `capabilities/aviation-software/index.php` | Mission software and avionics |
| `/capabilities/command-surveillance` | `capabilities/command-surveillance/index.php` | Sensors, command posts, coastal awareness |
| `/capabilities/depot-sustainment` | `capabilities/depot-sustainment/index.php` | Depot maintenance and spares |
| `/services` | `services/index.php` | Hub for the five services + FAQ |
| `/services/avionics-maintenance` | `services/avionics-maintenance/index.php` | Avionics maintenance engineering |
| `/services/compliance-training` | `services/compliance-training/index.php` | ICAO and EUROCONTROL training |
| `/services/uas-training` | `services/uas-training/index.php` | UAS operator certification |
| `/services/support-desk` | `services/support-desk/index.php` | Tiered support desk |
| `/services/advisory` | `services/advisory/index.php` | Technical advisory |
| `/training` | `training/index.php` | Four-stage training pathway |
| `/standards` | `standards/index.php` | Standards table and export control |
| `/about` | `about/index.php` | Company, commitments, footprint |
| `/distributor-integration` | `distributor-integration/index.php` | Logistics, integration architecture, depot |
| `/contact` | `contact/index.php` | Enquiry form + compliance notice |
| `/apply` | `apply/index.php` | Training and organisation application form |
| `/privacy` `/cookies` `/terms` `/security` | `*/index.php` | Legal and security pages |

Shared code lives in `includes/`: `config.php` (brand + constants), `content.php` (all editorial
arrays), `functions.php` (helpers/security), `header.php`, `domain-bar.php`, `footer.php`.

## 4. Editing content

- **Company details, phone, LinkedIn, mail addresses:** `includes/config.php`.
- **Domains, portfolio, standards table, services, countries, FAQ, metrics, briefs:** `includes/content.php`.
- **Page-specific sections:** each page's `index.php` (plain PHP + HTML, escaped with `e()`).

The visible wordmark is always **EPiTEK Solutions**. The blueprint gives the domain
`epitexsolution.africa` and email `contact@epitexsolution.africa` (one "k"). Confirm the final
legal spelling before launch (blueprint item V-7) and change it in one place, `config.php`.

## 5. Contact form

- Server-side validation, CSRF token, honeypot, 3-second timing trap, 5 requests/hour/IP throttle.
- Enquiries are always written to `storage/leads/leads-YYYYMM.jsonl` (never inside the web root path
  is blocked by `.htaccess`, see below).
- Email delivery is **off by default**:

```php
const MAIL_ENABLED = false;   // includes/config.php
const MAIL_TO      = 'contact@epitexsolution.africa';
const MAIL_FROM    = 'website@epitexsolution.africa';
```

Set `MAIL_ENABLED` to `true` once the host can send mail (PHP `mail()` / local MTA). For SMTP,
replace the `@mail(...)` call in `contact/index.php` with the host's SMTP transport and keep the
same headers. Until then the success screen tells the sender the request was recorded and gives
the direct email address, which is honest rather than pretending mail was delivered.

Permissions: `storage/` must be writable by PHP (`0750` on Linux). Apache access is denied by
`storage/.htaccess`, `storage/leads/.htaccess` and `storage/ratelimit/.htaccess`. On nginx add:

```nginx
location ^~ /storage/ { deny all; return 404; }
location ^~ /includes/ { deny all; return 404; }
```

## 6. Security

- Nonce-based Content Security Policy (`default-src 'self'`, no inline scripts/styles, no third parties).
- `X-Content-Type-Options`, `X-Frame-Options: DENY`, `Referrer-Policy`, `Permissions-Policy`,
  `Cross-Origin-Opener-Policy`, HSTS when HTTPS, `X-Powered-By` removed.
- All output escaped with `e()`; no dynamic SQL; no file uploads; no user-supplied paths.
- Sessions: `HttpOnly`, `SameSite=Lax`, `Secure` under HTTPS; form CSRF validated with `hash_equals`.
- Rate-limit and lead files are outside the public routes and denied by the web server.
- Fonts are self-hosted, so no visitor data is sent to Google.

## 7. Performance and SEO

- One CSS file, one deferred JS file, no framework; system-font fallbacks and `font-display: swap`.
- All artwork is hand-built SVG (hero 60 KB, cards 4–12 KB), lazy-loaded below the fold; hero is
  preloaded with `fetchpriority="high"`.
- `?v=filemtime` cache-busting + one-year immutable caching via `.htaccess`.
- Unique title/description/canonical/Open Graph per page, `sitemap.xml`, `robots.txt`, JSON-LD
  (`Organization`, `WebSite`, `Service`, `ContactPage`, `GovernmentService`), semantic headings and
  direct-answer FAQ content for generative search.
- Mobile: fixed quick-contact bar, 48px tap targets, drawer navigation with focus trap, horizontal
  scroll tables and domain chips, no horizontal page overflow. `prefers-reduced-motion` respected.

## 8. Pre-launch content verification (from the blueprint)

These are the blueprint's `[V]` items. The site avoids unevidenced numbers (no 99.9% uptime, no
"certified" claims, no "75+ platforms"), but the company must still confirm the rest:

| Ref | Item | Current site behaviour |
|---|---|---|
| V-1 | Partner or distributor claims | Removed. The site makes no representation, distribution or partnership claim for any third-party company or product. Add a partner page only when a signed agreement exists and the other party approves the wording and marks. |
| V-2 | Third-party site resemblance | The previous layout mirrored a supplied reference site. Pages are now organised by subject and written independently; no third-party copy, marks or product names are used. |
| V-3 | 99.9% uptime | Not published. Replaced with a "<2 h Tier 1 response target" clearly labelled a service objective. |
| V-4 | ISO 9001 / AS9100 | Written as "aligned"; certificate numbers are supplied on request and no certification is claimed. |
| V-5 | Test beds, factory-trained staff, field units | Described as capability, with evidence expected during due diligence. Replace with real counts/facilities before launch if available. |
| V-6 | Product usage figures | Omitted. No platform, customer or volume figures are published; add only with documented evidence. |
| V-7 | Epitex vs EPiTEK spelling | Site uses **EPiTEK Solutions** (from the logo); domain/email retain the blueprint spelling. Confirm and align. |

Also replace before launch:

- `SITE_PHONE` placeholder and the LinkedIn URL in `includes/config.php`.
- Nairobi address / named contacts (currently "provided on request", as the blueprint itself marks them TBC).
- Any partner logos: only add logos you have permission to use. The current trust strip is text-only.
- Legal entity registration details if the footer needs them.

## 9. Design notes

- Palette: EPiTEK logo blue `#294776` with deep surfaces `#14233D` / `#1B3055`, accent `#207EB0`
  and `#3F9ED2` for dark-surface highlights, neutral backgrounds `#F7F8F8` and `#EEF1F2`.
- Type: Bricolage Grotesque (display), Source Sans 3 (body), JetBrains Mono (labels), self-hosted in
  `assets/fonts`.
- Artwork is built by `tools/build_figures.mjs` with the vendored Anatomy isometric kit (MIT):
  `node tools/build_figures.mjs [figure ...]` rewrites `assets/img/*.svg`. The palette lives in the
  `THEME` constant; seven figures share one plate and hardware vocabulary. Idle pulses respect
  `prefers-reduced-motion` inside each SVG, and `tools/` is denied to the web by its `.htaccess`.
- The domain bar is persistent on every page; on pages with capability cards it filters in place,
  and it gets the selected state from the user's click. It intentionally resets to "All" on
  navigation so no content appears missing.

## 10. What is not included

- No CMS and no downloadable data sheets (release is eligibility-screened, as stated on the site).
  The authenticated area is the staff console described in §11.
- No analytics or advertising tags. The two browser preferences used by the site (cookie-notice
  dismissal, nothing else) are documented on `/cookies`.
- Mail transport is not configured out of the box (see §5). The console's newsletter and
  outbound-mail module is not built yet; it lands once SMTP credentials exist.

## 11. Admin console (dashboard)

A staff-only dashboard at `/epiadmin/`. Same PHP application, no framework and no extra service: it
reads the MySQL database below, or a SQLite file for local work. The path is deliberately not linked
from the public site and is disallowed in `robots.txt`; `/admin` does not exist.

### What it contains

| Module | What it does |
| --- | --- |
| Overview | Counts for 30/7/1 days, open work, pipeline by stage, latest enquiries and applications, recent activity, and a traffic summary read from the Apache access log. |
| Enquiries | Every contact-form submission: filter by status, country or text, open a record, change stage, assign an owner, add internal notes, export CSV. |
| Applications | Two intake types in one pipeline — training enrolment and organisation/RFP requests — with type-specific stages, notes, ownership and CSV export. |
| Activity | Audit log of sign-ins, stage changes, notes, password changes and exports, with the user and timestamp. |
| Security | Change your own password, see the account details and the controls protecting the console. |

Roles: `owner` (everything), `editor` (no user management), `viewer` (read-only).

### Local setup

```bash
php tools/migrate.php                                  # create/upgrade tables in storage/app.sqlite
php tools/create_admin.php admin@your-domain "Console Admin" owner --username=admin
php tools/import_leads.php                             # optional: pull existing JSONL enquiries in
```

The seeded console account for this project is username `admin`, password `epiadmin123`. It is a
bootstrap credential: sign in once and set your own password on the Security page — the console
shows a warning until you do. To reset or add another account:

```bash
php tools/create_admin.php you@your-domain "Your Name" owner --username=you --password='…'
```

Then open `http://127.0.0.1:8123/epiadmin/`. With no credentials file the console uses
`storage/app.sqlite`, which is git-ignored. Delete that file to start empty.

### Server setup (cPanel)

1. Create a MySQL database and user in cPanel, and grant the user all privileges on it.
2. Copy `config/db.example.php` to `config/db.local.php` and fill in the four values. The file is
   git-ignored and `config/` is denied to the web by `.htaccess`.
3. Run `php tools/migrate.php`, then `php tools/create_admin.php …` (cPanel → Terminal, or SSH).
4. Optional traffic panel: add `config/analytics.local.php` returning
   `['access_log' => '/home/USER/logs/DOMAIN.access.log']`.
5. Sign in at `https://your-domain/epiadmin/` with the username you created, then change the
   password on the Security page.

### Public pages that feed it

- `/apply` — training application and organisation briefing request, both landing in the
  Applications module. Linked from the services hero, the training section and the footer.
- `/contact` — unchanged behaviour (JSONL + optional mail) and now also mirrored into Enquiries.

### Security notes

- Passwords are hashed with `password_hash` (bcrypt); sign-in accepts the username or the account
  email. Sessions use HttpOnly, SameSite=Lax cookies, `use_strict_mode`, regenerate on sign-in and
  are destroyed on sign-out.
- Every write is POST + CSRF-checked and rate-limited; failed sign-ins are limited to six per fifteen
  minutes per connection (successful sign-ins are never throttled).
- Read-only roles cannot change stage, owner or notes — enforced server-side, not in the template.
- The console sends `noindex, nofollow`, `no-store`, `X-Frame-Options: DENY`, HSTS over HTTPS and a
  self-only Content Security Policy, and never exposes the database credentials or lead files.
- CSV exports neutralise spreadsheet formula injection (`=`, `+`, `-`, `@`) in every cell.

### Code layout

| File | Responsibility |
| --- | --- |
| `includes/repository.php` | Every read and write for enquiries, applications, notes, activity, exports and dashboard aggregates. Pages call these instead of building SQL. |
| `includes/http.php` | `redirect()`, `paginate()`, `require_post()`, small request helpers. |
| `includes/admin_auth.php` | Session, sign-in, roles, password changes, audit trail, flashes, console URL builder. |
| `epiadmin/_layout.php` | Chrome, icons, navigation with live counts, page header, pager, pills, empty states. |
| `epiadmin/*.php` | Thin controllers: authenticate, call the repository, render. |

## 12. EPI, the site assistant

EPI is a fixed-answer assistant in the bottom-right corner of every public page: the visitor picks a
question and EPI replies from `includes/bot.php`. There is no text field, no model call and no
network request, so every answer is reviewed content and cannot invent a claim. Answers link to the
matching page. Behaviour lives in `assets/js/bot.js`, styles in `assets/css/extras.css`.

To change an answer, edit the array in `includes/bot.php`; the markup and the chips update with it.

## 13. Legal and policy pages

| Page | Purpose |
| --- | --- |
| `/privacy` | What the forms collect, why, retention, access and deletion. |
| `/cookies` | Every cookie and local-storage entry, its purpose and lifetime, and how to remove it. |
| `/terms` | Terms of use: information only, acceptable use, IP, export control, liability, Kenyan law. |
| `/security` | Controls in place, what not to send, and how to report a vulnerability. |

A dismissible cookie notice appears once (dismissal stored in `localStorage`, never sent anywhere).
It links to `/cookies`; there is no consent gate because the only cookie is strictly necessary.
