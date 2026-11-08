<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForumCommentModerationController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->user()?->role === 'admin',
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );
    }

    // Menampilkan seluruh komentar
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $comments = ForumComment::with(['user', 'post'])
            ->withCount('replies')
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('content', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('post', function ($postQuery) use ($search) {
                            $postQuery->where('title', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('panel.forum.comments.index', compact('comments'));
    }

    // Menampilkan komentar yang sudah dihapus
    public function trash()
    {
        $this->authorizeAdmin();

        $comments = ForumComment::onlyTrashed()
            ->with(['user', 'post'])
            ->withCount('replies')
            ->latest('deleted_at')
            ->paginate(15);

        return view('panel.forum.comments.trash', compact('comments'));
    }

    // Menghapus komentar
    public function destroy(ForumComment $comment)
    {
        $this->authorizeAdmin();

        DB::transaction(function () use ($comment) {

            // Hapus komentar utama terlebih dahulu
            $comment->delete();

            // Ambil waktu penghapusan komentar utama
            $deletedAt = $comment->deleted_at;

            // Soft delete semua balasan yang masih aktif
            ForumComment::where('parent_id', $comment->id)
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => $deletedAt,
                    'updated_at' => now(),
                ]);
        });

        return back()->with(
            'success',
            'Komentar dan balasannya berhasil dipindahkan ke tempat sampah.'
        );
    }

    // Memulihkan komentar
    public function restore(ForumComment $comment)
    {
        $this->authorizeAdmin();

        abort_unless(
            $comment->trashed(),
            404,
            'Komentar tidak ditemukan di tempat sampah.'
        );

        DB::transaction(function () use ($comment) {

            // Simpan waktu penghapusan komentar utama
            $deletedAt = $comment->deleted_at;

            // Pulihkan balasan yang ikut terhapus bersama komentar utama
            ForumComment::withTrashed()
                ->where('parent_id', $comment->id)
                ->where('deleted_at', $deletedAt)
                ->update([
                    'deleted_at' => null,
                    'updated_at' => now(),
                ]);

            // Pulihkan komentar utama
            $comment->restore();
        });

        return redirect()
            ->route('panel.forum.comments.trash')
            ->with('success', 'Komentar dan balasannya berhasil dipulihkan.');
    }

    // Menghapus komentar secara permanen
    public function forceDestroy(ForumComment $comment)
    {
        $this->authorizeAdmin();

        abort_unless(
            $comment->trashed(),
            404,
            'Komentar tidak ditemukan di tempat sampah.'
        );

        DB::transaction(function () use ($comment) {

            // Hapus permanen seluruh balasan komentar
            ForumComment::withTrashed()
                ->where('parent_id', $comment->id)
                ->forceDelete();

            // Hapus permanen komentar utama
            $comment->forceDelete();
        });

        return redirect()
            ->route('panel.forum.comments.trash')
            ->with('success', 'Komentar berhasil dihapus secara permanen.');
    }
}
