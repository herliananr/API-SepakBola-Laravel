<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\KotaMarkas;
use Carbon\Carbon;
use Image;

class KotaMarkasController extends Controller
{

    public function index(){
        $list_kota_markas = KotaMarkas::latest()->get();
        return response([
            'success' => true,
            'message' => 'List Data Kota',
            'data'    => $list_kota_markas
        ]);
    }

    public function store(Request $request) {
        $rules = [
            'nama_kota'          => 'required',
        ];
        $messages = [
            'nama_kota.required' => 'Nama kota belum diisi',
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
            $cek_kesamaan = KotaMarkas::select(DB::raw("TRIM(LOWER(nama_kota)) AS nama_kota"))
                        ->where("nama_kota", trim(strtolower($request->nama_kota)) )->get();

            if (!$cek_kesamaan->isEmpty()) {
                $status = false;
                $msg    = 'Nama kota sudah tersedia sebelumnya';
            }
            else {
                $kota_markas = KotaMarkas::create([
                    'nama_kota'=> $request->nama_kota,
                ]);

                if($kota_markas) {
                    $status = true;
                    $msg    = 'Data kota markas berhasil disimpan';
                }
                else {
                    $status = false;
                    $msg    = 'Data kota markas gagal disimpan';
                }
            }

            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function show($id){
        $cek_kota_markas = KotaMarkas::find($id);

        if($cek_kota_markas) {
            return response([
                'success' => true,
                'message' => 'Menampilkan detail data',
                'data'    => $cek_kota_markas
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
            'nama_kota'          => 'required',
        ];
        $messages = [
            'nama_kota.required' => 'Nama kota belum diisi',
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
            $cek_kota_markas = KotaMarkas::find($id);

            if(!$cek_kota_markas) {
                $status = false;
                $msg    = 'Data untuk ID yang dicari tidak tersedia';
            }
            else {
                $kota_markas = KotaMarkas::where('id', $id)
                ->first()
                ->update([
                    'nama_kota'=> $request->nama_kota,
                ]);

                if($kota_markas) {
                    $status = true;
                    $msg    = 'Data kota markas berhasil diubah';
                }
                else {
                    $status = false;
                    $msg    = 'Data kota markas gagal diubah';
                }
            }
            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function delete($id) {
        $kota_markas = KotaMarkas::findOrFail($id);
        $kota_markas->delete();

        if ($kota_markas) {
            $status = true;
            $msg    = 'Data kota markas dengan id '.$id.' berhasil dihapus';
        }
        else {
            $status = false;
            $msg    = 'Data kota markas dengan id '.$id.' gagal dihapus';
        }

        return response()->json(['success' => $status, 'message' => $msg]);
    }
}
