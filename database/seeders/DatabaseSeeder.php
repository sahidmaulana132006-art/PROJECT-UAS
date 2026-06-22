<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EventCategory;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\Attendance;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::findOrCreate('admin');
        $panitiaRole = Role::findOrCreate('panitia');
        $pesertaRole = Role::findOrCreate('peserta');

        // 2. Create Core Testing Users
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        $admin->assignRole($adminRole);

        $panitia = User::create([
            'name' => 'Panitia Event',
            'email' => 'panitia@gmail.com',
            'password' => Hash::make('panitia123'),
            'role' => 'panitia',
        ]);
        $panitia->assignRole($panitiaRole);

        $peserta = User::create([
            'name' => 'Peserta Event',
            'email' => 'peserta@gmail.com',
            'password' => Hash::make('peserta123'),
            'role' => 'peserta',
        ]);
        $peserta->assignRole($pesertaRole);

        // 3. Create 50 Peserta Users
        $pesertas = [];
        for ($i = 1; $i <= 50; $i++) {
            $user = User::create([
                'name' => 'Peserta ' . $i,
                'email' => 'peserta' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
            ]);
            $user->assignRole($pesertaRole);
            $pesertas[] = $user;
        }

        // 4. Create 5 Event Categories
        $categories = [
            EventCategory::create(['nama_kategori' => 'Seminar', 'deskripsi' => 'Seminar akademik dan non-akademik skala nasional/internasional.']),
            EventCategory::create(['nama_kategori' => 'Workshop', 'deskripsi' => 'Pelatihan praktis dan interaktif untuk mengasah skill teknis.']),
            EventCategory::create(['nama_kategori' => 'Kompetisi', 'deskripsi' => 'Lomba akademik, seni, olahraga, dan teknologi tingkat mahasiswa.']),
            EventCategory::create(['nama_kategori' => 'Bootcamp', 'deskripsi' => 'Pelatihan intensif berdurasi panjang untuk persiapan karir.']),
            EventCategory::create(['nama_kategori' => 'Event Organisasi', 'deskripsi' => 'Kegiatan yang diselenggarakan oleh BEM, Himpunan, atau UKM.']),
        ];

        // 5. Create 10 Events
        $eventsData = [
            [
                'nama_event' => 'Seminar Nasional Artificial Intelligence 2026',
                'category_idx' => 0,
                'deskripsi' => 'Seminar kupas tuntas masa depan AI dan implementasinya dalam dunia industri modern bersama pembicara ahli.',
                'lokasi' => 'Auditorium Utama Kampus',
                'kuota' => 150,
                'harga' => 0,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'Workshop Desain UI/UX dengan Figma',
                'category_idx' => 1,
                'deskripsi' => 'Pelatihan intensif mendesain antarmuka aplikasi mobile dan web dari nol hingga pembuatan prototype interaktif.',
                'lokasi' => 'Lab Komputer Gedung B',
                'kuota' => 50,
                'harga' => 50000,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'National Web Design Competition (NWDC)',
                'category_idx' => 2,
                'deskripsi' => 'Kompetisi merancang website inovatif tingkat mahasiswa nasional dengan tema "Green Technology".',
                'lokasi' => 'Gedung Rektorat Lantai 3',
                'kuota' => 30,
                'harga' => 100000,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'Laravel 12 Web Developer Bootcamp',
                'category_idx' => 3,
                'deskripsi' => 'Bootcamp intensif 1 bulan mempelajari pengembangan web berskala enterprise menggunakan Laravel 12.',
                'lokasi' => 'Online (Zoom Meeting)',
                'kuota' => 80,
                'harga' => 150000,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'Malam Keakraban Himpunan Mahasiswa (Makrab)',
                'category_idx' => 4,
                'deskripsi' => 'Kegiatan silaturahmi dan bonding antar anggota himpunan mahasiswa baru dan pengurus.',
                'lokasi' => 'Villa Camp Hulu, Puncak',
                'kuota' => 100,
                'harga' => 75000,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'Seminar Karir: Sukses Menembus Big Tech',
                'category_idx' => 0,
                'deskripsi' => 'Tips dan trik menulis resume, membuat portfolio, serta menghadapi technical interview di perusahaan teknologi.',
                'lokasi' => 'Aula Gedung C',
                'kuota' => 200,
                'harga' => 0,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'Workshop IoT (Internet of Things) Dasar',
                'category_idx' => 1,
                'deskripsi' => 'Hands-on project merakit sensor suhu otomatis dengan ESP32 dan mengirim data ke platform cloud.',
                'lokasi' => 'Lab Elektronika Gedung A',
                'kuota' => 25,
                'harga' => 120000,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'Kompetisi Bisnis Plan Mahasiswa',
                'category_idx' => 2,
                'deskripsi' => 'Ajang kompetisi menyusun proposal ide bisnis kreatif dan mempresentasikannya di depan dewan juri.',
                'lokasi' => 'Mini Theater Kampus',
                'kuota' => 40,
                'harga' => 50000,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'Android App Development Bootcamp',
                'category_idx' => 3,
                'deskripsi' => 'Belajar membuat aplikasi Android modern menggunakan Kotlin dan Jetpack Compose secara komprehensif.',
                'lokasi' => 'Online (Zoom Meeting)',
                'kuota' => 60,
                'harga' => 200000,
                'status' => 'Active',
            ],
            [
                'nama_event' => 'LDKM (Latihan Dasar Kepemimpinan Mahasiswa)',
                'category_idx' => 4,
                'deskripsi' => 'Pelatihan kepemimpinan dan manajemen organisasi bagi calon pengurus baru organisasi mahasiswa kampus.',
                'lokasi' => 'Aula Pusdiklat Kampus',
                'kuota' => 70,
                'harga' => 30000,
                'status' => 'Active',
            ],
        ];

        $events = [];
        $now = Carbon::now();
        foreach ($eventsData as $index => $data) {
            $cat = $categories[$data['category_idx']];
            $startDate = $now->copy()->addDays(($index * 3) - 10); // Mix of past and future
            $endDate = $startDate->copy()->addHours(4);

            $events[] = Event::create([
                'event_category_id' => $cat->id,
                'nama_event' => $data['nama_event'],
                'deskripsi' => $data['deskripsi'],
                'lokasi' => $data['lokasi'],
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'kuota' => $data['kuota'],
                'harga' => $data['harga'],
                'status' => $data['status'],
                'poster' => null,
            ]);
        }

        // 6. Create 20 Registrations
        $registrations = [];
        $usedPairs = [];
        for ($i = 0; $i < 20; $i++) {
            do {
                $user = $pesertas[array_rand($pesertas)];
                $event = $events[array_rand($events)];
                $pair = $user->id . '-' . $event->id;
            } while (in_array($pair, $usedPairs));

            $usedPairs[] = $pair;

            // Pick statuses: 15 Diterima, 3 Pending, 2 Ditolak
            if ($i < 15) {
                $status = 'Diterima';
            } elseif ($i < 18) {
                $status = 'Pending';
            } else {
                $status = 'Ditolak';
            }

            $regDate = Carbon::parse($event->tanggal_mulai)->subDays(rand(2, 10));

            $registrations[] = Registration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'tanggal_daftar' => $regDate,
                'status_pendaftaran' => $status,
            ]);
        }

        // 7. Create 15 Payments
        // Nominal equals event price. Status: 10 Verified, 3 Pending, 2 Rejected.
        $paymentCount = 0;
        foreach ($registrations as $reg) {
            if ($paymentCount >= 15) {
                break;
            }

            $event = $reg->event;

            // Status allocation
            if ($paymentCount < 10) {
                $vStatus = 'Verified';
            } elseif ($paymentCount < 13) {
                $vStatus = 'Pending';
            } else {
                $vStatus = 'Rejected';
            }

            Payment::create([
                'registration_id' => $reg->id,
                'nominal' => $event->harga,
                'bukti_pembayaran' => 'bukti_pembayaran_dummy.jpg',
                'status_verifikasi' => $vStatus,
                'tanggal_bayar' => Carbon::parse($reg->tanggal_daftar)->addHours(rand(1, 24)),
            ]);

            $paymentCount++;
        }

        // 8. Create 15 Attendances
        // Recorded for registrations where status is Diterima. Status: 12 Hadir, 3 Tidak Hadir.
        $attendanceCount = 0;
        foreach ($registrations as $reg) {
            if ($reg->status_pendaftaran === 'Diterima') {
                if ($attendanceCount >= 15) {
                    break;
                }

                $aStatus = ($attendanceCount < 12) ? 'Hadir' : 'Tidak Hadir';
                $waktuAbsen = ($aStatus === 'Hadir')
                    ? Carbon::parse($reg->event->tanggal_mulai)->addMinutes(rand(5, 45))
                    : null;

                Attendance::create([
                    'registration_id' => $reg->id,
                    'waktu_absen' => $waktuAbsen,
                    'status_kehadiran' => $aStatus,
                ]);

                $attendanceCount++;
            }
        }
    }
}
