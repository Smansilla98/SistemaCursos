<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_courses' => Course::count(),
            'active_courses' => Course::where('is_active', true)->count(),
            'total_students' => User::role('student')->count(),
            'total_purchases' => Purchase::count(),
            'pending_purchases' => Purchase::where('status', 'pending')->count(),
            'approved_purchases' => Purchase::where('status', 'approved')->count(),
            'rejected_purchases' => Purchase::where('status', 'rejected')->count(),
        ];

        $recentPurchases = Purchase::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        $recentCourses = Course::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPurchases', 'recentCourses'));
    }
}
