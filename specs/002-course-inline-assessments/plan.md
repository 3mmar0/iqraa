# Implementation Plan: Course Inline Assessments

**Branch**: `002-course-inline-assessments` | **Date**: 2026-08-03 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/002-course-inline-assessments/spec.md`

**Note**: This template is filled in by the `/speckit-plan` command; its definition describes the execution workflow.

## Summary

Make the admin course detail page the self-contained workspace for course assessments: full quiz + question authoring on `?tab=quizzes`, and assignment management plus submission review/grading on `?tab=assignments`, without requiring navigation to standalone quiz/assignment pages. Approach: extend existing Blade + Alpine course-tab modals, add nested admin question CRUD and assignment submission grade/resubmit actions that redirect back via `ReturnsToCourse`, and enforce publish-requires-questions in `QuizAdminService`.

## Technical Context

**Language/Version**: PHP 8.2+; Laravel 12.x

**Primary Dependencies**: Blade; Tailwind CSS; Alpine.js; Laravel Form validation / Form Requests; existing `QuizAdminService`; `ReturnsToCourse` trait; `AuditLogger`

**Storage**: MySQL (existing `quizzes`, `questions`, `question_options`, `assignments`, `assignment_submissions`, `attempt_answers` tables — no new core tables expected)

**Testing**: PHPUnit / Pest feature tests for question CRUD, publish gate, assignment grade/resubmit, and course-tab redirect returns

**Target Platform**: Same as platform (Ubuntu + Nginx + PHP-FPM); admin Arabic RTL web UI

**Project Type**: Modular monolithic Laravel web app (server-rendered admin)

**Performance Goals**: Course Quizzes/Assignments tabs remain usable with dozens of quizzes/assignments and hundreds of questions/submissions per course (paginate or lazy-load submissions panel if lists grow large)

**Constraints**: Arabic RTL only; no React/Vue/Livewire/Inertia; stay on course show URL after mutations; reuse existing admin visual patterns (`x-admin.modal`, tab cards)

**Scale/Scope**: Two course tabs upgraded; new question admin routes + submission grade/resubmit; global quiz/assignment indexes retained as optional overview

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

`.specify/memory/constitution.md` remains the Spec Kit placeholder (principles not ratified). Follow project norms from `001-learning-platform-core` plan:

| Gate | Status | Notes |
|------|--------|-------|
| Constitution principles defined | PASS (advisory) | Proceed with modular monolith, Form Requests, Policies/Gates, service layer, Arabic UI |
| No stack split | PASS | Blade + Alpine only; no new frontend framework |
| Spec clarifications complete | PASS | Spec has no remaining `[NEEDS CLARIFICATION]` markers |
| Reuse existing course-tab patterns | PASS | Extends quizzes/assignments Alpine modals + `ReturnsToCourse` |
| Schema churn minimized | PASS | Entities already exist; behavior/UI is the work |

**Post–Phase 1 re-check**: Design keeps a single Laravel app, nested admin routes, and UI contracts under `contracts/`. No unjustified complexity. Complexity Tracking left empty.

## Project Structure

### Documentation (this feature)

```text
specs/002-course-inline-assessments/
├── plan.md              # This file (/speckit-plan command output)
├── research.md          # Phase 0 output
├── data-model.md        # Phase 1 output
├── quickstart.md        # Phase 1 output
├── contracts/           # Phase 1 output
│   └── admin-course-assessments.md
└── tasks.md             # Phase 2 (/speckit-tasks — NOT created by /speckit-plan)
```

### Source Code (repository root)

```text
app/
├── Http/
│   ├── Controllers/Web/Admin/
│   │   ├── QuizController.php              # publish gate; course return unchanged
│   │   ├── QuestionController.php          # NEW: question CRUD + reorder
│   │   ├── AssignmentController.php        # grade / resubmit + course-tab payloads
│   │   └── Concerns/ReturnsToCourse.php    # reuse
│   └── Requests/Admin/                     # Form Requests for questions / grade
├── Models/
│   ├── Quiz.php / Question.php / QuestionOption.php
│   ├── Assignment.php / AssignmentSubmission.php
│   └── Course.php
└── Services/AuditLogger.php

modules/Quizzes/Services/QuizAdminService.php   # publish ≥1 question; question helpers

resources/views/admin/
├── courses/tabs/quizzes.blade.php              # in-tab question manager
├── courses/tabs/assignments.blade.php          # in-tab detail + submissions
├── courses/_return_fields.blade.php
├── quizzes/show.blade.php                      # optional: link back / share partials
└── partials/                                   # shared question form / submission list if extracted

routes/web.php                                  # admin.quizzes.questions.* + assignment grade/resubmit

tests/Feature/Admin/
├── CourseQuizQuestionsTest.php
└── CourseAssignmentSubmissionsTest.php
```

**Structure Decision**: Extend the existing Laravel modular monolith admin surface. Primary UX changes live in course tab Blade views; new `QuestionController` nests under quizzes; grading lands on `AssignmentController`. No new deployable packages.

## Complexity Tracking

> No constitution violations requiring justification.
