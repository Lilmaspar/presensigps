<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KelasController extends Controller
{
    public function index(Request $request) {
        $kls = $request->kls;
        $query = Kelas::query();
        $query->select('*');
        if (!empty($kls)) {
            $query->where('kls', 'like', '%' . $kls . '%');
        }
        $kelas = $query->get();
        return view('kelas.index', compact('kelas'));
    }

    public function store(Request $request) {
        $kode_kls = $request->kode_kls;
        $kls = $request->kls;

        try {
            $data = [
                'kode_kls' => $kode_kls,
                'kls' => $kls
            ];
            $simpan = DB::table('kelas')->insert($data);
            if ($simpan) {
                return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request) {
        $kode_kls = $request->kode_kls;
        $kelas = DB::table('kelas')->where('kode_kls', $kode_kls)->first();
        return view('kelas.edit', compact('kelas'));
    }

    public function update($kode_kls, Request $request) {
        $kode_kls = $request->kode_kls;
        $kls = $request->kls;

        try {
            $data = [
                'kode_kls' => $kode_kls,
                'kls' => $kls,
            ];
            $update = DB::table('kelas')->where('kode_kls', $kode_kls)->update($data);
            if ($update) {
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function hapus($kode_kls) {
        $hapus = DB::table('kelas')->where('kode_kls', $kode_kls)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }

    }
}
