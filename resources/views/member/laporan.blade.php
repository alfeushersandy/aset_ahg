<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN ASET PDF</title>

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
        <h3 class="text-center">Laporan Aset Aktif Pada {{ $lokasi->nama_lokasi }}</h3>
        
    <table class="data" width="100%" style="margin-top: 15px">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kode</th>
                <th>Kategori</th>
                <th>Kode Asset</th>
                <th>Identitas Aset</th>
                <th>User / Operator</th>
            </tr>
        </thead>
        @php
            $no = 1;
        @endphp
        <tbody>
            @foreach ($member as $item)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->kode_member}}</td>
                    <td>{{$item->kategori->nama_kategori}}</td>
                    <td>{{$item->kode_kabin}}</td>
                    <td>{{$item->nopol}}</td>
                    <td>{{$item->user}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>    
</body>
</html>