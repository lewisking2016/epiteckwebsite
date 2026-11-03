# EPiTEK Solutions — corporate website

A fast, mobile-first PHP site for **EPiTEK Solutions**, regional defence, aerospace and
mission-technology integrator for East Africa (Kenya, Uganda, Tanzania, Rwanda, Ethiopia).

Built from the EPiTEK brand (navy `#294776`, accent blue `#207EB0`) and the site architecture in
`docs/Epitex_Website_Blueprint.pdf`. The layout language is modelled on the reference site the
client supplied (`st-modelisation.fr`): sticky translucent header, dark technical hero, mono
eyebrows, display-grotesque headings, card bands, navy data-table band and a numbered "why" row.
No code, copy or assets from that site were copied.

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
| `/` | `index.php` | Positioning, domains, portfolio, standards, sustainment, footprint, FAQ |
| `/distributor-integration` | `distributor-integration/index.php` | Logistics, integration architecture, depot, quality |
| `/partnerships-stm` | `partnerships-stm/index.php` | Mandate, portfolio table, joint execution, territory |
| `/services` | `services/index.php` | Five services, SLA tiers, training pathway, advisory |
| `/contact` | `contact/index.php` | Enterprise enquiry form + compliance notice (also 404 page) |

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
| V-1 | "Authorised regional partner/distributor" for STM | Used as positioning, with an explicit statement that authorisation letters must be supplied and that the page is not a legal instrument. Publish only after the signed document exists. |
| V-2 | Reference link `st-modelisation.fr` | The reference is **STM® Société Toulousaine de Modélisation**, a French company, *not* the Turkish defence company STM. Only the design language was used; no content. Use `stm.com.tr` for STM facts. |
| V-3 | 99.9% uptime | Not published. Replaced with a "<2 h Tier 1 response target" clearly labelled a service objective. |
| V-4 | ISO 9001 / AS9100 | Written as "aligned"; certificate numbers are supplied on request and no certification is claimed. |
| V-5 | Test beds, factory-trained staff, field units | Described as capability, with evidence expected during due diligence. Replace with real counts/facilities before launch if available. |
| V-6 | FocusFlite "75+ platforms" | Omitted. Add only once the manufacturer confirms the figure and wording. |
| V-7 | Epitex vs EPiTEK spelling | Site uses **EPiTEK Solutions** (from the logo); domain/email retain the blueprint spelling. Confirm and align. |

Also replace before launch:

- `SITE_PHONE` placeholder and the LinkedIn URL in `includes/config.php`.
- Nairobi address / named contacts (currently "provided on request", as the blueprint itself marks them TBC).
- Any partner logos: only add logos you have permission to use. The current trust strip is text-only.
- Legal entity registration details if the footer needs them.

## 9. Design notes

- Palette: navy `#12294A` / `#0A1A2F`, brand `#294776`, accent `#207EB0` (text-grade `#1A6A96`),
  sky `#3AA0D5`, backgrounds `#F4F8FC` and `#E7F0F8`.
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

- No CMS, customer portal, authenticated area or downloadable data sheets (release is
  eligibility-screened, as stated on the site).
- No analytics or cookie banner; add analytics only with the client's consent policy.
- Mail transport is not configured out of the box (see §5).
