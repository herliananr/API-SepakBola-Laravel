<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PencetakGolPertandingan extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'pencetak_gol_pertandingan';
    protected $fillable = ['jadwal_pertandingan_id', 'tim_id', 'pemain_pencetakgol_id', 'waktu_gol'];

    public function jadwalPertandingan() {
        return $this->belongsTo(JadwalPertandingan::class,"jadwal_pertandingan_id","id");
    }

    public function tim() {
        return $this->belongsTo(Tim::class,"tim_id","id");
    }

    public function pemainPencetakGol() {
        return $this->belongsTo(Pemain::class,"pemain_pencetakgol_id","id");
    }
}
