<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } elseif ($user->role === 'panitia') {
            return $this->panitiaDashboard();
        } else {
            return $this->pesertaDashboard();
        }
    }

    private function adminDashboard()
    {
        // Stats Cards
        $totalEvent = Event::count();
        $totalPeserta = User::where('role', 'peserta')->count();
        $totalRegistrasi = Registration::count();
        $totalPendapatan = Payment::where('status_verifikasi', 'Verified')->sum('nominal');

        // Chart 1: Registrasi per Bulan (last 12 months)
        // Group by month
        $monthlyRegistrations = Registration::selectRaw("DATE_FORMAT(tanggal_daftar, '%M %Y') as month_name, DATE_FORMAT(tanggal_daftar, '%Y-%m') as month, count(*) as count")
            ->groupBy('month', 'month_name')
            ->orderBy('month')
            ->limit(12)
            ->get();

        $chart1Labels = $monthlyRegistrations->pluck('month_name')->toArray();
        $chart1Data = $monthlyRegistrations->pluck('count')->toArray();

        // Chart 2: Peserta per Event
        $pesertaEvent = Event::select('nama_event')
            ->withCount(['registrations' => function ($query) {
                $query->where('status_pendaftaran', 'Diterima');
            }])
            ->limit(10)
            ->get();

        $chart2Labels = $pesertaEvent->pluck('nama_event')->toArray();
        $chart2Data = $pesertaEvent->pluck('registrations_count')->toArray();

        // Chart 3: Status Pembayaran (Pending vs Verified vs Rejected)
        $payments = Payment::selectRaw('status_verifikasi, count(*) as count')
            ->groupBy('status_verifikasi')
            ->get();

        $chart3Labels = [];
        $chart3Data = [];
        $statuses = ['Pending', 'Verified', 'Rejected'];
        
        foreach ($statuses as $status) {
            $payment = $payments->firstWhere('status_verifikasi', $status);
            $chart3Labels[] = $status;
            $chart3Data[] = $payment ? $payment->count : 0;
        }

        return view('dashboard.admin', compact(
            'totalEvent',
            'totalPeserta',
            'totalRegistrasi',
            'totalPendapatan',
            'chart1Labels',
            'chart1Data',
            'chart2Labels',
            'chart2Data',
            'chart3Labels',
            'chart3Data'
        ));
    }

    private function panitiaDashboard()
    {
        // Panitia sees statistics related to events
        $totalEvent = Event::count();
        $myRegistrationsCount = Registration::whereHas('event', function ($query) {
            // In a real scenario, panitia might be assigned to specific events.
            // For now, they see overall student participants and registration counts.
        })->count();

        $totalHadir = Registration::whereHas('attendance', function ($query) {
            $query->where('status_kehadiran', 'Hadir');
        })->count();

        $events = Event::withCount('registrations')->latest()->limit(5)->get();

        return view('dashboard.panitia', compact('totalEvent', 'myRegistrationsCount', 'totalHadir', 'events'));
    }

    private function pesertaDashboard()
    {
        $user = auth()->user();
        
        $myRegistrations = Registration::with(['event', 'payment', 'attendance', 'certificate'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalRegistered = $myRegistrations->count();
        $totalAccepted = $myRegistrations->where('status_pendaftaran', 'Diterima')->count();
        $totalPending = $myRegistrations->where('status_pendaftaran', 'Pending')->count();

        $upcomingEvents = Event::where('tanggal_mulai', '>', now())
            ->where('status', 'Active')
            ->orderBy('tanggal_mulai')
            ->limit(4)
            ->get();

        return view('dashboard.peserta', compact('myRegistrations', 'totalRegistered', 'totalAccepted', 'totalPending', 'upcomingEvents'));
    }
}
