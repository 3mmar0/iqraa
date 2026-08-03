# Implementation Plan: Lesson Video, Content & Exam

**Branch**: `003-lesson-video-content-exam` | **Date**: 2026-08-04 | **Spec**: [spec.md](./spec.md)

**Input**: Feature specification from `/specs/003-lesson-video-content-exam/spec.md`

## Summary

Reshape the lesson into a three-part learning unit: **main video**, **rich-text explanation**, and a **gated post-watch exam**. Extend `lessons` + `lesson_progress` (and optionally `media_assets`) so admin can author these parts clearly; redesign student `lessons/show` and admin lesson forms/course Lessons tab to match Reading Room / student dashboard visual language.

## Technical Context

**Language/Version**: PHP 8.2+; Laravel 12.x

**Primary Dependencies**: Blade; Tailwind CSS v4; Alpine.js; existing media streaming (`Student\MediaController`); existing quiz attempt flow (`Student\QuizController`); HTML sanitization library or Laravel-safe allowlist (e.g. `mews/purifier` or curated `strip_tags`/HTMLPurifier)

**Storage**: MySQL — extend `lessons` (`content_html`, `main_media_asset_id`), extend `lesson_progress` (`video_completed_at` or `watch_completed` bool); reuse `media_assets`, `quizzes`, `quiz_attempts`

**Testing**: PHPUnit feature tests for admin save fields, student gating, sanitization smoke; optional browser not required

**Target Platform**: Ubuntu + Nginx + PHP-FPM; Arabic RTL web

**Project Type**: Modular monolithic Laravel (Blade admin + student dashboards)

**Performance Goals**: Lesson page interactive within normal app latency; video served via existing private disk streaming

**Constraints**: Arabic RTL; no React/Vue/Livewire/Inertia; official palette / Tajawal; do not invent exam engine — reuse quizzes; enrollment required for media/exam

**Scale/Scope**: One student lesson surface + admin lesson authoring surfaces; course Lessons tab alignment

## Constitution Check

| Gate | Status | Notes |
|------|--------|-------|
| Spec Kit constitution | Placeholder | Follow `001` / `PRODUCT.md` / `DESIGN.md` norms |
| Arabic RTL | Pass | All new UI Arabic |
| Reuse quiz engine | Pass | Lesson exam = linked `Quiz` |
| Sanitize rich text | Pass | Required before student render |

## Project Structure

### Documentation (this feature)

```text
specs/003-lesson-video-content-exam/
├── plan.md
├── research.md
├── data-model.md
├── quickstart.md
├── contracts/
│   └── lesson-learning.md
└── tasks.md
```

### Source Code (repository root)

```text
app/Models/Lesson.php
app/Models/LessonProgress.php
app/Models/MediaAsset.php
app/Http/Controllers/Web/Admin/LessonController.php
app/Http/Controllers/Web/Admin/LessonMediaController.php
app/Http/Controllers/Web/Student/LessonController.php
app/Http/Requests/... (admin lesson store/update)
app/Services/LessonProgressService.php (extend watch completion)
database/migrations/*_add_lesson_content_and_watch_fields.php
resources/views/admin/lessons/_form.blade.php
resources/views/admin/lessons/show.blade.php
resources/views/admin/courses/tabs/lessons.blade.php
resources/views/student/lessons/show.blade.php
resources/js/ (optional Alpine lesson player progress reporter)
tests/Feature/Student/LessonLearningPathTest.php
tests/Feature/Admin/LessonContentAuthoringTest.php
```

## Complexity Tracking

No unjustified complexity. Watch-completion threshold is a product rule documented in research.md; keep implementation to player events + server endpoint.
