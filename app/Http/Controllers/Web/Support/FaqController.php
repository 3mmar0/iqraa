<?php

namespace App\Http\Controllers\Web\Support;

use App\Http\Controllers\Controller;
use App\Models\FaqArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $articles = FaqArticle::query()->orderBy('position')->get();

        return view('support.faq.index', compact('articles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'published' => ['nullable', 'boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
        ]);

        FaqArticle::query()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'published' => $request->boolean('published', true),
            'position' => $validated['position'] ?? ((int) FaqArticle::query()->max('position') + 1),
        ]);

        return back()->with('status', 'تمت إضافة المقال.');
    }
}