# Quickstart: Role Self-Registration

## Prerequisites

- App running with migrations + `RbacSeeder` (roles `student` and `instructor` present)
- Guest access to `/register`
- Optional: seeded admin (`admin@example.com`) for regression checks on admin-created users

## Student path

1. Open `/register` as a guest.
2. Choose **طالب** (student).
3. Confirm heading/help text describe student account / course enrollment.
4. Submit unique name, email, password (+ confirmation); phone/university optional.
5. Expect redirect into the **student** dashboard (or home via `dashboard.redirect`).
6. Confirm user has role `student`, `creation_source = self_registered`, `status = active`.

## Instructor path

1. Open `/register` as a guest (incognito / logged out).
2. Choose **محاضر** (instructor).
3. Confirm heading/help text describe instructor / teaching access.
4. Submit unique credentials.
5. Expect immediate access to the **instructor** dashboard (no admin approval wait).
6. Confirm user has role `instructor` only (not student), `creation_source = self_registered`.

## Negative checks

1. Submit without account type → Arabic validation; no user created.
2. Reuse an existing email → duplicate email message; role not partially created.
3. Confirm UI does not list staff roles.

## Regression

1. Admin-created student and instructor (seeded or via admin UI) still sign in to the correct dashboards.
2. Existing login / password-reset flows unchanged.

## Automated

```bash
php artisan test --filter=RegistrationFlowTest
```

Expect coverage for: student register, instructor register, missing `account_type`, and role attachment assertions (see [contracts/public-registration.md](./contracts/public-registration.md)).
