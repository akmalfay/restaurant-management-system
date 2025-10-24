<?php

namespace App\Http\Controllers;

use App\Models\StaffDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        $search = $request->get('search');

        $staffs = User::where('user_type', 'staff')
            ->with('staffDetail')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('staff.index', compact('staffs'));
    }

    public function show(User $user)
    {
        $authUser = Auth::user();

        // Cek akses: hanya admin
        if (!in_array($authUser->user_type, ['admin'])) {
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

    public function edit(User $user)
    {
        // Hanya admin yang bisa edit
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Pastikan user yang diedit adalah staff
        if ($user->user_type !== 'staff') {
            return redirect()->route('staff.index')
                ->with('error', 'User bukan staff');
        }

        $user->load('staffDetail');

        return view('staff.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Hanya admin yang bisa update
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['chef', 'waiter', 'cashier'])],
            'is_active' => ['required', 'boolean'],
        ]);

        DB::beginTransaction();

        try {
            // Update staff detail
            $user->staffDetail->update([
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
            ]);

            // Jika status diubah jadi inactive, set left_at
            if (!$validated['is_active'] && !$user->staffDetail->left_at) {
                $user->staffDetail->update(['left_at' => now()]);
            }

            // Jika status diubah jadi active, hapus left_at
            if ($validated['is_active'] && $user->staffDetail->left_at) {
                $user->staffDetail->update(['left_at' => null]);
            }

            DB::commit();

            return redirect()->route('staff.show', $user)
                ->with('success', 'Data staff berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data staff: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        // Hanya admin yang bisa delete
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Pastikan user yang dihapus adalah staff
        if ($user->user_type !== 'staff') {
            return redirect()->route('staff.index')
                ->with('error', 'User bukan staff');
        }

        DB::beginTransaction();

        try {
            // Hapus staff detail dulu (karena ada foreign key)
            $user->staffDetail()->delete();

            // Hapus user
            $user->delete();

            DB::commit();

            return redirect()->route('staff.index')
                ->with('success', 'Staff berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus staff: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['chef', 'waiter', 'cashier'])],
            'is_active' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::uuid() . '.' . $file->extension();
                $imagePath = $file->storeAs('profile', $filename, 'public');
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'image' => $imagePath ?? 'profile/profile.jpg', // fallback default
                'user_type' => 'staff',
            ]);

            StaffDetail::create([
                'user_id' => $user->id,
                'role' => $validated['role'],
                'is_active' => (bool)$validated['is_active'],
                'joined_at' => now(),
                'left_at' => null,
            ]);

            DB::commit();
            return redirect()->route('staff.index')->with('success', 'Staff berhasil ditambahkan');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['server' => $e->getMessage()])->withInput();
        }
    }
}
