<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Payment;
use App\Models\AccessKey;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_courses' => Course::count(),
            'active_courses' => Course::where('is_active', true)->count(),
            'total_students' => User::role('alumno')->count(),
            'total_teachers' => User::role('profesor')->count(),
            'total_payments' => Payment::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'approved_payments' => Payment::where('status', 'approved')->count(),
            'total_keys' => AccessKey::count(),
            'used_keys' => AccessKey::where('is_used', true)->count(),
        ];

        $recentPayments = Payment::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        $recentCourses = Course::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPayments', 'recentCourses'));
    }
}
