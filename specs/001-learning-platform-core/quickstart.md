# Quickstart Validation: Learning Platform Core

**Branch**: `001-learning-platform-core` | **Date**: 2026-07-31  
**Purpose**: Prove the feature end-to-end after implementation. Not an implementation guide.

Related: [spec.md](./spec.md) · [data-model.md](./data-model.md) · [contracts/api.md](./contracts/api.md) · [plan.md](./plan.md)

---

## Prerequisites

- PHP 8.3+, Composer, Node/npm, MySQL 8, Redis
- App `.env` configured: `APP_LOCALE=ar`, DB, Redis queue/cache/session
- Migrations + seeders create personas below
- Queue worker running for mail/notifications/reports: `php artisan queue:work`
- Scheduler available for cron-related checks (optional for smoke)

### Seed personas (expected)

| Persona | Role(s) | Notes |
|---------|---------|--------|
| `student@example.com` | student | Self-registered style; verified |
| `student2@example.com` | student | Admin-created style; activated |
| `instructor@example.com` | instructor | Owns sample course |
| `support@example.com` | support **without** `enrollments.approve` initially |
| `approver@example.com` | support **with** `enrollments.approve` |
| `admin@example.com` | super_admin | Can assign permissions |
| `multi@example.com` | student + instructor | For picker test |
| Finance / marketing / team users | respective roles | Surface smoke |

Default password documented in seeder output (dev only).

---

## Setup (once app exists)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
php artisan queue:work
```

Open the public site; confirm RTL Arabic chrome.

---

## Scenario A — Shared login & RBAC

1. Login as `student@example.com` → lands on Student dashboard (no picker).
2. Open `/finance` or `/admin` → denied (Arabic message).
3. Login as `multi@example.com` → dashboard picker shows only Student + Instructor → choose Instructor → Instructor home.
4. Logout → protected URL redirects to shared `/login`.

**Pass**: SC-004, SC-016 behaviors observed.

---

## Scenario B — Dual account origins + request/approve

1. Public `/register` → create new student → verify email (or seeder helper) → login.
2. Browse published course → submit course access request → status `pending` → lesson media URL denied.
3. Login as `support@example.com` (no approve permission) → cannot approve (403/UI hidden).
4. Login as `admin@example.com` → grant `enrollments.approve` to Support role (or to `support` user via role).
5. Login as `approver@example.com` → approve pending request.
6. Login as requesting student → course in My Courses → open lesson → video plays via private media route.

**Pass**: SC-013, SC-014, SC-017.

Also: admin creates `student2` → student activates/logs in → can request a course.

---

## Scenario C — Student learning loop

1. Enrolled student: Home shows last lesson / progress.
2. My Courses → Course → Lesson → mark complete → progress updates.
3. Start quiz → submit → see score, answers, correct answers, analysis.
4. Settings: toggle dark mode; **no** language switcher.
5. Open Support → create ticket → visible in Support dashboard.

**Pass**: SC-001 (≤3 clicks to resume), SC-003, FR-001a.

---

## Scenario D — Instructor smoke

1. Login instructor → create/edit lesson on owned course → student sees published content after refresh.
2. Publish announcement → student notification inbox updates (queue processed).

---

## Scenario E — Staff surfaces present (v1 all-dashboards)

Login each of: team, finance, marketing, support, admin. Confirm each home route loads (not “coming soon”).

Finance: open Overview; enqueue one transactions export → notification/download when job completes (queue worker on).

**Pass**: SC-015, SC-010 sample.

---

## Scenario F — API smoke

```bash
# Obtain token (exact payload per contracts/api.md)
curl -X POST /api/v1/login -d '{"email":"student@example.com","password":"…"}'
curl /api/v1/me -H "Authorization: Bearer …"
```

**Pass**: Authorized `me` returns roles/permissions; unauthenticated → 401.

---

## Automated checks (when implemented)

```bash
php artisan test --testsuite=Feature
php artisan test --testsuite=Contract
vendor/bin/pint --test
```

Map Feature tests to Scenarios A–C at minimum before calling the slice done.

---

## Out of scope for this quickstart

- Payment gateway checkout
- S3/CDN streaming
- WhatsApp / FCM / 2FA
- Full Medium/Low page polish beyond High-priority matrix pages
