<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanBASTP extends Model
{
    use HasFactory;
    protected $table = 'tagihan_bastp';
    protected $fillable = [
        'nomor_bastp',
        'nomor_kontrak',
        'nomor_permohonan_bastp',
        'tanggal_permohonan_bastp',
        'tanggal_bastp',
        'nilai_kontrak_bastp',
        'jumlah_bayar_termin_1_bastp',
        'jangka_waktu_pemeliharaan_bastp',
    ];

    protected $casts = [
        'tanggal_permohonan_bastp' => 'date',
        'tanggal_bastp' => 'date',
        'nilai_kontrak_bastp' => 'integer',
    ];

    public function berkasPBJ()
    {
        return $this->belongsTo(BerkasPBJ::class, 'nomor_kontrak', 'nomor_kontrak');
    }
}
