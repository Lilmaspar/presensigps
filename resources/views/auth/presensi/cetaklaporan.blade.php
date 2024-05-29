<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
  <style>@page { size: A4 }

    #title{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 17px;
        font-weight: bold;
    }

    .tabeldatamahasiswa{
        margin-top: 40px;
    }

    .tabeldatamahasiswa tr td {
        padding: 3px;
    }

    .tabelpresensi {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .tabelpresensi tr th {
        border: 1px solid #000000;
        padding: 5px;
        background-color: #e7e7e7;
    }

    .tabelpresensi tr td {
        border: 1px solid #000000;
        padding: 5px;
        font-size: 14px;
        text-align: center;
    }

    .foto {
        width: 80px;
        height: 60px;
    }

    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
@php
    function selisih($jam_masuk, $jam_keluar){
        list($h, $m, $s) = explode(":", $jam_masuk);
        $dtAwal = mktime($h, $m, $s, "1", "1", "1");
        list($h, $m, $s) = explode(":", $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
        $dtSelisih = $dtAkhir - $dtAwal;
        $totalmenit = $dtSelisih / 60;
        $jam = explode(".", $totalmenit / 60);
        $sisamenit = ($totalmenit / 60) - $jam[0];
        $sisamenit2 = $sisamenit * 60;
        $jml_jam = $jam[0];

        return $jml_jam . ":" . round($sisamenit2);
    }
@endphp

  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">

    <table style="width: 100%">
        <tr>
            <td style="width: 30px">
                <img src="{{ asset('assets/img/PNUP.png') }}" width="90" height="100" alt="">
            </td>
            <td>
                <span id="title">
                    LAPORAN PRESENSI MAHASISWA <br>
                    PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}<br>
                    POLITEKNIK NEGERI UJUNG PANDANG <br>
                </span>
                <span><i>Jl. Politeknik, Tamalanrea Indah, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245</i></span>
            </td>
        </tr>
    </table>
    <table class="tabeldatamahasiswa">
        <tr>
            <td rowspan="7">
                @php
                    $path = Storage::url('uploads/mahasiswa/'.$mahasiswa->foto);
                @endphp
                <img src="{{ url($path) }}" alt="" width="140" height="200">
            </td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td>Nama Mahasiswa</td>
            <td>:</td>
            <td>{{ $mahasiswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $mahasiswa->kls }}</td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td>:</td>
            <td>{{ $mahasiswa->jurusan }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td>{{ $mahasiswa->prodi }}</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>:</td>
            <td>{{ $mahasiswa->no_hp }}</td>
        </tr>
    </table>
    <table class="tabelpresensi">
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Foto Masuk</th>
            <th>Jam Pulang</th>
            <th>Foto Pulang</th>
            <th>Keterangan</th>
        </tr>
        @foreach ($presensi as $d)
        @php
            $path_in = Storage::url('uploads/absensi/'.$d->foto_in);
            $path_out = Storage::url('uploads/absensi/'.$d->foto_out);
            $jamterlambat = selisih('07:00:00',$d->jam_in);
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date("d-m-Y",strtotime($d->tgl_presensi)) }}</td>
            <td>{{ $d->jam_in }}</td>
            <td><img src="{{ url($path_in) }}" alt="" class="foto"></td>
            <td>{!! $d->jam_out !== null ? $d->jam_out : '<span class="badge bg-danger">Belum Absen</span>' !!}</td>
            <td>
                @if ($d->jam_out !== null)
                    <img src="{{ url($path_out) }}" alt="" class="foto"></td>
                @else
                    <img src="{{ asset('assets/img/nophoto.png') }}" alt="" width="80px" height="60px">
                @endif
            <td>
                @if ($d->jam_in > '07:00')
                    Terlambat {{ $jamterlambat }}
                @else
                    Tepat Waktu
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    <table width="100%" style="margin-top: 100px">
        <tr>
            <td colspan="2" style="text-align: right">Makassar, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align:bottom" height="100px">
                <u><b>Meylanie Olivia</b></u><br>
                <i><b>
                    Kepala Program Studi <br>
                    Teknik Komputer dan Jaringan <br>
                </b></i>
            </td>
            <td style="text-align: center; vertical-align:bottom">
                <u><b>Iin Karmila</b></u><br>
                <i><b>
                    Ketua Jurusan <br>
                    Teknik Informatika dan Komputer <br>
                </b></i>
            </td>
        </tr>
    </table>
  </section>

</body>

</html>
