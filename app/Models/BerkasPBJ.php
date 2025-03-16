<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPBJ extends Model
{
    use HasFactory;

    protected $table = 'berkas_pbj';
    protected $primaryKey = 'nomor_kontrak';
    public $incrementing = false;

    protected $fillable = [
        'nomor_kontrak',
        'nama_kontrak',
        'tanggal_kontrak_mulai',
        'tanggal_kontrak_selesai',
        'nilai_kontrak_pbj',
        'nama_vendor',
    ];

    public function tagihanPHO()
    {
        return $this->hasOne(TagihanPHO::class, 'nomor_kontrak', 'nomor_kontrak');
    }

    public function tagihanFHO()
    {
        return $this->hasOne(TagihanFHO::class, 'nomor_kontrak', 'nomor_kontrak');
    }

    public function tagihanBAPP()
    {
        return $this->hasone(TagihanBAPP::class, 'nomor_kontrak', 'nomor_kontrak');
    }

    public function tagihanBASTP()
    {
        return $this->hasone(TagihanBASTP::class, 'nomor_kontrak', 'nomor_kontrak');
    }
}
