<?php

namespace App\Support;

use Illuminate\Http\Request;

class CoursePanel
{
    public static function fromRequest(?Request $request = null): string
    {
        $request ??= request();
        $name = (string) ($request->route()?->getName() ?? '');

        if (str_starts_with($name, 'instructor.')) {
            return 'instructor';
        }

        $returnPanel = (string) $request->input('return_panel', '');
        if (in_array($returnPanel, ['admin', 'instructor'], true)) {
            return $returnPanel;
        }

        return 'admin';
    }

    public static function layout(string $panel): string
    {
        return $panel === 'instructor' ? 'layouts.instructor' : 'layouts.admin';
    }
}
