<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportHasilPertandingan extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'report_hasil_pertandingan';
    protected $fillable = ['jadwal_pertandingan_id', 'tim_tuan_id', 'nama_tim_tuan', 'tim_tamu_id', 'nama_tim_tamu', 'total_skor_akhir_tim_tuan', 'total_skor_akhir_tim_tamu', 'status_akhir_pertandingan', 'id_pemain_pencetakgol_terbanyak', 'nama_pemain_pencetakgol_terbanyak', 'akumulasi_total_kemenangan_tim_tuan', 'akumulasi_total_kemenangan_tim_tamu'];
}
