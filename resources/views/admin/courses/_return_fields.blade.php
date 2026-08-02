@props(['course', 'tab'])

<input type="hidden" name="return_to" value="course">
<input type="hidden" name="return_course_id" value="{{ $course->id }}">
<input type="hidden" name="return_tab" value="{{ $tab }}">
