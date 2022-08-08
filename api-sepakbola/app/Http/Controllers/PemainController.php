<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\LogPemainTim;
use App\Models\Pemain;
use App\Models\Tim;
use Carbon\Carbon;
use Image;

class PemainController extends Controller
{
    public function index(){
        $list_pemain = Pemain::latest()->get();
        return response([
            'success' => true,
            'message' => 'List Data Pemain',
            'data'    => $list_pemain
        ]);
    }

    public function store(Request $request) {
        $rules = [
            'nama'           => 'required',
            'tinggi_badan'   => 'required|numeric|max:250',
            'berat_badan'    => 'required|numeric|max:400',
            'tim_id'         => 'required|integer',
            'posisi'         => 'required',
            'nomor_punggung' => 'required|integer',
        ];
        $messages = [
            'nama.required'           => 'Nama pemain belum diisi',
            'tinggi_badan.required'   => 'Tinggi badan belum diisi',
            'tinggi_badan.numeric'    => 'Mohon untuk mengisi tinggi badan dengan bilangan',
            'tinggi_badan.max'        => 'Tinggi badan yang anda masukkan tidak valid. Mohon untuk mengisi tinggi badan dengan bilangan kurang dari 251',
            'berat_badan.required'    => 'Berat badan belum diisi',
            'berat_badan.numeric'     => 'Mohon untuk mengisi berat badan dengan bilangan',
            'berat_badan.max'         => 'Berat badan yang anda masukkan tidak valid. Mohon untuk mengisi berat badan dengan bilangan kurang dari 401',
            'tim_id.required'         => 'ID tim belum diisi',
            'tim_id.integer'          => 'Mohon untuk mengisi ID tim dengan bilangan bulat',
            'posisi.required'         => 'Posisi belum diisi',
            'nomor_punggung.required' => 'Nomor punggung belum diisi',
            'nomor_punggung.integer'  => 'Mohon untuk mengisi nomor punggung dengan bilangan bulat',
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
            $cek_kesamaan = Pemain::select(DB::raw("TRIM(LOWER(nama)) AS nama"))
                        ->where("nama", trim(strtolower($request->nama)) )->get();

            if (!$cek_kesamaan->isEmpty()) {
                $status = false;
                $msg    = 'Nama pemain sudah tersedia sebelumnya';
            }
            else {
                $tim             = Tim::find($request->tim_id);
                $cek_no_punggung = Pemain::where("tim_id", $request->tim_id)->where("nomor_punggung", $request->nomor_punggung)->get();

                if(!$tim) {
                    $status = false;
                    $msg    = 'ID tim tidak ada di dalam database';
                }
                else if (count($cek_no_punggung) > 0) {
                    $status = false;
                    $msg    = 'Nomor punggung untuk tim dengan id '.$request->tim_id.' sudah tersedia sebelumnya';
                }
                else {
                    // Disini menggunakan DB::beginTransaction(), DB::commit(), DB::rollBack() agar punya kendali penuh untuk penyimpanan data ke dalam database.
                    // Apabila ada satu kode yang error, dengan metode ini, maka semua transaksi dibatalkan sehingga tidak terjadi data yang hanya masuk sebagian ke dalam database
                    DB::beginTransaction();
                    try {
                        $pemain = Pemain::create([
                            'nama'           => $request->nama,
                            'tinggi_badan'   => $request->tinggi_badan,
                            'berat_badan'    => $request->berat_badan,
                            'tim_id'         => $request->tim_id,
                            'posisi'         => $request->posisi,
                            'nomor_punggung' => $request->nomor_punggung,
                        ]);

                        $pemain->logPemainTims()->create([
                            'tim_id'         => $request->tim_id,
                            'posisi'         => $request->posisi,
                            'nomor_punggung' => $request->nomor_punggung,
                        ]);

                        DB::commit();

                        $status = true;
                        $msg    = 'Data pemain berhasil disimpan';
                    }
                    catch (\Throwable $th) {
                        DB::rollBack();
                        $status = false;
                        $msg    = 'Data pemain gagal disimpan'.$th;
                    }
                }
            }

            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function show($id){
        $cek_pemain     = Pemain::find($id);
        $log_pemain_tim = "";
        if($cek_pemain) {
            $log_pemain_tim = ($cek_pemain->logPemainTims) ? $cek_pemain->logPemainTims : null;
        }
        $data_pemain    = array("pemain" =>$cek_pemain, "histori_pemain" =>$log_pemain_tim);
        if($cek_pemain) {
            return response([
                'success' => true,
                'message' => 'Menampilkan detail data',
                'data'    => $data_pemain
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
            'nama'           => 'required',
            'tinggi_badan'   => 'required|numeric|max:250',
            'berat_badan'    => 'required|numeric|max:400',
            'tim_id'         => 'required|integer',
            'posisi'         => 'required',
            'nomor_punggung' => 'required|integer',
        ];
        $messages = [
            'nama.required'           => 'Nama pemain belum diisi',
            'tinggi_badan.required'   => 'Tinggi badan belum diisi',
            'tinggi_badan.numeric'    => 'Mohon untuk mengisi tinggi badan dengan bilangan',
            'tinggi_badan.max'        => 'Tinggi badan yang anda masukkan tidak valid. Mohon untuk mengisi tinggi badan dengan bilangan kurang dari 251',
            'berat_badan.required'    => 'Berat badan belum diisi',
            'berat_badan.numeric'     => 'Mohon untuk mengisi berat badan dengan bilangan',
            'berat_badan.max'         => 'Berat badan yang anda masukkan tidak valid. Mohon untuk mengisi berat badan dengan bilangan kurang dari 401',
            'tim_id.required'         => 'ID tim belum diisi',
            'tim_id.integer'          => 'Mohon untuk mengisi ID tim dengan bilangan bulat',
            'posisi.required'         => 'Posisi belum diisi',
            'nomor_punggung.required' => 'Nomor punggung belum diisi',
            'nomor_punggung.integer'  => 'Mohon untuk mengisi nomor punggung dengan bilangan bulat',
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
            $cek_pemain = Pemain::find($id);

            if(!$cek_pemain) {
                $status = false;
                $msg    = 'Data untuk ID yang dicari tidak tersedia';
            }
            else {
                $tim = Tim::find($request->tim_id);

                if(!$tim) {
                    $status = false;
                    $msg    = 'ID tim tidak ada di dalam database';
                }
                else {
                    DB::beginTransaction();
                    try {
                        // apabila ada perubahan tim, berarti pemain ini pindah ke tim baru.
                        // apabila ada perubahan di posisi dan nomor punggung, berarti masih di tim yang sama.
                        // histori tentang pemain ini sudah bermain di tim apa saja atau adanya perubahan posisi atau nomor punggung akan tercatat di table log_pemain_tim
                        $tim_id_before = Pemain::where('id', $id)->first();
                        if(($request->tim_id != $tim_id_before->tim_id) || ($request->posisi != $tim_id_before->posisi) || ($request->nomor_punggung != $tim_id_before->nomor_punggung)) {
                            $cek_pemain->logPemainTims()->create([
                                'tim_id'         => $request->tim_id,
                                'posisi'         => $request->posisi,
                                'nomor_punggung' => $request->nomor_punggung,
                            ]);
                        }

                        Pemain::where('id', $id)
                        ->first()
                        ->update([
                            'nama'           => $request->nama,
                            'tinggi_badan'   => $request->tinggi_badan,
                            'berat_badan'    => $request->berat_badan,
                            'tim_id'         => $request->tim_id,
                            'posisi'         => $request->posisi,
                            'nomor_punggung' => $request->nomor_punggung,
                        ]);

                        DB::commit();

                        $status = true;
                        $msg    = 'Data pemain berhasil diubah';
                    }
                    catch (\Throwable $th) {
                        DB::rollBack();
                        $status = false;
                        $msg    = 'Data pemain gagal diubah'.$th;
                    }
                }
            }
            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function delete($id) {
        $pemain = Pemain::findOrFail($id);

        DB::beginTransaction();
        try {
            if (!empty($pemain->logPemainTims)) {
                LogPemainTim::where('pemain_id',$id)->delete();
            }
            $pemain->delete();

            DB::commit();
            $status = true;
            $msg    = 'Data pemain dengan id '.$id.' berhasil dihapus';
        }
        catch (\Throwable $th) {
            DB::rollBack();
            $status = false;
            $msg    = 'Data pemain dengan id '.$id.' gagal dihapus';
        }

        return response()->json(['success' => $status, 'message' => $msg]);
    }
}
