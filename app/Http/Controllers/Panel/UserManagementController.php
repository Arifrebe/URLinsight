<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->user()?->role === 'admin',
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $users = User::where('role', 'user')
            ->withCount([
                'forumPosts',
                'forumComments',
                'forumLikes',
            ])
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('panel.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $this->authorizeAdmin();

        // Admin tidak bisa membuka detail akun admin lain
        abort_unless($user->role === 'user', 404);

        $user->loadCount([
            'forumPosts',
            'forumComments',
            'forumLikes',
        ]);

        $posts = $user->forumPosts()
            ->with('category')
            ->latest()
            ->paginate(5, ['*'], 'posts_page');

        $comments = $user->forumComments()
            ->with('post')
            ->latest()
            ->paginate(5, ['*'], 'comments_page');

        return view('panel.users.show', compact(
            'user',
            'posts',
            'comments'
        ));
    }
        
    public function deactivate(User $user)
    {
        $this->authorizeAdmin();

        abort_unless($user->role === 'user', 404);

        $user->update([
            'is_active' => false,
        ]);

        return back()->with(
            'success',
            'Akun pengguna berhasil dinonaktifkan.'
        );
    }

    public function activate(User $user)
    {
        $this->authorizeAdmin();

        abort_unless($user->role === 'user', 404);

        $user->update([
            'is_active' => true,
        ]);

        return back()->with(
            'success',
            'Akun pengguna berhasil diaktifkan kembali.'
        );
    }

}