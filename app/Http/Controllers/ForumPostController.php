<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumPostController extends Controller
{
    /**
     * Menampilkan daftar diskusi.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'exists:forum_categories,id'],
        ]);

        $query = ForumPost::with(['user', 'category'])
            ->withCount(['comments', 'likes'])
            ->where(function ($query) {
                $query->where('status', 'published');

                if (auth()->check()) {
                    $query->orWhere('user_id', auth()->id());
                }
            });

        // Pencarian berdasarkan judul atau isi
        if (!empty($validated['search'])) {
            $search = $validated['search'];

            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if (!empty($validated['category'])) {
            $query->where('category_id', $validated['category']);
        }

        $posts = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = ForumCategory::orderBy('name')->get();

        return view('front.forum.posts.index', compact(
            'posts',
            'categories'
        ));
    }

    /**
     * Menampilkan form pembuatan postingan.
     */
    public function create()
    {
        $categories = ForumCategory::all();

        return view('front.forum.posts.create', compact('categories'));
    }

    /**
     * Menyimpan postingan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:forum_categories,id'],
            'content' => ['required', 'string', 'min:10'],
        ]);

        $slug = Str::slug($validated['title']);

        // Memastikan slug unik.
        $originalSlug = $slug;
        $counter = 1;

        while (ForumPost::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        ForumPost::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'status' => 'published',
        ]);

        return redirect()
            ->route('forum.posts.index')
            ->with('success', 'Diskusi berhasil dibuat.');
    }

    /**
     * Menampilkan detail postingan.
     */
    public function show(ForumPost $post)
    {
        abort_if(
            $post->status !== 'published'
            && $post->user_id !== auth()->id()
            && auth()->user()?->role !== 'admin',
            404
        );

        $post->load([
            'user',
            'category',
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                    ->with(['user', 'replies.user']);
            },
        ]);

        $post->loadCount(['comments', 'likes']);

        return view('front.forum.posts.show', compact('post'));
    }

    /**
     * Menampilkan form edit.
     */
    public function edit(ForumPost $post)
    {
        $this->authorizePost($post);

        $categories = ForumCategory::all();

        return view('front.forum.posts.edit', compact('post', 'categories'));
    }

    /**
     * Memperbarui postingan.
     */
    public function update(Request $request, ForumPost $post)
    {
        $this->authorizePost($post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:forum_categories,id'],
            'content' => ['required', 'string', 'min:10'],
        ]);

        $slug = $post->slug;

        if ($post->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;

            while (
                ForumPost::where('slug', $slug)
                    ->where('id', '!=', $post->id)
                    ->exists()
            ) {
                $slug = $originalSlug . '-' . $counter++;
            }
        }

        $post->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'category_id' => $validated['category_id'],
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('forum.posts.show', $post->slug)
            ->with('success', 'Diskusi berhasil diperbarui.');
    }

    /**
     * Menghapus postingan.
     */
    public function destroy(ForumPost $post)
    {
        $this->authorizePost($post);

        $post->delete();

        return redirect()
            ->route('forum.posts.index')
            ->with('success', 'Diskusi berhasil dihapus.');
    }

    /**
     * Memeriksa hak akses pengguna.
     */
    private function authorizePost(ForumPost $post): void
    {
        abort_unless(
            $post->user_id === auth()->id()
            || auth()->user()->role === 'admin',
            403
        );
    }
}
