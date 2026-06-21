<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'tanggal_daftar',
        'status_pendaftaran'
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}