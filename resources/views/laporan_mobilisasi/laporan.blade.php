<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN MOBILISASI PDF</title>

    <style>
        table td {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
        }
        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 5px;
        }
        table.data {
            border-collapse: collapse;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td rowspan="4" width="60%">
                <img src="{{ public_path($setting->path_logo) }}" alt="{{ $setting->path_logo }}" width="120">
                <br>
                {{ $setting->alamat }}
                <br>
            </td>
            <td width="40%">Tanggal Cetak : {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
    </table>
        @if ($lokasi == 'Semua Data')
            <h3 class="text-center">Laporan Mobilisasi Aset Seluruh Permintaan</h3>
        @else
            <h3 class="text-center">Laporan Mobilisasi Aset Pada {{ $lokasi->nama_lokasi }}</h3>
        @endif
        <h2 class="text-center">Periode {{tanggal_indonesia($tanggal_awal, false)}} - {{tanggal_indonesia($tanggal_akhir, false)}}</h2>

    @if ($lokasi == 'Semua Data' )
    <table class="data" width="100%" style="margin-top: 15px">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Mobilisasi</th>
                <th>kode Aset</th>
                <th>Identitas Aset</th>
                <th>User</th>
                <th>Tanggal Mobilisasi</th>
                <th>Tanggal Kembali</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        @php
            $no = 1;
        @endphp
        <tbody>
            @foreach ($permintaan as $item)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->kode_mobilisasi}}</td>
                    <td>{{$item->kode_kabin}}</td>
                    <td>{{$item->nopol}}</td>
                    <td>{{$item->user}}</td>
                    <td>{{tanggal_indonesia($item->tanggal_awal, false)}}</td>
                    <td>{{$item->tanggal_kembali ? tanggal_indonesia($item->tanggal_kembali, false) : '';}}</td>
                    <td>{{$item->nama_lokasi}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <table class="data" width="100%" style="margin-top: 15px">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Mobilisasi</th>
                <th>kode Aset</th>
                <th>Identitas Aset</th>
                <th>User</th>
                <th>Tanggal Mobilisasi</th>
                <th>Tanggal Kembali</th>
            </tr>
        </thead>
        @php
            $no = 1;
        @endphp
        <tbody>
            @foreach ($permintaan as $item)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->kode_mobilisasi}}</td>
                    <td>{{$item->kode_kabin}}</td>
                    <td>{{$item->nopol}}</td>
                    <td>{{$item->user}}</td>
                    <td>{{tanggal_indonesia($item->tanggal_awal, false)}}</td>
                    <td>{{$item->tanggal_kembali ? tanggal_indonesia($item->tanggal_kembali, false) : '';}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>  
    @endif
    
</body>
</html>