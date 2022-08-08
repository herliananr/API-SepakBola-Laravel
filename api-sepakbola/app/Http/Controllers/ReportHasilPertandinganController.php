<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\PencetakGolPertandingan;
use App\Models\ReportHasilPertandingan;
use App\Models\JadwalPertandingan;
use App\Models\HasilPertandingan;
use App\Models\Pemain;
use App\Models\Tim;
use Carbon\Carbon;
use Image;

class ReportHasilPertandinganController extends Controller
{
    public function index(){
        $list_report_hasil_pertandingan = ReportHasilPertandingan::latest()->get();

        return response([
            'success' => true,
            'message' => 'List Data Report Hasil Pertandingan',
            'data'    => $list_report_hasil_pertandingan
        ]);
    }

    public function show($id){
        $cek_report_hasil_pertandingan = ReportHasilPertandingan::find($id);

        if ($cek_report_hasil_pertandingan) {
            return response([
                'success' => true,
                'message' => 'Menampilkan detail data',
                'data'    => $cek_report_hasil_pertandingan
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
