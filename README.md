# Yeti Auctions

An online auction platform built with Laravel: users list lots, bid against each other, and the
system closes expired auctions on its own and notifies everyone involved by email.

**Live demo: [yeti.katdou.ru](https://yeti.katdou.ru/)**

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat-square&logo=docker&logoColor=white)

---

## Overview

The interesting part of an auction is not the CRUD, it is everything that has to hold true at the
moment a bid is placed and at the moment the clock runs out. This project is built around those two
moments:

- a bid is only accepted if the lot is still active, the timer has not expired, the bidder is not
  the lot owner, and the amount beats both the current highest bid and the seller's minimum step;
- when the timer expires, a scheduled command settles the lot without anyone touching it - it picks
  the winner, moves the lot out of the active pool, and emails the winner and every losing bidder.

Everything else - catalog, search, profiles, uploads - exists to support that core loop.

## Features

**Auctions and bidding**
- Lot creation with image upload, starting price, minimum bid step, and an expiry timer
- Bid validation enforced server-side: active lot, unexpired timer, no self-bidding, and an amount
  above `max(current highest bid, minimum step)`
- Bid history per lot with human-readable relative timestamps ("12 minutes ago", "3 hours ago")
- Automatic settlement through the `auctions:complete` scheduled command
- Winner and loser email notifications, with per-recipient failures logged instead of aborting the
  whole run
- Lot lifecycle statuses: active, completed, expired without bids, cancelled

**Catalog and discovery**
- Category-based catalog with readable nested URLs (`/{category-slug}/{lot-slug}`)
- Automatic slug generation with collision handling, so two lots sharing a title still get unique URLs
- Search across lot titles and descriptions
- JSON endpoint powering live search suggestions as the user types
- Breadcrumb trail derived from the current route
- Recently viewed lots
- `sitemap.xml` generation via a console command

**Accounts**
- Registration and login with mandatory email verification (`MustVerifyEmail`)
- Profile editing with avatar upload and a default avatar fallback
- Role flag separating regular users from admins
- Ownership checks, so users can manage only their own lots and bids
- Personal dashboard: own lots, own bids, and won lots

**Housekeeping**
- `CleanUnusedAvatars` and `CleanUnusedLotImages` remove orphaned uploads left behind by deletions
- Model lifecycle hooks delete the associated image file when a lot or user is removed
- Seeders for categories, users, lots, bids, and static pages

## Tech Stack

| Layer | Choice |
|---|---|
| Backend | PHP 8.1+, Laravel 10 |
| Database | MySQL 8 - Eloquent, migrations, seeders |
| Templating | Blade |
| Styling | SCSS |
| Assets | webpack |
| Mail | Laravel Mailables over SMTP |
| Scheduling | Laravel task scheduler (`auctions:complete`, every minute) |
| Slugs | cocur/slugify |
| Sitemap | spatie/laravel-sitemap |
| Local env | Docker (php-fpm, Nginx, MySQL) |

## Architecture Notes

**Settlement runs on a schedule, not on a request.** Nothing about auction closing depends on a
user loading a page. `auctions:complete` runs every minute, finds lots whose timer has passed while
still marked active, and settles each one. A lot that received no bids is marked as expired rather
than being handed a winner.

**Validation sits at the controller boundary.** Bid rules are enforced in `LotController::placeBid`,
and lot creation and profile updates go through Form Request classes (`Lot\StoreRequest`,
`Lot\UpdateLotRequest`, `Auth\*`), which keeps rule definitions out of controller bodies and
reusable across entry points.

**Shared page data is centralised.** `DataController::getCommonData()` assembles what every page
needs - navigation, categories, user context - and `BreadcrumbsController` derives breadcrumbs from
the current route, so individual controllers stay focused on their own concern.

**Uploads are treated as owned resources.** Images live on the `public` disk, model `deleting` hooks
remove the file alongside the record, and the cleanup commands sweep up anything that slipped
through - useful when a database is restored from a dump that no longer matches the filesystem.

## Local Setup

```bash
git clone git@github.com:kvilcins/yeti-laravel.git
cd yeti-laravel
composer install
cp .env.example .env
php artisan key:generate
```

Point the `DB_*` variables in `.env` at your database, then:

```bash
php artisan migrate --seed
php artisan storage:link
npm install && npm run prod
php artisan serve
```

For auction settlement to run locally, either start the scheduler:

```bash
php artisan schedule:work
```

or trigger a single pass by hand:

```bash
php artisan auctions:complete
```

Outgoing mail needs the `MAIL_*` variables configured; any SMTP catcher such as Mailpit or Mailtrap
works for local testing.

## Project Structure

```
app/
├── Console/Commands/
│   ├── CompleteAuctions.php      # settlement: winner selection + notifications
│   ├── CleanUnusedAvatars.php    # orphaned upload cleanup
│   ├── CleanUnusedLotImages.php
│   └── GenerateSitemap.php
├── Http/
│   ├── Controllers/              # lots, bids, catalog, search, profile, auth
│   └── Requests/                 # Form Request validation (Auth/, Lot/)
├── Mail/
│   ├── AuctionWinnerMail.php
│   └── AuctionLoserMail.php
├── Models/                       # Item (lot), Bid, Category, User, Page
└── Services/
```

## Tests

```bash
php artisan test
```

Feature tests cover the two rules that matter most - what makes a bid legal, and what
happens when the clock runs out:

| Suite | Covers |
|---|---|
| `BidRulesTest` | Self-bidding, bids below the highest bid, bids below the minimum step, bids after the timer expired, bids on closed lots, guest access, amount validation |
| `AuctionSettlementTest` | Winner selection, winner and loser notifications, one email per losing bidder however many bids they placed, lots closing with no bids, lots whose timer is still running, already-settled lots |

Tests run against an in-memory SQLite database, so no setup beyond `composer install` is needed.

## Roadmap

- Move notification sending into queued jobs, so settlement never blocks on SMTP
- Live bid updates over WebSockets instead of a page refresh
- Full-text search index in place of `LIKE` matching
