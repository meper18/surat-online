<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditTrailController extends Controller
{
    /**
     * Display audit trail logs (Admin only)
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Only admin can view audit trails
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $query = AuditTrail::with('user')->orderBy('created_at', 'desc');

        // Filter by action if provided
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range if provided
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by model type if provided
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        $auditTrails = $query->paginate(20);

        // Get unique actions for filter dropdown
        $actions = AuditTrail::distinct()->pluck('action')->sort();
        
        // Get users for filter dropdown
        $users = User::select('id', 'name')->orderBy('name')->get();

        // Get unique model types for filter dropdown
        $modelTypes = AuditTrail::whereNotNull('model_type')
            ->distinct()
            ->pluck('model_type')
            ->map(function ($type) {
                return class_basename($type);
            })
            ->sort();

        return view('admin.audit-trail.index', compact(
            'auditTrails', 
            'actions', 
            'users', 
            'modelTypes'
        ));
    }

    /**
     * Show detailed audit trail entry
     */
    public function show(AuditTrail $auditTrail)
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Only admin can view audit trail details
        if (!$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.audit-trail.show', compact('auditTrail'));
    }
}
