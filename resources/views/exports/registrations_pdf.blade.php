<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendaftaran Event - SIEK</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 15px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            color: #0F172A;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 3px 0;
            font-size: 11px;
        }

        .info-label {
            font-weight: bold;
            color: #475569;
            width: 120px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background-color: #0F172A;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #1e293b;
        }

        .data-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Status Badge Styling for PDF compatibility */
        .status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        .status-diterima {
            color: #16A34A;
        }

        .status-pending {
            color: #D97706;
        }

        .status-ditolak {
            color: #DC2626;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Sistem Informasi Event Kampus (SIEK)</h2>
        <p>Laporan Pendaftaran Peserta Kegiatan Mahasiswa</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td class="info-label">Tanggal Cetak:</td>
                <td>{{ now()->format('d M Y, H:i') }} WIB</td>
                @if($event)
                    <td class="info-label" style="text-align: right;">Target Event:</td>
                    <td style="text-align: right; font-weight: bold; color: #2563EB;">{{ $event->nama_event }}</td>
                @endif
            </tr>
            @if($event)
                <tr>
                    <td class="info-label">Penyelenggara:</td>
                    <td>Universitas Utama</td>
                    <td class="info-label" style="text-align: right;">Biaya Tiket:</td>
                    <td style="text-align: right;">{{ $event->harga == 0 ? 'Gratis' : 'Rp ' . number_format($event->harga, 0, ',', '.') }}</td>
                </tr>
            @endif
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama Peserta</th>
                <th style="width: 20%">Email</th>
                @if(!$event)
                    <th style="width: 25%">Event</th>
                @endif
                <th style="width: 15%">Tanggal Daftar</th>
                <th style="width: 10%">Pendaftaran</th>
                <th style="width: 10%">Pembayaran</th>
                <th style="width: 10%">Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $index => $reg)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $reg->user->name ?? '-' }}</strong></td>
                    <td>{{ $reg->user->email ?? '-' }}</td>
                    @if(!$event)
                        <td>{{ $reg->event->nama_event ?? '-' }}</td>
                    @endif
                    <td>{{ $reg->tanggal_daftar ? $reg->tanggal_daftar->format('d-m-Y H:i') : '-' }}</td>
                    <td>
                        @if($reg->status_pendaftaran === 'Diterima')
                            <span class="status status-diterima">Diterima</span>
                        @elseif($reg->status_pendaftaran === 'Pending')
                            <span class="status status-pending">Pending</span>
                        @else
                            <span class="status status-ditolak">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($reg->event->harga == 0)
                            Gratis
                        @elseif($reg->payment)
                            @if($reg->payment->status_verifikasi === 'Verified')
                                Lunas (Verified)
                            @elseif($reg->payment->status_verifikasi === 'Pending')
                                Pending Review
                            @else
                                Ditolak
                            @endif
                        @else
                            Belum Bayar
                        @endif
                    </td>
                    <td>
                        {{ $reg->attendance->status_kehadiran ?? 'Tidak Hadir' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $event ? '7' : '8' }}" style="text-align: center; padding: 20px; color: #94a3b8;">
                        Tidak ada data pendaftaran yang sesuai.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Sistem Informasi Event Kampus (SIEK) &copy; 2026.
    </div>

</body>
</html>
