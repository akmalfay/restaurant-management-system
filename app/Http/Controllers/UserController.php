<?php

namespace App\Http\Controllers;

use App\Models\CustomerDetail;
use App\Models\StaffDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  // Menampilkan list user
  public function index()
  {
    $users = User::all();

    return view("users.index", $users);
  }

  // Menampilkan form register
  public function create()
  {
    return view("users.create");
  }

  // Membuat user baru
  public function store(Request $request)
  {
    $validated = $request->validate(
      rules: [
        'name' => 'required|string',
        'email' => 'required|email|unique:customers,email',
        'password' => 'required|min:8|confirmed',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
      ],
      params: [
        'email.unique' => 'Email ini sudah terdaftar.',
        'phone.unique' => 'Nomor telepon ini sudah terdaftar.',
      ],
    );

    $imageUrl = 'profile/profile.jpg';

    if ($request->hasFile(key: 'image')) {
      $file = $request->file(key: 'image');
      $path = $file->storeAs(path: 'profile', name: Str::uuid(), options: 'public');
      $imageUrl = $path;
    }

    User::create(attributes: [
      'name' => $request->name,
      'phone' => $request->phone,
      'email' => $request->email,
      'password' => Hash::make(value: $validated['password']),
      'image' => $imageUrl,
    ]);

    return redirect()->route(route: 'users.index')->with(key: 'success', value: "User berhasil dibuat");
  }

  // Menampilkan user tertentu
  public function show(User $user)
  {
    return view('users.show', ['user' => $user]);
  }

  // Mengupdate data oleh admin
  public function updateRole(Request $request, User $user)
  {
    if ($request->user()->user_type !== 'admin') {
      return redirect()->route('users.index')->with(key: 'false', value: "Gagal mengedit user");
    }

    $validated = $request->validate([
      'user_type' => ['required', Rule::in(['admin', 'staff', 'customer'])],

      // 'staff_role' hanya wajib diisi jika user_type yang dipilih adalah 'staff'
      'staff_role' => ['required_if:user_type,staff', Rule::in(['chef', 'waiter', 'cashier'])],
    ]);

    $oldType = $user->user_type;
    $newType = $validated['user_type'];

    if ($oldType === $newType) {
      return redirect()->back()->with('info', 'Tidak ada perubahan role yang dilakukan.');
    }

    // Customer menjadi staff
    if ($oldType === 'customer' && $newType === 'staff') {
      CustomerDetail::where('user_id', $user->id)->delete();

      StaffDetail::create([
        'user_id' => $user->id,
        'role' => $validated['staff_role'],
        'is_active' => true,
        'joined_at' => now(),
      ]);
    }
    // Staff menjadi customer
    else if ($oldType === 'staff' && $newType === 'customer') {
      StaffDetail::where('user_id', $user->id)->delete();

      CustomerDetail::create([
        'user_id' => $user->id,
        'points' => 0,
      ]);
    }
    // Staff ke admin
    else if ($oldType === 'staff' && $newType === 'admin') {
      StaffDetail::where('user_id', $user->id)->delete();
    }

    // Customer ke admin
    else if ($oldType === 'customer' && $newType === 'admin') {
      CustomerDetail::where('user_id', $user->id)->delete();
    }

    $user->update([
      'user_type' => $newType,
    ]);

    DB::commit();

    return redirect()->route('users.show', $user)
      ->with('success', 'Role user berhasil diubah.');
  }


  // Menghapus user
  public function destroy(User $user)
  {
    $user->delete();

    return redirect()->route("users.index")->with(key: "success", value: "User berhasil dihapus");
  }
}
