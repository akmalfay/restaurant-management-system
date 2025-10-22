<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek akses: hanya admin dan staff
        if (!in_array($user->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        $staffs = User::with('staffDetail')
            ->where('user_type', 'staff')
            ->orderBy('name')
            ->paginate(10);

        return view('staff.index', compact('staffs'));
    }

    public function show(User $user)
    {
        $authUser = Auth::user();

        // Cek akses: hanya admin dan staff
        if (!in_array($authUser->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        // Pastikan user yang dilihat adalah staff
        if ($user->user_type !== 'staff') {
            return redirect()->route('staff.index')
                ->with('error', 'User bukan staff');
        }

        $user->load('staffDetail');

        return view('staff.show', compact('user'));
    }
}
