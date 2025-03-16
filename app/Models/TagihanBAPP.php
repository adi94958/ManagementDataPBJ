<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanBAPP extends Model
{
    use HasFactory;

    protected $table = 'tagihan_bapp';
    protected $primaryKey = 'nomor_bapp';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nomor_bapp',
        'nomor_kontrak',
        'nomor_permohonan_bapp',
        'tanggal_permohonan_bapp',
        'tanggal_bapp',
        'nilai_kontrak_bapp',
        'jumlah_bayar_termin_1_bapp',
        'jangka_waktu_pemeliharaan_bapp',
    ];

    protected $casts = [
        'tanggal_permohonan_bapp' => 'date',
        'tanggal_bapp' => 'date',
        'nilai_kontrak_bapp' => 'integer',
    ];

    public function berkasPBJ()
    {
        return $this->belongsTo(BerkasPBJ::class, 'nomor_kontrak', 'nomor_kontrak');
    }
}
