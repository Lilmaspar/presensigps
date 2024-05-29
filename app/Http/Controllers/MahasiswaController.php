<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index(Request $request) {

        $query = Mahasiswa::query();
        $query->select('mahasiswa.*', 'kls');
        $query->join('kelas', 'mahasiswa.kode_kls','=','kelas.kode_kls');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_mahasiswa)){
            $query->where('nama_lengkap', 'like', '%' .$request->nama_mahasiswa. '%');
        }
        if(!empty($request->kode_kls)){
            $query->where('mahasiswa.kode_kls', $request->kode_kls);
        }
        $mahasiswa = $query->paginate(10);

        $kelas = DB::table('kelas')->get();
        return view('mahasiswa.index', compact('mahasiswa', 'kelas'));
    }

    public function store(Request $request) {
        $nim = $request->nim;
        $nama_lengkap = $request->nama_lengkap;
        $kode_kls = $request->kode_kls;
        $no_hp = $request->no_hp;
        $password = Hash::make('111');
        if ($request->hasFile('foto')){
            $foto = $nim . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = null;
        }

        try {
            $data = [
                'nim' => $nim,
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'kode_kls' => $kode_kls,
                'password' => $password
            ];
            $simpan = DB::table('mahasiswa')->insert($data);
            if ($simpan) {
                if ($request->hasFile('foto')){
                    $folderPath = "public/uploads/mahasiswa/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request) {
        $nim = $request->nim;
        $kelas = DB::table('kelas')->get();
        $mahasiswa = DB::table('mahasiswa')->where('nim', $nim)->first();
        return view('mahasiswa.edit', compact('kelas', 'mahasiswa'));
    }

    public function update($nim, Request $request) {
        $nim = $request->nim;
        $nama_lengkap = $request->nama_lengkap;
        $kode_kls = $request->kode_kls;
        $no_hp = $request->no_hp;
        $password = Hash::make('111');
        $old_foto = $request->old_foto;
        if ($request->hasFile('foto')){
            $foto = $nim . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'kode_kls' => $kode_kls,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'password' => $password
            ];
            $update = DB::table('mahasiswa')->where('nim', $nim)->update($data);
            if ($update) {
                if ($request->hasFile('foto')){
                    $folderPath = "public/uploads/mahasiswa/";
                    $folderPathOld = "public/uploads/mahasiswa/" . $old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function hapus($nim) {
        $hapus = DB::table('mahasiswa')->where('nim', $nim)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }

    }
}
