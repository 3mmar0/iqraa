<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('public.pages.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
        ], [
            'required' => 'هذا الحقل مطلوب.',
            'email' => 'أدخل بريداً إلكترونياً صالحاً.',
        ]);

        Lead::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'stage' => 'new',
            'source' => 'website_contact',
            'notes' => $validated['message'],
        ]);

        return redirect()
            ->route('public.contact')
            ->with('status', 'وصلنا رسالتك. سنعود إليك قريباً.');
    }
}
