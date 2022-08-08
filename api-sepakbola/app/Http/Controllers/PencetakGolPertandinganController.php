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
use App\Models\ReportHasilPertandingan;
use App\Models\Pemain;
use App\Models\Tim;
use Carbon\Carbon;
use Image;

class PencetakGolPertandinganController extends Controller
{
    public function index(){
        $list_pencetak_gol_pertandingan = PencetakGolPertandingan::latest()->get();
        return response([
            'success' => true,
            'message' => 'List Data Pencetak Gol Pertandingan',
            'data'    => $list_pencetak_gol_pertandingan
        ]);
    }

    public function store(Request $request) {
        $rules = [
            'jadwal_pertandingan_id' => 'required|integer',
            'tim_id'                 => 'required|integer',
            'pemain_pencetakgol_id'  => 'required|integer',
            'waktu_gol'              => 'required|date_format:H:i:s',
        ];
        $messages = [
            'jadwal_pertandingan_id.required' => 'ID jadwal pertandingan belum diisi',
            'jadwal_pertandingan_id.integer'  => 'Mohon untuk mengisi ID jadwal pertandingan dengan bilangan bulat',
            'tim_id.required'                 => 'ID tim pertandingan belum diisi',
            'tim_id.integer'                  => 'Mohon untuk mengisi ID tim pertandingan dengan bilangan bulat',
            'pemain_pencetakgol_id.required'  => 'ID pemain pencetak gol belum diisi',
            'pemain_pencetakgol_id.integer'   => 'Mohon untuk mengisi ID pemain pencetak gol dengan bilangan bulat',
            'waktu_gol.required'              => 'Waktu gol pertandingan belum diisi',
            'waktu_gol.date_format'           => 'Mohon untuk mengisi format waktu gol pertandingan dengan format:H:i:s (jam:menit:detik dalam satuan angka)',
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
            $cek_jadwal_pertandingan = JadwalPertandingan::find($request->jadwal_pertandingan_id);
            $cek_pemain              = Pemain::find($request->pemain_pencetakgol_id);

            if (!$cek_jadwal_pertandingan) {
                $status = false;
                $msg    = 'Data untuk ID jadwal pertandingan yang dimasukkan tidak tersedia';
            }
            else if (!$cek_pemain) {
                $status = false;
                $msg    = 'Data untuk ID pemain yang dimasukkan tidak tersedia';
            }
            else if (($cek_jadwal_pertandingan->tim_tuan_id != $request->tim_id) && ($cek_jadwal_pertandingan->tim_tamu_id != $request->tim_id)) {
                $status = false;
                $msg    = 'Data untuk ID tim yang dimasukkan tidak tersedia di jadwal pertandingan';
            }
            else if ($cek_pemain->tim_id != $request->tim_id) {
                $status = false;
                $msg    = 'Data untuk ID pemain yang dimasukkan tidak terdaftar di ID tim yang dimasukkan';
            }
            else {
                // apabila data ini di dalam database belum ada, maka insert
                // namun apabila data ini sudah ada yang sama persis untuk value:jadwal_pertandingan_id, pemain_pencetakgol_id, waktu_gol_id, maka tidak usah insert
                $pencetak_gol_pertandingan = PencetakGolPertandingan::firstOrCreate([
                    'jadwal_pertandingan_id' => $request->jadwal_pertandingan_id,
                    'tim_id'                 => $request->tim_id,
                    'pemain_pencetakgol_id'  => $request->pemain_pencetakgol_id,
                    'waktu_gol'              => $request->waktu_gol,
                ]);

                if($pencetak_gol_pertandingan) {
                    $this->autoUpdateHasilPertandingan($request->jadwal_pertandingan_id);
                    $this->autoUpdateReportHasilPertandingan($request->jadwal_pertandingan_id);
                    $status = true;
                    $msg    = 'Data pemain pencetak gol berhasil disimpan';
                }
                else {
                    $status = false;
                    $msg    = 'Data pemain pencetak gol gagal disimpan';
                }
            }

            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function show($id){
        $cek_pencetak_gol_pertandingan = PencetakGolPertandingan::find($id);

        if($cek_pencetak_gol_pertandingan) {
            return response([
                'success' => true,
                'message' => 'Menampilkan detail data',
                'data'    => $cek_pencetak_gol_pertandingan
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
            'jadwal_pertandingan_id' => 'required|integer',
            'tim_id'                 => 'required|integer',
            'pemain_pencetakgol_id'  => 'required|integer',
            'waktu_gol'              => 'required|date_format:H:i:s',
        ];
        $messages = [
            'jadwal_pertandingan_id.required' => 'ID jadwal pertandingan belum diisi',
            'jadwal_pertandingan_id.integer'  => 'Mohon untuk mengisi ID jadwal pertandingan dengan bilangan bulat',
            'tim_id.required'                 => 'ID tim pertandingan belum diisi',
            'tim_id.integer'                  => 'Mohon untuk mengisi ID tim pertandingan dengan bilangan bulat',
            'pemain_pencetakgol_id.required'  => 'ID pemain pencetak gol belum diisi',
            'pemain_pencetakgol_id.integer'   => 'Mohon untuk mengisi ID pemain pencetak gol dengan bilangan bulat',
            'waktu_gol.required'              => 'Waktu gol pertandingan belum diisi',
            'waktu_gol.date_format'           => 'Mohon untuk mengisi format waktu gol pertandingan dengan format:H:i:s (jam:menit:detik dalam satuan angka)',
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
            $cek_pencetak_gol_pertandingan = PencetakGolPertandingan::find($id);

            if(!$cek_pencetak_gol_pertandingan) {
                $status = false;
                $msg    = 'Data untuk ID yang dicari tidak tersedia';
            }
            else {
                $cek_jadwal_pertandingan = JadwalPertandingan::find($request->jadwal_pertandingan_id);
                $cek_pemain              = Pemain::find($request->pemain_pencetakgol_id);

                if (!$cek_jadwal_pertandingan) {
                    $status = false;
                    $msg    = 'Data untuk ID jadwal pertandingan yang dimasukkan tidak tersedia';
                }
                else if (!$cek_pemain) {
                    $status = false;
                    $msg    = 'Data untuk ID pemain yang dimasukkan tidak tersedia';
                }
                else if (($cek_jadwal_pertandingan->tim_tuan_id != $request->tim_id) && ($cek_jadwal_pertandingan->tim_tamu_id != $request->tim_id)) {
                    $status = false;
                    $msg    = 'Data untuk ID tim yang dimasukkan tidak tersedia di jadwal pertandingan';
                }
                else if ($cek_pemain->tim_id != $request->tim_id) {
                    $status = false;
                    $msg    = 'Data untuk ID pemain yang dimasukkan tidak terdaftar di ID tim yang dimasukkan';
                }
                else {
                    $pencetak_gol_pertandingan = PencetakGolPertandingan::where('id', $id)
                    ->first()
                    ->update([
                        'jadwal_pertandingan_id' => $request->jadwal_pertandingan_id,
                        'tim_id'                 => $request->tim_id,
                        'pemain_pencetakgol_id'  => $request->pemain_pencetakgol_id,
                        'waktu_gol'              => $request->waktu_gol,
                    ]);

                    if($pencetak_gol_pertandingan) {
                        $this->autoUpdateHasilPertandingan($request->jadwal_pertandingan_id);
                        $this->autoUpdateReportHasilPertandingan($request->jadwal_pertandingan_id);
                        $status = true;
                        $msg    = 'Data pemain pencetak gol berhasil diubah';
                    }
                    else {
                        $status = false;
                        $msg    = 'Data pemain pencetak gol gagal diubah';
                    }
                }
            }
            return response()->json(['success' => $status, 'message' => $msg]);
        }
    }

    public function delete($id) {
        $pencetak_gol_pertandingan = PencetakGolPertandingan::findOrFail($id);
        $pencetak_gol_pertandingan->delete();

        if ($pencetak_gol_pertandingan) {
            $this->autoUpdateHasilPertandingan($pencetak_gol_pertandingan->jadwal_pertandingan_id);
            $this->autoUpdateReportHasilPertandingan($pencetak_gol_pertandingan->jadwal_pertandingan_id);
            $status = true;
            $msg    = 'Data pencetak gol dengan id '.$id.' berhasil dihapus';
        }
        else {
            $status = false;
            $msg    = 'Data pencetak gol dengan id '.$id.' gagal dihapus';
        }

        return response()->json(['success' => $status, 'message' => $msg]);
    }

    public function autoUpdateHasilPertandingan($jadwal_pertandingan_id) {
        $cek_jadwal_pertandingan        = JadwalPertandingan::find($jadwal_pertandingan_id);
        $cek_pencetak_gol_pertandingan  = PencetakGolPertandingan::where('jadwal_pertandingan_id', $jadwal_pertandingan_id)->get();
        $cek_hasil_pertandingan         = HasilPertandingan::where('jadwal_pertandingan_id', $jadwal_pertandingan_id)->first();

        $total_skor_tim_tuan = 0;
        $total_skor_tim_tamu = 0;

        if (!$cek_pencetak_gol_pertandingan->isEmpty()) {
            foreach ($cek_pencetak_gol_pertandingan as $pencetak_gol) {
                if ($pencetak_gol->tim_id == $cek_jadwal_pertandingan->tim_tuan_id) {
                    $total_skor_tim_tuan += 1;
                }
                else if ($pencetak_gol->tim_id == $cek_jadwal_pertandingan->tim_tamu_id) {
                    $total_skor_tim_tamu += 1;
                }
            }
        }

        if (isset($cek_hasil_pertandingan->id)) {
            $hasil_pertandingan = HasilPertandingan::where('id', $cek_hasil_pertandingan->id)
                                    ->first()
                                    ->update([
                                        'total_skor_akhir_tim_tuan'  => $total_skor_tim_tuan,
                                        'total_skor_akhir_tim_tamu'  => $total_skor_tim_tamu,
                                    ]);
        }
        else {
            $hasil_pertandingan = HasilPertandingan::create([
                                        'jadwal_pertandingan_id'     => $jadwal_pertandingan_id,
                                        'total_skor_akhir_tim_tuan'  => $total_skor_tim_tuan,
                                        'total_skor_akhir_tim_tamu'  => $total_skor_tim_tamu,
                                    ]);
        }
    }

    function sorting($a, $b)
    {
        if ($a == $b) return 0;
            return ($a > $b) ? -1 : 1;
    }

    public function autoUpdateReportHasilPertandingan($jadwal_pertandingan_id) {
        $cek_jadwal_pertandingan        = JadwalPertandingan::find($jadwal_pertandingan_id);
        $cek_pencetak_gol_pertandingan  = PencetakGolPertandingan::where('jadwal_pertandingan_id', $jadwal_pertandingan_id)->get();
        $cek_hasil_pertandingan         = HasilPertandingan::where('jadwal_pertandingan_id', $jadwal_pertandingan_id)->first();
        $cek_report_hasil_pertandingan  = ReportHasilPertandingan::where('jadwal_pertandingan_id', $jadwal_pertandingan_id)->first();
        $cek_nama_tim_tuan              = Tim::find($cek_jadwal_pertandingan->tim_tuan_id);
        $cek_nama_tim_tamu              = Tim::find($cek_jadwal_pertandingan->tim_tamu_id);

        $total_skor_tim_tuan            = 0;
        $total_skor_tim_tamu            = 0;
        $status_akhir_pertandingan      = "";
        $trace_pecetak_skor             = [];
        $id_pencetak_skor;

        // hitung hasil cetak skor tim tuan dan tim rumah
        if (!$cek_pencetak_gol_pertandingan->isEmpty()) {
            foreach ($cek_pencetak_gol_pertandingan as $pencetak_gol) {
                if ($pencetak_gol->tim_id == $cek_jadwal_pertandingan->tim_tuan_id) {
                    $total_skor_tim_tuan += 1;
                }
                else if ($pencetak_gol->tim_id == $cek_jadwal_pertandingan->tim_tamu_id) {
                    $total_skor_tim_tamu += 1;
                }
            }
        }

        // cek status akhir_pertandingan
        if ($total_skor_tim_tuan > $total_skor_tim_tamu) {
            $status_akhir_pertandingan = "Tim Tuan Menang";
        }
        else if ($total_skor_tim_tuan < $total_skor_tim_tamu) {
            $status_akhir_pertandingan = "Tim Tamu Menang";
        }
        else {
            $status_akhir_pertandingan = "Draw";
        }

        // cari pemain pencetak skor terbanyak
        $pemain_pencetak_skor = DB::table('pencetak_gol_pertandingan')
                                ->select(DB::raw('pemain_pencetakgol_id, COUNT(pemain_pencetakgol_id) AS pemain_pencetakgol_terbanyak_id'))
                                ->where('jadwal_pertandingan_id', $jadwal_pertandingan_id)
                                ->groupBy('pemain_pencetakgol_id')
                                ->get();

        foreach ($pemain_pencetak_skor as $pps) {
            $trace_pecetak_skor[$pps->pemain_pencetakgol_id] = $pps->pemain_pencetakgol_terbanyak_id;
        }

        // sort descending data jumlah gol dari setiap ID pemain
        uasort($trace_pecetak_skor, array($this, "sorting"));

        // ambil nilai key dari variabel bersangkutan
        $id_pencetak_skor = array_keys($trace_pecetak_skor);

        // ambil nama pemain
        $cek_nama_pemain  = Pemain::find($id_pencetak_skor[0]);

        // menghitung akumulasi kemenangan tim tuan
        $akumulasi_kemenangan_tim_tuan  = DB::table('pencetak_gol_pertandingan')
                                        ->select(DB::raw('*, COUNT(tim_id) AS akumulasi_gol'))
                                        ->where('tim_id', $cek_jadwal_pertandingan->tim_tuan_id)
                                        ->groupBy('tim_id')
                                        ->first();

        // menghitung akumulasi kemenangan tim tamu
        $akumulasi_kemenangan_tim_tamu  = DB::table('pencetak_gol_pertandingan')
                                        ->select(DB::raw('*, COUNT(tim_id) AS akumulasi_gol'))
                                        ->where('tim_id', $cek_jadwal_pertandingan->tim_tamu_id)
                                        ->groupBy('tim_id')
                                        ->first();

        // apabila hasil pertandingan sudah tersedia, baru bisa insert ke table report_hasil_pertandingan
        // apabila datanya sudah ada, maka update ke table report_hasil_pertandingan
        if (isset($cek_hasil_pertandingan->id)) {
            if (isset($cek_report_hasil_pertandingan->id)) {
                $report_hasil_pertandingan = ReportHasilPertandingan::where('id', $cek_report_hasil_pertandingan->id)
                                            ->first()
                                            ->update([
                                            'tim_tuan_id'                         => $cek_jadwal_pertandingan->tim_tuan_id,
                                            'nama_tim_tuan'                       => $cek_nama_tim_tuan->nama,
                                            'tim_tamu_id'                         => $cek_jadwal_pertandingan->tim_tamu_id,
                                            'nama_tim_tamu'                       => $cek_nama_tim_tamu->nama,
                                            'total_skor_akhir_tim_tuan'           => $total_skor_tim_tuan,
                                            'total_skor_akhir_tim_tamu'           => $total_skor_tim_tamu,
                                            'status_akhir_pertandingan'           => $status_akhir_pertandingan,
                                            'id_pemain_pencetakgol_terbanyak'     => $id_pencetak_skor[0],
                                            'nama_pemain_pencetakgol_terbanyak'   => $cek_nama_pemain->nama,
                                            'akumulasi_total_kemenangan_tim_tuan' => $akumulasi_kemenangan_tim_tuan->akumulasi_gol ?? 0,
                                            'akumulasi_total_kemenangan_tim_tamu' => $akumulasi_kemenangan_tim_tamu->akumulasi_gol ?? 0,
                                            ]);
            }
            else {
                $report_hasil_pertandingan = ReportHasilPertandingan::create([
                                            'jadwal_pertandingan_id'              => $jadwal_pertandingan_id,
                                            'tim_tuan_id'                         => $cek_jadwal_pertandingan->tim_tuan_id,
                                            'nama_tim_tuan'                       => $cek_nama_tim_tuan->nama,
                                            'tim_tamu_id'                         => $cek_jadwal_pertandingan->tim_tamu_id,
                                            'nama_tim_tamu'                       => $cek_nama_tim_tamu->nama,
                                            'total_skor_akhir_tim_tuan'           => $total_skor_tim_tuan,
                                            'total_skor_akhir_tim_tamu'           => $total_skor_tim_tamu,
                                            'status_akhir_pertandingan'           => $status_akhir_pertandingan,
                                            'id_pemain_pencetakgol_terbanyak'     => $id_pencetak_skor[0],
                                            'nama_pemain_pencetakgol_terbanyak'   => $cek_nama_pemain->nama,
                                            'akumulasi_total_kemenangan_tim_tuan' => $akumulasi_kemenangan_tim_tuan->akumulasi_gol ?? 0,
                                            'akumulasi_total_kemenangan_tim_tamu' => $akumulasi_kemenangan_tim_tamu->akumulasi_gol ?? 0,
                                            ]);
            }
        }
    }
}
