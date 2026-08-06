@props(['course', 'tab'])

@php($panel = $coursePanel ?? 'admin')

<input type="hidden" name="return_to" value="course">
<input type="hidden" name="return_course_id" value="{{ $course->id }}">
<input type="hidden" name="return_tab" value="{{ $tab }}">
<input type="hidden" name="return_panel" value="{{ $panel }}">
