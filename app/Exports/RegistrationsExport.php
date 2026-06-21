<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrationsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventId;

    public function __construct($eventId = null)
    {
        $this->eventId = $eventId;
    }

    /**
     * Return collection of registrations.
     */
    public function collection()
    {
        $query = Registration::with(['user', 'event', 'payment', 'attendance']);

        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        return $query->latest()->get();
    }

    /**
     * Headers for the Excel sheet.
     */
    public function headings(): array
    {
        return [
            'ID Registrasi',
            'Nama Peserta',
            'Email Peserta',
            'Nama Event',
            'Tanggal Daftar',
            'Status Pendaftaran',
            'Status Pembayaran',
            'Nominal Pembayaran',
            'Status Kehadiran',
        ];
    }

    /**
     * Map each row of registration.
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->user->name ?? '-',
            $row->user->email ?? '-',
            $row->event->nama_event ?? '-',
            $row->tanggal_daftar ? $row->tanggal_daftar->format('d-m-Y H:i') : '-',
            $row->status_pendaftaran,
            $row->payment->status_verifikasi ?? 'Belum Bayar',
            $row->payment ? 'Rp ' . number_format($row->payment->nominal, 0, ',', '.') : '-',
            $row->attendance->status_kehadiran ?? 'Tidak Hadir',
        ];
    }
}
