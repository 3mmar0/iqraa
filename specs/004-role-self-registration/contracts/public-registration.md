# Contract: Public Registration (Student & Instructor)

**Feature**: `004-role-self-registration` | **Date**: 2026-08-07  
**Surface**: Guest Arabic RTL Blade UI (`layouts.guest`)  
**Base**: Existing auth routes — paths unchanged

## HTTP

| Method | Path | Auth | Description |
|--------|------|------|-------------|
| GET | `/register` | guest | Registration form with account-type chooser |
| POST | `/register` | guest | Create self-registered student or instructor |

Named route: `register` (GET). POST uses same URI.

### Success

- Create user + attach exactly one allowed role + create user settings
- Authenticate session
- Redirect to `dashboard.redirect` (→ `student.home` or `instructor.home` for single-role users)

### Failure

- Redirect back to `/register` with validation errors (Arabic) and `old()` input including `account_type`

---

## Request payload (POST)

| Field | Rules | Notes |
|-------|-------|-------|
| `account_type` | required; `student` \| `instructor` | Determines role; not a free-text role slug |
| `name` | required; string; max 255 | |
| `email` | required; email; max 255; unique `users.email` | |
| `phone` | nullable; string; max 30; unique `users.phone` when set | |
| `university` | nullable; string; max 255 | Same for both types |
| `password` | required; confirmed; Password::defaults() | |
| `password_confirmation` | required with password | |

### Arabic validation (minimum)

| Case | Message intent |
|------|----------------|
| Missing/invalid `account_type` | Ask to choose student or instructor |
| Duplicate email | البريد الإلكتروني مستخدم مسبقاً |
| Duplicate phone | رقم الهاتف مستخدم مسبقاً |
| Password mismatch | تأكيد كلمة المرور غير متطابق |
| Required fields | هذا الحقل مطلوب |

---

## UI contract (GET `/register`)

Must provide:

1. **Account type control** — radio group or equivalent choosing student vs instructor (Arabic labels: طالب / محاضر or equivalent clear wording)
2. **Adaptive primary copy** — heading + short help text that match the selected type:
   - Student: create student account; then request course enrollment
   - Instructor: create instructor account; teaching dashboard access
3. **Fields**: name, email, phone, university, password, password confirmation
4. **Submit** control and link to login
5. On validation failure, preserve selected `account_type` and show field errors

Must not:

- Offer staff/admin roles
- Require a second URL for instructors
- Send the user to a pending-approval dead-end after instructor register

### Suggested copy (Arabic)

| Type | Heading | Help |
|------|---------|------|
| student | إنشاء حساب طالب | أنشئ حسابك ثم اطلب الالتحاق بالمقررات المناسبة لك. |
| instructor | إنشاء حساب محاضر | أنشئ حساب المحاضر للوصول إلى لوحة التدريس وإدارة مقرراتك. |

---

## Post-login landing

| Roles after register | Expected |
|----------------------|----------|
| student only | Redirect to student home |
| instructor only | Redirect to instructor home |
| none (misconfiguration) | Existing no-access view |

Admin-created accounts and admin teacher CRUD are out of scope for this contract but must keep working unchanged.
