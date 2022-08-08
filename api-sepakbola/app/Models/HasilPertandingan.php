<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilPertandingan extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'hasil_pertandingan';
    protected $fillable = ['jadwal_pertandingan_id', 'total_skor_akhir_tim_tuan', 'total_skor_akhir_tim_tamu'];

    public function jadwalPertandingan(){
        return $this->belongsTo(JadwalPertandingan::class,'jadwal_pertandingan_id', 'id');
    }
}
