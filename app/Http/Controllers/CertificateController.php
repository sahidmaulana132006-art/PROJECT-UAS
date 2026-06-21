<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'peserta') {
            // Peserta: view their own certificates
            $certificates = Certificate::with(['registration.event'])
                ->whereHas('registration', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->paginate(10);
            
            return view('certificates.peserta_index', compact('certificates'));
        }

        // Admin & Panitia see all certificates, can upload
        $query = Certificate::with(['registration.user', 'registration.event']);

        if ($request->filled('event_id')) {
            $query->whereHas('registration', function($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        $certificates = $query->latest()->paginate(10)->withQueryString();
        $events = Event::all();

        return view('certificates.index', compact('certificates', 'events'));
    }

    public function create(Request $request)
    {
        $events = Event::all();
        $selectedEventId = $request->get('event_id');

        $eligibleRegistrations = [];
        if ($selectedEventId) {
            // Find registered participants who are Accepted (Diterima) and Attended (Hadir)
            // and do not have a certificate issued yet.
            $eligibleRegistrations = Registration::with('user')
                ->where('event_id', $selectedEventId)
                ->where('status_pendaftaran', 'Diterima')
                ->whereHas('attendance', function($q) {
                    $q->where('status_kehadiran', 'Hadir');
                })
                ->whereDoesntHave('certificate')
                ->get();
        }

        return view('certificates.create', compact('events', 'selectedEventId', 'eligibleRegistrations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id|unique:certificates,registration_id',
            'nomor_sertifikat' => 'required|string|unique:certificates,nomor_sertifikat',
            'file_sertifikat' => 'required|file|mimes:pdf|max:5120', // PDF, max 5MB
        ]);

        $file = $request->file('file_sertifikat');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('certificates', $fileName, 'public');
        $filePath = 'certificates/' . $fileName;

        Certificate::create([
            'registration_id' => $request->registration_id,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'file_sertifikat' => $filePath,
            'tanggal_terbit' => now(),
        ]);

        return redirect()->route('certificates.index')->with('success', 'Sertifikat berhasil diupload.');
    }

    public function download(Certificate $certificate)
    {
        $user = auth()->user();

        // Security check for Peserta
        if ($user->role === 'peserta' && $certificate->registration->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $filePath = $certificate->file_sertifikat;

        if (!Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->with('error', 'File sertifikat tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($filePath, 'Sertifikat_' . str_replace('/', '_', $certificate->nomor_sertifikat) . '.pdf');
    }

    public function destroy(Certificate $certificate)
    {
        if ($certificate->file_sertifikat && Storage::disk('public')->exists($certificate->file_sertifikat)) {
            Storage::disk('public')->delete($certificate->file_sertifikat);
        }

        $certificate->delete();

        return redirect()->back()->with('success', 'Sertifikat berhasil dihapus.');
    }
}
