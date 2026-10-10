# Time Tracker: employee time logging for a small team

A Laravel 11 app where employees clock their own hours and admins review, correct, and manage everything in one place.

I built it for a client on Upwork, who rated the work five stars with the review **"Excellent Laravel Developer."** The code is public with the client's permission.

## What it does

**For employees**
- One-click start and stop timer (Livewire), so a session is logged without a page reload.
- The timer picks up an open session when you come back, so a running session is never lost.
- Total tracked time, with durations shown in plain language (for example "2 hours 15 minutes").

**For admins**
- **Users:** create, edit, and delete accounts with a unique username and email, a role, and a password (min. 8 characters, confirmed).
- **Employees:** search by username, and start or stop any employee's timer for them.
- **Time logs:** view, edit, and delete any employee's logs, including the session details the client uses for payroll: session type and length, client, payout, country, and payment method.
- **Filters:** narrow logs by date range, session type, client, country, and payment method.
- **Countries:** manage the country list (unique name and 3-letter code). A log's country must match an entry in this list.

## How it's built

- **Two roles in a PHP enum** (`admin`, `employee`). Every admin route sits behind an `access-admin` gate, so access is enforced on the server, not just hidden in the UI.
- **Validation on every write:** unique usernames, emails, and country codes, allowed values for session type and payment method, and an existence check for countries.
- **Livewire 3 components** for the employee timer and the admin's per-employee timer.
- **Laravel Breeze (Livewire + Volt)** for registration with a username, login, email verification, and profile management.

**Stack:** PHP 8.2, Laravel 11, Livewire 3, Volt, Blade, Tailwind CSS, Vite. SQLite by default; MySQL works by changing `.env`.

## Run it locally

```bash
git clone https://github.com/ali-hassan-dev/time-tracker.git
cd time-tracker
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Then:

1. Open `http://localhost:8000/register` and create an account.
2. Verify the email. With the default mail settings, the verification link is written to `storage/logs/laravel.log`.
3. Make that account an admin:

```bash
php artisan tinker
>>> App\Models\User::where('email', 'you@example.com')->update(['role' => 'admin']);
```

New accounts are employees by default.

## About me

I'm Ali Hassan, a full-stack engineer working with Laravel, React, Next.js, and Node.js. See more of my work on my [portfolio](https://ali-hassan-dev.netlify.app/upwork), or [work with me on Upwork](https://www.upwork.com/freelancers/~0110268fa5a62812e0).
