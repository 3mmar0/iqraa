<?php

namespace App\Http\Controllers\Web\Admin\Concerns;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait ReturnsToCourse
{
    protected function redirectToCourseContext(Request $request, string $status, string $defaultTab = 'lessons'): ?RedirectResponse
    {
        if ($request->input('return_to') !== 'course' || ! $request->filled('return_course_id')) {
            return null;
        }

        return redirect()
            ->route('admin.courses.show', [
                'course' => $request->integer('return_course_id'),
                'tab' => $request->input('return_tab', $defaultTab),
            ])
            ->with('status', $status);
    }
}
