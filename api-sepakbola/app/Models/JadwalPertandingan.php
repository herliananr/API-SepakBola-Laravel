<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalPertandingan extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'jadwal_pertandingan';
    protected $fillable = ['tgl_pertandingan', 'waktu_pertandingan', 'tim_tuan_id', 'tim_tamu_id'];

    public function pencetakGolPertandingans(){
        return $this->hasMany(PencetakGolPertandingan::class,'jadwal_pertandingan_id', 'id');
    }

    public function hasilPertandingan(){
        return $this->hasOne(HasilPertandingan::class,'jadwal_pertandingan_id', 'id');
    }

    public function timTuan() {
        return $this->belongsTo(Tim::class,"tim_tuan_id","id");
    }

    public function timTamu() {
        return $this->belongsTo(Tim::class,"tim_tamu_id","id");
    }
}
