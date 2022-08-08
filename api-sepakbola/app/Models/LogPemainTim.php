<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogPemainTim extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'log_pemain_tim';
    protected $fillable = ['pemain_id', 'tim_id', 'posisi', 'nomor_punggung'];

    public function pemain() {
        return $this->belongsTo(Pemain::class,"pemain_id","id");
    }
}
