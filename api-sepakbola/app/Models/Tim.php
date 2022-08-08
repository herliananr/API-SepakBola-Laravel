<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tim extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'tim';
    protected $fillable = ['nama', 'logo', 'tahun_berdiri', 'alamat_markas', 'kota_markas_id'];

    public function pemains(){
        return $this->hasMany(Pemain::class,'tim_id', 'id');
    }

    public function kotaMarkas() {
        return $this->belongsTo(KotaMarkas::class,"kota_markas_id","id");
    }
}
