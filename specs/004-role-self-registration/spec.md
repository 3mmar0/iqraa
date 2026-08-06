# Feature Specification: Role Self-Registration

**Feature Branch**: `004-role-self-registration`

**Created**: 2026-08-07

**Status**: Draft

**Input**: User description: "https://yatmaen.ammarelgndy.cloud/register — the ability to Register the students and instructors"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Visitor chooses account type and registers (Priority: P1)

A visitor opens the public registration page, chooses whether they are registering as a student or as an instructor, completes the required profile and password fields, and receives an account with the matching role so they can sign in and reach the correct dashboard.

**Why this priority**: Today the live register page only creates student accounts. Enabling a clear role choice is the core of this feature and unblocks instructor onboarding without requiring an admin for every new instructor account.

**Independent Test**: As a guest, open `/register`, select student, submit valid data, then confirm login lands on the student experience; repeat with instructor selection and confirm login lands on the instructor experience immediately (no admin approval wait).

**Acceptance Scenarios**:

1. **Given** a guest on the registration page, **When** they view the form, **Then** they can clearly choose student or instructor as the account type before submitting.
2. **Given** a guest selects student and submits valid required fields, **When** registration succeeds, **Then** the account is created with the student role, they are signed in (or guided to sign in after any required verification), and they can request course enrollment like existing students.
3. **Given** a guest selects instructor and submits valid required fields, **When** registration succeeds, **Then** the account is created with the instructor role, they are signed in (or guided to sign in after any required verification), they can open the instructor dashboard immediately, and copy on the page reflects that they are creating an instructor account (not a student account).
4. **Given** a guest omits the account type, **When** they submit, **Then** registration is rejected with a clear Arabic validation message asking them to choose student or instructor.

---

### User Story 2 - Role-appropriate fields and messaging (Priority: P2)

The registration form adapts labels, helper text, and optional fields so student vs instructor registration feels intentional (e.g. university remains relevant for students; instructor-facing copy explains teaching access).

**Why this priority**: Prevents confusion on a shared page and reduces support questions about “why am I a student when I wanted to teach.”

**Independent Test**: Toggle account type on `/register` and verify visible heading/help text (and any role-specific optional fields) match the selected role without leaving the page.

**Acceptance Scenarios**:

1. **Given** student is selected, **When** the form is shown, **Then** the primary heading/help text describe creating a student account and course enrollment path.
2. **Given** instructor is selected, **When** the form is shown, **Then** the primary heading/help text describe creating an instructor account and teaching access path.
3. **Given** either role, **When** validation fails (duplicate email/phone, password mismatch, missing required fields), **Then** Arabic error messages remain clear and the selected role is preserved after the failed submit.

---

### User Story 3 - Existing admin-created accounts stay valid (Priority: P3)

Admins can still create student and instructor accounts through existing admin surfaces. Self-registered and admin-created accounts of the same role behave equivalently for sign-in and role dashboards once active.

**Why this priority**: Preserves the current dual-path model for students and the admin teacher workflow; self-registration is an addition, not a replacement.

**Independent Test**: Create one student via public register and one via admin; create one instructor via public register (immediate access) and confirm an admin-created instructor still works; both accounts of each role can sign in to the matching dashboard.

**Acceptance Scenarios**:

1. **Given** an admin creates a student or instructor through existing admin flows, **When** that user signs in, **Then** behavior is unchanged from before this feature.
2. **Given** a self-registered user of a role and an admin-created user of the same role (both active), **When** each signs in, **Then** both reach the same role dashboard surface for that role.

---

### Edge Cases

- Guest tries to register with an email or phone already used — reject with existing uniqueness messages; do not create a second account.
- Guest attempts to register as both roles in one submission — not allowed; only one account type per registration.
- Newly self-registered instructors get immediate instructor-dashboard access (same status path as self-registered students); there is no pending-approval gate for this feature.
- Staff/admin roles (team, finance, support, super_admin, etc.) are never offered on public registration.
- After registration, a user who somehow holds multiple dashboards still uses the existing dashboard picker behavior (out of scope to redesign).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: The public registration page MUST allow a guest to choose exactly one account type: student or instructor.
- **FR-002**: Submitting registration as student MUST create a self-registered user with the student role and the same required fields and uniqueness rules as today’s student self-registration (name, email, password confirmation; phone and university as currently collected).
- **FR-003**: Submitting registration as instructor MUST create a self-registered user associated with the instructor role path (not the student role), using the same core identity fields (name, email, password confirmation; phone optional unless already required platform-wide).
- **FR-004**: A newly self-registered instructor MUST receive immediate instructor-dashboard access after successful registration (and any existing email-verification step if enabled), with no admin approval required before first instructor access.
- **FR-005**: Public registration MUST NOT offer or assign non-teaching staff roles (admin/team/finance/marketing/support/super_admin or custom staff roles).
- **FR-006**: After successful registration (and any existing email-verification step if enabled), the user MUST be able to sign in and land on the dashboard appropriate to their role (student → student surface; instructor → instructor surface; multi-role users keep existing picker behavior).
- **FR-007**: Registration page copy MUST be Arabic and MUST reflect the selected account type (student vs instructor), replacing the current student-only framing when instructor is selected.
- **FR-008**: Existing admin capabilities to create/manage students and teachers MUST remain available and MUST not be removed by this feature.
- **FR-009**: Creation source for public registrations MUST remain distinguishable as self-registered (vs admin-created) for both roles.

### Key Entities

- **User account**: Identity (name, email, phone, university when provided), credentials, status/activation, creation source (self-registered vs admin-created).
- **Account type (registration choice)**: Student or instructor; maps to the corresponding platform role immediately after successful registration.
- **Role**: Existing platform roles; this feature only assigns student or instructor via public registration.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: In UAT, a new visitor can complete student self-registration from the public register page in under 3 minutes and reach the student experience.
- **SC-002**: In UAT, a new visitor can complete instructor self-registration from the same page in under 3 minutes and reach the instructor experience immediately (no admin approval wait).
- **SC-003**: 100% of successful public registrations in UAT receive exactly one of {student, instructor} — never a staff/admin role.
- **SC-004**: At least 90% of first-time UAT participants correctly identify which account type they created based on on-page copy alone (no support intervention).
- **SC-005**: Admin-created student and instructor accounts continue to sign in successfully in the same UAT cycle (no regression).

## Assumptions

- Public registration remains email/password based; no new identity provider is required for this feature.
- One role per public registration; becoming both student and instructor later (if needed) stays an admin/multi-role concern, not part of this form.
- University field stays available at least for students; for instructors it may remain optional/shared without a separate bio/CV upload in v1.
- Email verification rules already defined for student self-registration apply consistently unless product later opts out for instructors.
- The live URL `/register` is the single public entry point (no separate `/register/instructor` required for v1).
- Staff onboarding continues to be admin-only.
- Clarified 2026-08-07: instructor self-registration grants immediate instructor access (option A); no pending-admin gate.
