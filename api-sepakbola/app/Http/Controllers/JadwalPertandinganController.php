<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalPertandingan;
use App\Models\Tim;
use Carbon\Carbon;
use Image;

class JadwalPertandinganController extends Controller
{
    public function index(){
        $list_jadwal_pertandingan = JadwalPertandingan::latest()->get();
        return response([
            'success' => true,
            'message' => 'List Data Jadwal Pertandingan',
            'data'    => $list_jadwal_pertandingan
        ]);
    }

    public function store(Request $request) {
        $rules = [
            'tgl_pertandingan'   => 'required|date|date_format:d-m-Y|after_or_equal:today',
            'waktu_pertandingan' => 'required|date_format:H:i:s',
            'tim_tuan_id'        => 'required|integer',
            'tim_tamu_id'        => 'required|integer',
        ];
        $messages = [
            'tgl_pertandingan.required'       => 'Tanggal pertandingan belum diisi',
            'tgl_pertandingan.date'           => 'Mohon untuk mengisi format tanggal pertandingan dengan format:d-m-Y (hari-bulan-tahun dalam satuan angka)',
            'tgl_pertandingan.date_format'    => 'Mohon untuk mengisi format tanggal pertandingan dengan format:d-m-Y (hari-bulan-tahun dalam satuan angka)',
            'tgl_pertandingan.after_or_equal' => 'Mohon untuk mengisi tanggal pertandingan minimal hari ini atau hari selanjutnya',
            'waktu_pertandingan.required'     => 'Waktu pertandingan belum diisi',
            'waktu_pertandingan.date_format'  => 'Mohon untuk mengisi format waktu pertandingan dengan format:H:i:s (jam:menit:detik dalam satuan angka)',
            'tim_tuan_id.required'            => 'ID tim tuan belum diisi',
            'tim_tamu_id.required'            => 'ID tim tamu belum diisi',
            'tim_tuan_id.integer'             => 'Mohon untuk mengisi ID tim tuan dengan bilangan bulat',
            'tim_tamu_id.integer'             => 'Mohon untuk mengisi ID tim tamu dengan bilangan bulat',
        ];

        $validasi = Validator::make($request->all(), $rules, $messages);
        if ($validasi->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Mohon untuk mengisi bidang yang kosong',
                'data'    => $validasi->errors(),
            ]);
        }
        else {
            $tim_tuan = Tim::find($request->tim_tuan_id);
            $tim_tamu = Tim::find($request->tim_tamu_id);

            // cek apakah tim tuan sama dengan tim tamu, apabila iya, mohon perbaiki dengan mengisi tim tuan dengan tim tamu yang berbeda
            if (($request->tim_tuan_id) == ($request->tim_tamu_id)) {
                $status = false;
                $msg    = 'ID tim tuan dengan ID tim tamu tidak boleh sama. Mohon untuk memasukkan ID tim tuan berbeda dengan ID tim tamu.';
            }
            // cek apakah tim tuan dan tim tamu di dalam database
            else if ((!$tim_tuan) || (!$tim_tamu)) {
                $status = false;
                $msg    = 'ID tim tuan atau ID tim tamu tidak ada di dalam database';
            }
            else if ($tim_tuan && $tim_tamu) {
                $time         = explode("-", $request->tgl_pertandingan);

                $jadwal_pertandingan = JadwalPertandingan::create([
                    'tgl_pertandingan'   => $time[2]."-".$time[1]."-".$time[0],
                    'waktu_pertandingan' => $request->waktu_pertandingan,
                    'tim_tuan_id'        => $request->tim_tuan_id,
                    'tim_tamu_id'        => $request->tim_tamu_id,
                ]);

                if($jadwal_pertandingan) {
                    $status = true;
                    $msg    = 'Data jadwal pertandingan berhasil disimpan';
                }
                else {
                    $status = false;
                    $msg    = 'Data jadwal pertandingan gagal disimpan';
                }
            }

            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function show($id){
        $cek_jadwal_pertandingan = JadwalPertandingan::find($id);

        if($cek_jadwal_pertandingan) {
            return response([
                'success' => true,
                'message' => 'Menampilkan detail data',
                'data'    => $cek_jadwal_pertandingan
            ]);
        }
        else {
            return response([
                'success' => false,
                'message' => 'Data untuk ID yang dicari tidak tersedia'
            ]);
        }
    }

    public function update(Request $request, $id) {
        $rules = [
            'tgl_pertandingan'   => 'required|date|date_format:d-m-Y|after_or_equal:today',
            'waktu_pertandingan' => 'required|date_format:H:i:s',
        ];
        $messages = [
            'tgl_pertandingan.required'       => 'Tanggal pertandingan belum diisi',
            'tgl_pertandingan.date'           => 'Mohon untuk mengisi format tanggal pertandingan dengan format:d-m-Y (hari-bulan-tahun dalam satuan angka)',
            'tgl_pertandingan.date_format'    => 'Mohon untuk mengisi format tanggal pertandingan dengan format:d-m-Y (hari-bulan-tahun dalam satuan angka)',
            'tgl_pertandingan.after_or_equal' => 'Mohon untuk mengisi tanggal pertandingan minimal hari ini atau hari selanjutnya',
            'waktu_pertandingan.required'     => 'Waktu pertandingan belum diisi',
            'waktu_pertandingan.date_format'  => 'Mohon untuk mengisi format waktu pertandingan dengan format:H:i:s (jam:menit:detik dalam satuan angka)',
        ];

        $validasi = Validator::make($request->all(), $rules, $messages);
        if ($validasi->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Mohon untuk mengisi bidang yang kosong',
                'data'    => $validasi->errors(),
            ]);
        }
        else {
            $cek_jadwal_pertandingan = JadwalPertandingan::find($id);

            if(!$cek_jadwal_pertandingan) {
                $status = false;
                $msg    = 'Data untuk ID yang dicari tidak tersedia';
            }
            else {
                // mengubah format tgl pertandingan agar dapat masuk ke dalam mysql
                $time         = explode("-", $request->tgl_pertandingan);

                $jadwal_pertandingan = JadwalPertandingan::where('id', $id)
                ->first()
                ->update([
                    'tgl_pertandingan'   => $time[2]."-".$time[1]."-".$time[0],
                    'waktu_pertandingan' => $request->waktu_pertandingan,
                    'tim_tuan_id'        => isset($request->tim_tuan_id) ? $request->tim_tuan_id : null,
                    'tim_tamu_id'        => isset($request->tim_tamu_id) ? $request->tim_tamu_id : null,
                ]);

                if($jadwal_pertandingan) {
                    $status = true;
                    $msg    = 'Data jadwal pertandingan berhasil diubah';
                }
                else {
                    $status = false;
                    $msg    = 'Data jadwal pertandingan gagal diubah';
                }
            }
            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function delete($id) {
        $jadwal_pertandingan = JadwalPertandingan::findOrFail($id);
        $jadwal_pertandingan->delete();

        if ($jadwal_pertandingan) {
            $status = true;
            $msg    = 'Data jadwal pertandingan dengan id '.$id.' berhasil dihapus';
        }
        else {
            $status = false;
            $msg    = 'Data jadwal pertandingan dengan id '.$id.' gagal dihapus';
        }

        return response()->json(['success' => $status, 'message' => $msg]);
    }
}
