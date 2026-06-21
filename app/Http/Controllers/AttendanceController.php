<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Filter by Event
        $events = Event::all();
        $selectedEventId = $request->get('event_id', $events->first()->id ?? null);

        $attendances = [];
        if ($selectedEventId) {
            $attendances = Attendance::with(['registration.user', 'registration.event'])
                ->whereHas('registration', function($query) use ($selectedEventId) {
                    $query->where('event_id', $selectedEventId)
                          ->where('status_pendaftaran', 'Diterima');
                })
                ->get();
        }

        return view('attendances.index', compact('attendances', 'events', 'selectedEventId'));
    }

    public function record(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'status_kehadiran' => 'required|in:Hadir,Tidak Hadir',
        ]);

        $attendance = Attendance::updateOrCreate(
            ['registration_id' => $request->registration_id],
            [
                'status_kehadiran' => $request->status_kehadiran,
                'waktu_absen' => $request->status_kehadiran === 'Hadir' ? now() : null,
            ]
        );

        return redirect()->back()->with('success', 'Absensi peserta berhasil diperbarui.');
    }
}
