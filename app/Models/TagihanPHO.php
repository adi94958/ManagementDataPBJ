<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanPho extends Model
{
    use HasFactory;

    protected $table = 'tagihan_pho';
    protected $primaryKey = 'nomor_ba_pemeriksaan_pekerjaan_pho';
    public $incrementing = false;

    protected $fillable = [
        'nomor_kontrak',
        'nomor_ba_pemeriksaan_pekerjaan_pho',
        'tanggal_ba_pemeriksaan_pekerjaan_pho',
        'nomor_ba_serah_terima_pho',
        'tanggal_ba_serah_terima_pho',
        'nomor_bapp_pada_pho',
        'tanggal_bapp_pada_pho',
        'nomor_bastp_pada_pho',
        'tanggal_bastp_pada_pho',
        'nomor_permohonan_pho_vendor',
        'tanggal_permohonan_pho_vendor',
    ];

    public function berkasPbj()
    {
        return $this->belongsTo(BerkasPbj::class, 'nomor_kontrak', 'nomor_kontrak');
    }
}
