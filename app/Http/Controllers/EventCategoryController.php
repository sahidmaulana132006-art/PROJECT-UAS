<?php

namespace App\Http\Controllers;

use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::withCount('events')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        EventCategory::create(
            $request->only([
                'nama_kategori',
                'deskripsi'
            ])
        );

        return redirect()->route('categories.index')->with('success', 'Kategori event berhasil ditambahkan.');
    }

    public function edit(EventCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, EventCategory $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Kategori event berhasil diperbarui.');
    }

    public function destroy(EventCategory $category)
    {
        if ($category->events()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak dapat dihapus karena memiliki event terkait.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori event berhasil dihapus.');
    }
}
