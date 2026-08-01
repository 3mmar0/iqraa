<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Catalog\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categories)
    {
    }

    public function index(Request $request): View
    {
        $query = Category::query()->withCount('courses')->orderBy('position')->orderBy('name');

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $categories = $query->paginate(20)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $category = $this->categories->create($validated, $request->user());

        return redirect()->route('admin.categories.index')->with('status', 'تم إنشاء التصنيف.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate($this->rules($category), [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $this->categories->update($category, $validated, $request->user());

        return redirect()->route('admin.categories.index')->with('status', 'تم تحديث التصنيف.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        $this->categories->delete($category, $request->user());

        return redirect()->route('admin.categories.index')->with('status', 'تم حذف التصنيف.');
    }

    public function merge(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'target_id' => ['required', 'integer', 'exists:categories,id', Rule::notIn([$category->id])],
        ], [
            'required' => 'هذا الحقل مطلوب.',
        ]);

        $target = Category::query()->findOrFail($validated['target_id']);
        $this->categories->merge($category, $target, $request->user());

        return redirect()->route('admin.categories.index')->with('status', 'تم دمج التصنيفات.');
    }

    public function archive(Request $request, Category $category): RedirectResponse
    {
        $this->categories->archive($category, $request->user());

        return back()->with('status', 'تم أرشفة التصنيف.');
    }

    public function restore(Request $request, int $category): RedirectResponse
    {
        $this->categories->restore($category, $request->user());

        return redirect()->route('admin.categories.index')->with('status', 'تم استعادة التصنيف.');
    }

    /** @return array<string, list<mixed>> */
    private function rules(?Category $category = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255',
                Rule::unique('categories', 'slug')->ignore($category?->id),
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['active', 'archived'])],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
