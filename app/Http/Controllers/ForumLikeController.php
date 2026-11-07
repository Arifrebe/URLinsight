<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;

class ForumLikeController extends Controller
{
    public function toggle(ForumPost $post)
    {
        $userId = auth()->id();

        $like = $post->likes()
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            // Jika sudah like, hapus like
            $like->delete();

            $message = 'Like berhasil dibatalkan.';
        } else {
            // Jika belum like, tambahkan like
            $post->likes()->create([
                'user_id' => $userId,
            ]);

            $message = 'Postingan berhasil disukai.';
        }

        return back()->with('success', $message);
    }
}