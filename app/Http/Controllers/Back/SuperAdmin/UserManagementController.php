<?php

namespace App\Http\Controllers\Back\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserManagementController extends Controller
{
    /**
     * List all admins with basic info (super admin view)
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->withTrashed()->latest()->paginate(20);

        return view('super_admin.user_management.index', compact('admins'));
    }

    /**
     * View full profile of an admin
     */
    public function show($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        return view('super_admin.user_management.show', compact('admin'));
    }

    /**
     * Download a single document file
     */
    public function downloadDocument($id, $field)
    {
        $allowed = [
            'nid_document', 'passport_document', 'tin_document',
            'driving_licence_document', 'occupation_document', 'car_details_document',
        ];

        abort_if(!in_array($field, $allowed), 403);

        $admin = User::where('role', 'admin')->findOrFail($id);
        $path  = $admin->$field;

        abort_if(!$path || !Storage::disk('public')->exists($path), 404, 'Document not found.');

        return Storage::disk('public')->download($path);
    }

    /**
     * Future: mark a document as verified
     */
    public function verifyDocument(Request $request, $id)
    {
        // TODO: add a `verified_documents` JSON column to users table
        // and track which docs have been verified by super admin
        return response()->json(['message' => 'Verification system coming soon.'], 501);
    }
}