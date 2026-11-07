<?php

namespace App\Http\Controllers;

use App\Models\User;

class ForumProfileController extends Controller
{
    public function show(User $user)
    {
        // Ambil postingan yang sudah dipublikasikan
        $posts = $user->forumPosts()
            ->where('status', 'published')
            ->with('category')
            ->withCount(['comments', 'likes'])
            ->latest()
            ->paginate(5);

        // Ambil komentar terbaru pada postingan publik
        $comments = $user->forumComments()
            ->whereHas('post', function ($query) {
                $query->where('status', 'published');
            })
            ->with('post')
            ->latest()
            ->take(10)
            ->get();

        // Statistik profil
        $totalPosts = $user->forumPosts()
            ->where('status', 'published')
            ->count();

        $totalComments = $user->forumComments()
            ->whereHas('post', function ($query) {
                $query->where('status', 'published');
            })
            ->count();

        return view('front.forum.profile.show', compact(
            'user',
            'posts',
            'comments',
            'totalPosts',
            'totalComments'
        ));
    }
}