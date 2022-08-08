<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KotaMarkas extends Model
{
    // use HasFactory;
    use SoftDeletes;
    protected $table = 'kota_markas';
    protected $fillable = ['nama_kota'];
    protected $dates = ['deleted_at'];
}
