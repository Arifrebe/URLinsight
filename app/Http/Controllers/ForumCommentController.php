<?php

namespace App\Http\Controllers;

use App\Models\ForumComment;
use App\Models\ForumPost;
use Illuminate\Http\Request;

class ForumCommentController extends Controller
{
    public function store(Request $request, ForumPost $post)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:2', 'max:2000'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        // Pastikan komentar yang dibalas berasal dari postingan ini
        // dan bukan merupakan balasan lain.
        $parentId = null;

        if (!empty($validated['parent_id'])) {
            $parent = $post->comments()
                ->whereNull('parent_id')
                ->findOrFail($validated['parent_id']);

            $parentId = $parent->id;
        }

        $post->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $parentId,
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('forum.posts.show', $post->slug)
            ->with('success', 'Komentar berhasil dikirim.');
    }

    public function destroy(ForumComment $comment)
    {
        abort_unless(
            (int) $comment->user_id === (int) auth()->id()
            || auth()->user()->role === 'admin',
            403
        );

        // Jika komentar utama dihapus, balasannya ikut dihapus.
        if ($comment->parent_id === null) {
            $comment->replies()->delete();
        }

        $postSlug = $comment->post->slug;

        $comment->delete();

        return redirect()
            ->route('forum.posts.show', $postSlug)
            ->with('success', 'Komentar berhasil dihapus.');
    }
}
