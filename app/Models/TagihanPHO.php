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
    protected $keyType = 'integer';

    protected $fillable = [
        'nomor_kontrak',
        'nomor_ba_pemeriksaan_pekerjaan_pho',
        'tanggal_ba_pemeriksaan_pekerjaan_pho',
        'nomor_ba_serah_terima_pho',
        'tanggal_ba_serah_terima_pho'
    ];

    public function berkasPbj()
    {
        return $this->belongsTo(BerkasPbj::class, 'nomor_kontrak', 'nomor_kontrak');
    }
}
