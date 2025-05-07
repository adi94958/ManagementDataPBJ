<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanFho extends Model
{
    use HasFactory;

    protected $table = 'tagihan_fho';
    protected $primaryKey = 'nomor_surat_permohonan_fho_vendor';
    public $incrementing = false;

    protected $fillable = [
        'nomor_kontrak',
        'nomor_surat_permohonan_fho_vendor',
        'tanggal_surat_permohonan_fho_vendor',
        'nomor_surat_laporan_tindak_lanjut_fho',
        'tanggal_surat_laporan_tindak_lanjut_fho',
        'nomor_bapp_pada_fho',
        'tanggal_bapp_pada_fho',
        'nomor_bastp_pada_fho',
        'tanggal_bastp_pada_fho',
    ];

    public function berkasPbj()
    {
        return $this->belongsTo(BerkasPbj::class, 'nomor_kontrak', 'nomor_kontrak');
    }
}
