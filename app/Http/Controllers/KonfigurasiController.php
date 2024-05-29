<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function lokasikampus() {
        $lok_kampus = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

        return view('konfigurasi.lokasikampus', compact('lok_kampus'));
    }

    public function updatelokasi(Request $request) {
        $lokasi_kampus = $request->lokasi_kampus;
        $radius = $request->radius;
        $update = DB::table('konfigurasi_lokasi')->where('id', 1)->update([
            'lokasi_kampus' => $lokasi_kampus,
            'radius' => $radius
        ]);

        if($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }
}
