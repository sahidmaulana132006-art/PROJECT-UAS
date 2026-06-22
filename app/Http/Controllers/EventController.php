<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('category');

        // Filter by search query
        if ($request->filled('search')) {
            $query->where('nama_event', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('event_category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(9)->withQueryString();
        $categories = EventCategory::all();

        // If user is admin/panitia, show manage dashboard. If peserta/guest, show listing page.
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'panitia')) {
            return view('events.index_manage', compact('events', 'categories'));
        }

        return view('events.index_list', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        $hasRegistered = false;
        $registrationStatus = null;

        if (auth()->check()) {
            $registration = auth()->user()->registrations()->where('event_id', $event->id)->first();
            if ($registration) {
                $hasRegistered = true;
                $registrationStatus = $registration->status_pendaftaran;
            }
        }

        return view('events.show', compact('event', 'hasRegistered', 'registrationStatus'));
    }

    public function create()
    {
        $categories = EventCategory::all();
        return view('events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_category_id' => 'required|exists:event_categories,id',
            'nama_event' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'status' => 'required|in:Active,Inactive,Completed',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('poster');

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $response = Http::asMultipart()->post(
                'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getRealPath(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                    [
                        'name' => 'upload_preset',
                        'contents' => env('CLOUDINARY_UPLOAD_PRESET'),
                    ],
                ]
            );

            $result = $response->json();

            if (isset($result['secure_url'])) {
                $data['poster'] = $result['secure_url'];
            }
        }

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event)
    {
        $categories = EventCategory::all();
        return view('events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_category_id' => 'required|exists:event_categories,id',
            'nama_event' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'harga' => 'required|integer|min:0',
            'status' => 'required|in:Active,Inactive,Completed',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('poster');

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $response = Http::asMultipart()->post(
                'https://api.cloudinary.com/v1_1/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload',
                [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getRealPath(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ],
                    [
                        'name' => 'upload_preset',
                        'contents' => env('CLOUDINARY_UPLOAD_PRESET'),
                    ],
                ]
            );

            $result = $response->json();

            if (isset($result['secure_url'])) {
                $data['poster'] = $result['secure_url'];
            }
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        if ($event->registrations()->count() > 0) {
            return redirect()->route('events.index')->with('error', 'Event tidak dapat dihapus karena memiliki data pendaftaran.');
        }

        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus.');
    }
}
