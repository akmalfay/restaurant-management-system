<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categoryId = $request->get('category');

        $menuItems = MenuItem::query()
            ->with('category')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy('category_id')
            ->orderBy('name')
            ->paginate(12)
            ->appends(['search' => $search, 'category' => $categoryId]);

        $categories = Category::orderBy('name')->get();

        return view('menu-items.index', compact('menuItems', 'categories', 'search', 'categoryId'));
    }

    public function show(MenuItem $menuItem)
    {
        $menuItem->load('category');
        return view('menu-items.show', compact('menuItem'));
    }

    public function create()
    {
        // Hanya admin dan staff
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::orderBy('name')->get();
        return view('menu-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:menu_items'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_available' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        MenuItem::create($validated);

        return redirect()->route('menu-items.index')
            ->with('success', 'Menu item berhasil ditambahkan');
    }

    public function edit(MenuItem $menuItem)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::orderBy('name')->get();
        return view('menu-items.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:menu_items,name,' . $menuItem->id],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'is_available' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            // Hapus image lama
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        $menuItem->update($validated);

        return redirect()->route('menu-items.show', $menuItem)
            ->with('success', 'Menu item berhasil diupdate');
    }

    public function destroy(MenuItem $menuItem)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized');
        }

        // Hapus image
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $menuItem->delete();

        return redirect()->route('menu-items.index')
            ->with('success', 'Menu item berhasil dihapus');
    }
}
