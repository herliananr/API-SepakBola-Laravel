<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemain extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'pemain';
    protected $fillable = ['nama', 'tinggi_badan', 'berat_badan', 'tim_id', 'posisi', 'nomor_punggung'];

    public function logPemainTims(){
        return $this->hasMany(LogPemainTim::class,'pemain_id', 'id');
    }

    public function tim() {
        return $this->belongsTo(Tim::class,"tim_id","id");
    }
}
