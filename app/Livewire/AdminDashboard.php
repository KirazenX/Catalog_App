<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Comment;
use App\Models\User;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        $totalProducts  = Product::count();
        $totalUsers     = User::where('is_admin', false)->count();
        $totalComments  = Comment::count();
        $totalAdmins    = User::where('is_admin', true)->count();

        $latestProducts = Product::latest()->take(5)->get();
        $latestComments = Comment::with(['user', 'product'])->latest()->take(5)->get();

        $productWithMostComments = Product::withCount('comments')
            ->orderByDesc('comments_count')
            ->take(5)
            ->get();

        return view('livewire.admin-dashboard', compact(
            'totalProducts',
            'totalUsers',
            'totalComments',
            'totalAdmins',
            'latestProducts',
            'latestComments',
            'productWithMostComments',
        ));
    }
}
