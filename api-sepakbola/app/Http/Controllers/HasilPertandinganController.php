<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\PencetakGolPertandingan;
use App\Models\JadwalPertandingan;
use App\Models\HasilPertandingan;
use App\Models\Pemain;
use App\Models\Tim;
use Carbon\Carbon;
use Image;

class HasilPertandinganController extends Controller
{
    public function index(){
        $list_hasil_pertandingan = HasilPertandingan::latest()->get();
        return response([
            'success' => true,
            'message' => 'List Data Hasil Pertandingan',
            'data'    => $list_hasil_pertandingan
        ]);
    }

    public function show($id){
        $cek_hasil_pertandingan        = HasilPertandingan::find($id);

        if ($cek_hasil_pertandingan) {
            $cek_pencetak_gol_pertandingan = PencetakGolPertandingan::where('jadwal_pertandingan_id', $cek_hasil_pertandingan->jadwal_pertandingan_id)->get();
        }

        if ($cek_hasil_pertandingan && $cek_pencetak_gol_pertandingan) {
            return response([
                'success' => true,
                'message' => 'Menampilkan detail data',
                'data'    => array("hasil_pertandingan"=>$cek_hasil_pertandingan, "pencetak_gol_pertandingan"=>$cek_pencetak_gol_pertandingan)
            ]);
        }
        else {
            return response([
                'success' => false,
                'message' => 'Data dengan ID yang anda cari tidak tersedia'
            ]);
        }
    }
}
