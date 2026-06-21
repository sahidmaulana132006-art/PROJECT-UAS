<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'nomor_sertifikat',
        'file_sertifikat',
        'tanggal_terbit'
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}