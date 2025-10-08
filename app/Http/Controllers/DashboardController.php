<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permohonan;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function adminDashboard()
    {
        $totalUsers = User::count();
        $totalJenisSurat = JenisSurat::count();
        $totalPermohonan = Permohonan::count();
        $permohonanBaru = Permohonan::where('status', 'diajukan')->count();
        $recentPermohonan = Permohonan::with(['user', 'jenisSurat'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalJenisSurat', 
            'totalPermohonan',
            'permohonanBaru',
            'recentPermohonan'
        ));
    }

    /**
     * Operator Dashboard
     */
    public function operatorDashboard()
    {
        $totalPermohonan = Permohonan::count();
        $permohonanBaru = Permohonan::where('status', 'diajukan')->count();
        $permohonanDiproses = Permohonan::where('status', 'diverifikasi')->count();
        $permohonanSelesai = Permohonan::where('status', 'selesai')->count();
        $recentPermohonan = Permohonan::with(['user', 'jenisSurat'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('operator.dashboard', compact(
            'totalPermohonan',
            'permohonanBaru',
            'permohonanDiproses',
            'permohonanSelesai',
            'recentPermohonan'
        ));
    }

    /**
     * Warga Dashboard
     */
    public function wargaDashboard()
    {
        $user = Auth::user();
        $totalPermohonan = Permohonan::where('user_id', $user->id)->count();
        $permohonanPending = Permohonan::where('user_id', $user->id)
            ->where('status', 'diajukan')->count();
        $permohonanDiproses = Permohonan::where('user_id', $user->id)
            ->where('status', 'diverifikasi')->count();
        $permohonanSelesai = Permohonan::where('user_id', $user->id)
            ->where('status', 'selesai')->count();
        $recentPermohonan = Permohonan::where('user_id', $user->id)
            ->with('jenisSurat')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('warga.dashboard', compact(
            'totalPermohonan',
            'permohonanPending',
            'permohonanDiproses',
            'permohonanSelesai',
            'recentPermohonan'
        ));
    }
}