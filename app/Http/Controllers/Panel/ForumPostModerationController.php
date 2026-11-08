<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ForumPostModerationController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->user()?->role === 'admin',
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );
    }

    // Menampilkan seluruh postingan
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['published', 'hidden'])],
        ]);

        $posts = ForumPost::with(['user', 'category'])
            ->withCount(['comments', 'likes'])
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($validated['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('panel.forum.posts.index', compact('posts'));
    }

    // Mengubah status postingan
    public function updateStatus(Request $request, ForumPost $post)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => ['required', Rule::in(['published', 'hidden'])],
        ]);

        $post->update([
            'status' => $validated['status'],
        ]);

        return back()->with(
            'success',
            'Status postingan berhasil diperbarui.'
        );
    }

    // Menghapus postingan
    public function destroy(ForumPost $post)
    {
        $this->authorizeAdmin();

        $post->delete();

        return back()->with(
            'success',
            'Postingan berhasil dihapus.'
        );
    }

    // Menampilkan postingan yang sudah dihapus
    public function trash()
    {
        $this->authorizeAdmin();

        $posts = ForumPost::onlyTrashed()
            ->with(['user', 'category'])
            ->withCount(['comments', 'likes'])
            ->latest('deleted_at')
            ->paginate(15);

        return view('panel.forum.posts.trash', compact('posts'));
    }

    // Memulihkan postingan
    public function restore(ForumPost $post)
    {
        $this->authorizeAdmin();

        abort_unless(
            $post->trashed(),
            404,
            'Postingan tidak ditemukan di tempat sampah.'
        );

        $post->restore();

        return redirect()
            ->route('panel.forum.posts.trash')
            ->with('success', 'Postingan berhasil dipulihkan.');
    }

    // Menghapus postingan secara permanen
    public function forceDestroy(ForumPost $post)
    {
        $this->authorizeAdmin();

        abort_unless(
            $post->trashed(),
            404,
            'Postingan tidak ditemukan di tempat sampah.'
        );

        $post->forceDelete();

        return redirect()
            ->route('panel.forum.posts.trash')
            ->with('success', 'Postingan berhasil dihapus secara permanen.');
    }
}