<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Admin views all payments
        $query = Payment::with(['registration.user', 'registration.event']);

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function showUploadForm(Registration $registration)
    {
        // Verify ownership
        if (auth()->user()->role === 'peserta' && $registration->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $payment = $registration->payment;

        return view('payments.upload', compact('registration', 'payment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'nominal' => 'required|integer|min:0',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $registration = Registration::findOrFail($request->registration_id);

        // Verify ownership
        if (auth()->user()->role === 'peserta' && $registration->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        // Upload file
        $file = $request->file('bukti_pembayaran');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('payments', $fileName, 'public');
        $filePath = 'payments/' . $fileName;

        // Create or update payment
        $payment = Payment::updateOrCreate(
            ['registration_id' => $registration->id],
            [
                'nominal' => $request->nominal,
                'bukti_pembayaran' => $filePath,
                'status_verifikasi' => 'Pending',
                'tanggal_bayar' => now(),
            ]
        );

        return redirect()->route('registrations.index')->with('success', 'Bukti pembayaran berhasil diupload. Harap tunggu verifikasi.');
    }

    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Verified,Rejected',
        ]);

        $payment->update([
            'status_verifikasi' => $request->status_verifikasi,
        ]);

        // Auto update registration status
        if ($request->status_verifikasi === 'Verified') {
            $payment->registration->update([
                'status_pendaftaran' => 'Diterima',
            ]);
        } else {
            $payment->registration->update([
                'status_pendaftaran' => 'Ditolak',
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function destroy(Payment $payment)
    {
        if ($payment->bukti_pembayaran && Storage::disk('public')->exists($payment->bukti_pembayaran)) {
            Storage::disk('public')->delete($payment->bukti_pembayaran);
        }

        $payment->delete();

        return redirect()->back()->with('success', 'Data pembayaran berhasil dihapus.');
    }
}
