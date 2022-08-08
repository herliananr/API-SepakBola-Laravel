<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Tim;
use Carbon\Carbon;
use Image;

class TimController extends Controller
{
    public function index(){
        $list_tim = Tim::latest()->get();
        return response([
            'success' => true,
            'message' => 'List Data Tim',
            'data'    => $list_tim
        ]);
    }

    public function store(Request $request) {
        $rules = [
            'nama'          => 'required',
            'logo'          => 'required|mimes:jpg,jpeg,png',
            'alamat_markas' => 'required',
            'tahun_berdiri' => 'integer',
            'kota_markas_id'=> 'integer',
        ];
        $messages = [
            'nama.required'          => 'Nama tim belum diisi',
            'logo.required'          => 'Logo belum diisi',
            'logo.mimes'             => 'Tipe file foto harus jpg, png, jpeg',
            'alamat_markas.required' => 'Alamat markas belum diisi',
            'tahun_berdiri.integer'  => 'Mohon untuk mengisi tahun berdiri dengan bilangan bulat',
            'kota_markas_id.integer' => 'Mohon untuk mengisi ID kota markas dengan bilangan bulat',
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
            $cek_kesamaan = Tim::select(DB::raw("TRIM(LOWER(nama)) AS nama"))
                        ->where("nama", trim(strtolower($request->nama)) )->get();

            if (!$cek_kesamaan->isEmpty()) {
                $status = false;
                $msg    = 'Nama tim sudah tersedia sebelumnya';
            }
            else {
                $tim = Tim::create([
                    'nama'          => $request->nama,
                    'tahun_berdiri' => isset($request->tahun_berdiri) ? $request->tahun_berdiri : null,
                    'alamat_markas' => $request->alamat_markas,
                    'kota_markas_id'=> isset($request->kota_markas_id) ? $request->kota_markas_id : null,
                ]);

                if($tim) {
                    $ext                = $request->logo->extension();
                    $namabaru           = "logo" . "-" . date('Y-m-d-H-i-s') . "." . $ext;
                    $tim->logo          = $namabaru;
                    $tim->save();

                    Image::make($request->logo)
                    ->save("upload/tim/logo/" . $namabaru);

                    $status = true;
                    $msg    = 'Data tim berhasil disimpan';
                }
                else {
                    $status = false;
                    $msg    = 'Data tim gagal disimpan';
                }
            }

            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function show($id){
        $cek_tim = Tim::find($id);

        if($cek_tim) {
            return response([
                'success' => true,
                'message' => 'Menampilkan detail data',
                'data'    => $cek_tim
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
            'nama'          => 'required',
            'logo'          => 'required|mimes:jpg,jpeg,png',
            'alamat_markas' => 'required',
            'tahun_berdiri' => 'integer',
            'kota_markas_id'=> 'integer',
        ];
        $messages = [
            'nama.required'          => 'Nama tim belum diisi',
            'logo.required'          => 'Logo belum diisi',
            'logo.mimes'             => 'Tipe file foto harus jpg, png, jpeg',
            'alamat_markas.required' => 'Alamat markas belum diisi',
            'tahun_berdiri.integer'  => 'Mohon untuk mengisi tahun berdiri dengan bilangan bulat',
            'kota_markas_id.integer' => 'Mohon untuk mengisi ID kota markas dengan bilangan bulat',
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
            $cek_tim = Tim::find($id);

            if(!$cek_tim) {
                $status = false;
                $msg    = 'Data untuk ID yang dicari tidak tersedia';
            }
            else {
                $tim = Tim::where('id', $id)
                ->first()
                ->update([
                    'nama'          => $request->nama,
                    'tahun_berdiri' => isset($request->tahun_berdiri) ? $request->tahun_berdiri : null,
                    'alamat_markas' => $request->alamat_markas,
                    'kota_markas_id'=> isset($request->kota_markas_id) ? $request->kota_markas_id : null,
                ]);

                if($tim) {
                    // hapus logo lama
                    if ($cek_tim->logo) {
                        unlink("upload/tim/logo/" . $cek_tim->logo);
                    }

                    // masukkan logo baru
                    $ext                = $request->logo->extension();
                    $namabaru           = "logo" . "-" . date('Y-m-d-H-i-s') . "." . $ext;
                    $cek_tim->logo      = $namabaru;
                    $cek_tim->save();

                    Image::make($request->logo)
                    ->save("upload/tim/logo/" . $namabaru);

                    $status = true;
                    $msg    = 'Data tim berhasil diubah';
                }
                else {
                    $status = false;
                    $msg    = 'Data tim gagal diubah';
                }
            }
            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function delete($id) {
        $tim = Tim::findOrFail($id);
        $tim->delete();

        if ($tim) {
            $status = true;
            $msg    = 'Data tim dengan id '.$id.' berhasil dihapus';
        }
        else {
            $status = false;
            $msg    = 'Data tim dengan id '.$id.' gagal dihapus';
        }

        return response()->json(['success' => $status, 'message' => $msg]);
    }
}
