<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumLike;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PanelDashboardController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->user()?->role === 'admin',
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );
    }

    public function index()
    {
        $this->authorizeAdmin();

        // Statistik utama
        $totalUsers = User::where('role', 'user')->count();

        $totalPosts = ForumPost::count();

        $totalComments = ForumComment::count();

        $totalLikes = ForumLike::count();

        $hiddenPosts = ForumPost::where('status', 'hidden')->count();

        // Aktivitas postingan terbaru
        $recentPosts = ForumPost::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Aktivitas komentar terbaru
        $recentComments = ForumComment::with(['user', 'post'])
            ->latest()
            ->take(5)
            ->get();

        // Rentang 7 hari terakhir
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Daftar tanggal untuk sumbu X
        $dates = collect(range(6, 0))->map(function ($day) {
            return now()->subDays($day)->toDateString();
        });

        // Menghitung postingan per hari
        $postCounts = ForumPost::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // Menghitung komentar per hari
        $commentCounts = ForumComment::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // Menyiapkan label grafik
        $chartLabels = $dates->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        });

        // Menyiapkan data postingan
        $postChartData = $dates->map(function ($date) use ($postCounts) {
            return (int) $postCounts->get($date, 0);
        });

        // Menyiapkan data komentar
        $commentChartData = $dates->map(function ($date) use ($commentCounts) {
            return (int) $commentCounts->get($date, 0);
        });

        return view('panel.dashboard', compact(
            'totalUsers',
            'totalPosts',
            'totalComments',
            'totalLikes',
            'hiddenPosts',
            'recentPosts',
            'recentComments',
            'chartLabels',
            'postChartData',
            'commentChartData'
        ));
    }
}