<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = Staff::all();
        return view('staffs.index', compact('staffs'));
    }

    public function create()
    {
        // Menampilkan form tambah staff
        return view('staffs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'active' => 'required|boolean',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|file|image|max:2048', // ubah jadi file upload
        ]);

        // Upload file jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('staff_images', 'public');
        }

        Staff::create($validated);
        return redirect()->route('staffs.index')->with('success', 'Staff berhasil ditambahkan!');
    }

    public function show(Staff $staff)
    {
        return response()->json($staff);
    }

    public function edit(Staff $staff)
    {
        // Menampilkan form edit staff
        return view('staffs.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'active' => 'sometimes|required|boolean',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|file|image|max:2048',
        ]);

        // Upload file baru kalau ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('staff_images', 'public');
        }

        $staff->update($validated);
        return redirect()->route('staffs.index')->with('success', 'Staff berhasil diperbarui!');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staffs.index')->with('success', 'Staff berhasil dihapus!');
    }
}
