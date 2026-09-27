# URL Shortener — Sembark Backend Assignment

A Laravel 10 application for creating and tracking short URLs across client companies. This repository is the public submission for Sembark's backend developer assignment. It includes session login, invitation-based onboarding, role-restricted workspaces, company-scoped link lists, public redirects, hit tracking, and CSV export.

**Live demo:** [urlshortner.joblira.in/login](https://urlshortner.joblira.in/login) (login page checked on 27 September 2026). Demo access details are shared privately with the reviewer; no account credentials belong in this repository.

## What is implemented

| Role | Access and workflow |
| --- | --- |
| SuperAdmin | Seeded locally using credentials supplied in the local environment. Invites a company's first Admin, which creates the client on acceptance; views clients and links across clients, optionally filtered by client; exports CSV. Cannot create a short URL. |
| Admin | Accepts an invitation, views the team in their own client, invites additional Admins or Members, creates links, views and exports all links belonging to their client. |
| Member | Accepts an invitation, creates links, and views or exports only links they created. |
| Visitor | Can follow a valid `/s/{code}` link without logging in. The application returns an HTTP 302 redirect to the original URL and records a hit. |

The assignment brief mentions Sales and Manager roles; this code currently implements login routes and middleware for **SuperAdmin, Admin, and Member** only. There is no public registration form: accounts are created by the SuperAdmin seeder or through an invitation. An invitation expires after seven days and can be accepted once.

## Architecture

- **Laravel 10 / PHP 8.1+**, Blade views, Laravel session authentication, Eloquent, and a MySQL or SQLite database. The pages use assets already in `public/assets`; an npm build is not required to run these screens.
- `routes/web.php` defines guest login/invitation routes, authenticated role groups, and the public redirect route. `app/Http/Kernel.php` registers the `superadmin`, `admin`, and `member` middleware aliases.
- `app/Http/Controllers/SuperAdmin`, `Admin`, and `Member` hold workspace and invitation actions. `ShortUrlController` handles link creation, scoped listings, period filters, CSV downloads, and redirects.
- `clients` owns many users and short URLs. `users.client_id` assigns Admins and Members to a client; the seeded SuperAdmin has no client. `short_urls` stores a unique code, original URL, owner, client, and total hit count. `url_hits` stores timestamps for individual visits.
- The Admin's link query is restricted by `client_id`; the Member's by both `client_id` and `user_id`. The SuperAdmin can view all clients' links. Listing and CSV routes use the same scope. The default date filter is this month; `period=all` shows all time.
- Invitation URLs contain a random token; the database stores its SHA-256 hash. Acceptance creates the user in a transaction, checks invitation age and inviter role/client, and lets the invitee choose a password. Login is throttled to five attempts per minute; invitation submissions and link creation are also throttled.
- New links accept HTTP or HTTPS URLs (up to 2,048 characters), receive a unique 10-character code, and resolve through `/s/{code}`. A successful redirect increments `hits_count` and inserts a `url_hits` record.

This is a server-rendered web app. The stock Sanctum `/api/user` route is present, but the URL-shortener workflow is implemented through web routes, not an API.

## Local setup

Prerequisites: PHP 8.1 or newer with the extensions required by Laravel and your chosen PDO driver, Composer, and either SQLite or MySQL. Use a local mail driver for invitations while testing.

```bash
git clone https://github.com/urmtechnologies/urlshortner.git
cd urlshortner
composer install
cp .env.example .env
php artisan key:generate
```

Edit **your untracked `.env`**. For a quick SQLite setup, create an empty database file (`touch database/database.sqlite` on macOS/Linux) and set:

```dotenv
APP_NAME="URL Shortener"
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/urlshortner/database/database.sqlite
MAIL_MAILER=log
MAIL_FROM_ADDRESS=local@example.test
SUPERADMIN_EMAIL=your-local-admin@example.test
SUPERADMIN_PASSWORD=choose-a-unique-local-password
```

For MySQL instead, create an empty database and use the `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` settings in `.env.example`. Configure an SMTP mailer if you want invitations delivered rather than logged. Set `APP_URL` to the URL you actually serve so invitation and short URLs point to the right host. Keep `.env` private; it is gitignored.

```bash
php artisan migrate
php artisan db:seed --class=SuperAdminSeeder
php artisan serve
```

Open <http://127.0.0.1:8000/login> and use the SuperAdmin account you configured locally. The seeder uses a parameterized raw SQL insert and hashes the password. It is **not** run by the default `DatabaseSeeder`; invoke it explicitly. It skips an account if that email already exists. Set the two `SUPERADMIN_*` values before seeding and never commit them or share a real password in an issue or README.

The migrations create Laravel's users table, clients, invitations, short URLs, and URL hits. Some earlier migrations overlap in table names; the later client and invitation migrations guard against creating a table twice. Run `migrate` on a fresh database for review rather than `migrate:fresh` against a database containing data, since the latter deletes tables and records.

## Walk through the short-link flow

1. Sign in as the locally seeded SuperAdmin. Open **Invite Client**, enter a client name and a distinct Admin email, and send the invitation. With `MAIL_MAILER=log`, find the invitation URL in your **local** `storage/logs/laravel.log`; do not publish it. Open the link in a private window, set the Admin's name and password, and accept.
2. As Admin, open **Invite Team** and invite a Member (or another Admin) in the same client. Accept that link in a separate private window. Each invitation is single use and expires after seven days.
3. As Admin or Member, open **Generate**, submit an HTTP(S) destination such as `https://example.com`, and copy the resulting URL. SuperAdmin has no Generate route; an unauthorized role gets HTTP 403 on role-protected pages.
4. Open the generated `http://127.0.0.1:8000/s/YOUR_CODE` while signed out. It should return a **302 redirect** to the destination. Visiting an unknown code returns 404. You can inspect the redirect without following it using `curl -I` with your actual generated URL; that request also counts as a hit.
5. Return to **Short URLs**, select **All Time** if the creation was outside the current month, and check that the hit count increased. Confirm that an Admin sees all links for that client, a Member sees only their own, and SuperAdmin can see links across clients or filter by client. **Download CSV** follows the same role and period scope.

`php artisan test` runs the repository's basic home-to-login redirect and unit sanity tests. They do **not** assert the role, invitation, or short-link redirect scenarios above; use the manual walkthrough to review those behaviors.

## Security and disclosure

The public redirect is deliberately accessible without authentication. Treat destination URLs as untrusted external sites. Never place `.env`, invitation links, demo credentials, tokens, or database exports in the public repository. Supply demo credentials to reviewers through a private channel and rotate them if exposed.

**AI usage:** ChatGPT assisted with inspecting the existing source, drafting this documentation, and identifying the duplicate table creation in migrations. The role and link behavior described here was checked against the repository code; the documented walkthrough has not been represented as an automated test suite.
