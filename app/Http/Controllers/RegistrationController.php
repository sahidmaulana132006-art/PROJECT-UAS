<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Attendance;
use App\Exports\RegistrationsExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Registration::with(['user', 'event', 'payment', 'attendance']);

        // Search & Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', '%' . $search . '%')
                       ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('event', function($eq) use ($search) {
                    $eq->where('nama_event', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status_pendaftaran')) {
            $query->where('status_pendaftaran', $request->status_pendaftaran);
        }

        // Access control: Peserta only sees their own
        if ($user->role === 'peserta') {
            $query->where('user_id', $user->id);
            $registrations = $query->latest()->paginate(10)->withQueryString();
            return view('registrations.peserta_index', compact('registrations'));
        }

        // Admin & Panitia can see all
        $registrations = $query->latest()->paginate(10)->withQueryString();
        $events = Event::all();

        return view('registrations.index', compact('registrations', 'events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $user = auth()->user();
        $event = Event::findOrFail($request->event_id);

        // Check if already registered
        $existing = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah terdaftar pada event ini.');
        }

        // Check quota (only count Accepted/Pending registrations)
        $activeRegistrationsCount = Registration::where('event_id', $event->id)
            ->where('status_pendaftaran', '!=', 'Ditolak')
            ->count();

        if ($activeRegistrationsCount >= $event->kuota) {
            return redirect()->back()->with('error', 'Pendaftaran gagal. Kuota event sudah penuh.');
        }

        // Create registration
        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'tanggal_daftar' => now(),
            'status_pendaftaran' => 'Pending',
        ]);

        // Auto create empty attendance slot
        Attendance::create([
            'registration_id' => $registration->id,
            'status_kehadiran' => 'Tidak Hadir',
        ]);

        // If event is free (harga == 0), auto create a verified payment record of 0 nominal
        if ($event->harga == 0) {
            Payment::create([
                'registration_id' => $registration->id,
                'nominal' => 0,
                'status_verifikasi' => 'Verified',
                'tanggal_bayar' => now(),
            ]);
            // Free events are auto-accepted
            $registration->update(['status_pendaftaran' => 'Diterima']);
        }

        return redirect()->route('registrations.index')->with('success', 'Berhasil mendaftar event. Silakan cek status pendaftaran Anda.');
    }

    public function updateStatus(Request $request, Registration $registration)
    {
        $request->validate([
            'status_pendaftaran' => 'required|in:Pending,Diterima,Ditolak',
        ]);

        // Admin verification logic
        $registration->update([
            'status_pendaftaran' => $request->status_pendaftaran,
        ]);

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function exportPdf(Request $request)
    {
        $query = Registration::with(['user', 'event', 'payment', 'attendance']);

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $registrations = $query->latest()->get();
        $event = $request->filled('event_id') ? Event::find($request->event_id) : null;

        $pdf = Pdf::loadView('exports.registrations_pdf', compact('registrations', 'event'))
            ->setPaper('a4', 'landscape');

        $fileName = 'Laporan_Pendaftaran_SIEK_' . time() . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportExcel(Request $request)
    {
        $eventId = $request->get('event_id');
        $fileName = 'Laporan_Pendaftaran_SIEK_' . time() . '.xlsx';
        return Excel::download(new RegistrationsExport($eventId), $fileName);
    }

    public function destroy(Registration $registration)
    {
        // Admin CRUD can delete registration
        $registration->delete();
        return redirect()->back()->with('success', 'Pendaftaran berhasil dihapus.');
    }
}
