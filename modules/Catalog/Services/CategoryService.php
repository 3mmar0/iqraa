<?php

namespace Modules\Catalog\Services;

use App\Models\Category;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function create(array $data, ?User $actor = null): Category
    {
        $category = Category::query()->create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'active',
            'position' => $data['position'] ?? 0,
        ]);

        $this->audit->log($actor, 'category.created', Category::class, $category->id);

        return $category;
    }

    public function update(Category $category, array $data, ?User $actor = null): Category
    {
        $category->fill(collect($data)->only([
            'name', 'slug', 'description', 'status', 'position',
        ])->all());

        if (isset($data['name']) && ! isset($data['slug'])) {
            $category->slug = Str::slug($data['name']);
        }

        $category->save();
        $this->audit->log($actor, 'category.updated', Category::class, $category->id);

        return $category;
    }

    public function delete(Category $category, ?User $actor = null): void
    {
        $category->delete();
        $this->audit->log($actor, 'category.deleted', Category::class, $category->id);
    }

    public function merge(Category $source, Category $target, ?User $actor = null): Category
    {
        DB::transaction(function () use ($source, $target, $actor) {
            $source->courses()->update(['category_id' => $target->id]);
            $source->delete();

            $this->audit->log($actor, 'category.merged', Category::class, $target->id, [
                'source_id' => $source->id,
            ]);
        });

        return $target->fresh();
    }

    public function archive(Category $category, ?User $actor = null): Category
    {
        $category->update(['status' => 'archived']);
        $this->audit->log($actor, 'category.archived', Category::class, $category->id);

        return $category;
    }

    public function restore(int $categoryId, ?User $actor = null): Category
    {
        $category = Category::query()->onlyTrashed()->findOrFail($categoryId);
        $category->restore();
        $category->update(['status' => 'active']);

        $this->audit->log($actor, 'category.restored', Category::class, $category->id);

        return $category;
    }
}
