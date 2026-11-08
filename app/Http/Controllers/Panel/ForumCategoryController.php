<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ForumCategoryController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->user()?->role === 'admin',
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );
    }

    // Menampilkan seluruh kategori
    public function index()
    {
        $this->authorizeAdmin();

        $categories = ForumCategory::withCount('posts')
            ->latest()
            ->paginate(10);

        return view(
            'panel.forum.categories.index',
            compact('categories')
        );
    }

    // Menyimpan kategori baru
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:forum_categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        ForumCategory::create([
            'name' => $validated['name'],
            'slug' => $this->generateSlug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Memperbarui kategori
    public function update(Request $request, ForumCategory $category)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('forum_categories', 'name')
                    ->ignore($category->id),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $this->generateSlug(
                $validated['name'],
                $category->id
            ),
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori
    public function destroy(ForumCategory $category)
    {
        $this->authorizeAdmin();

        // Kategori yang masih digunakan tidak boleh dihapus
        if ($category->posts()->exists()) {
            return back()->with(
                'error',
                'Kategori tidak dapat dihapus karena masih memiliki postingan.'
            );
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    // Membuat slug unik
    private function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            ForumCategory::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
