<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Jalan</title>

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
                <br>
                {{ $setting->alamat }}
                <br>
                <br>
            </td>
            <tr>
                <td>Tanggal</td>
                <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
            </tr>
            <tr>
                <td>Kode Mobilisasi</td>
                <td>: {{ $mobilisasi->kode_mobilisasi ?? '' }}</td>
            </tr>
            <tr>
                <td>Pemohon</td>
                <td>: {{ $mobilisasi->pemohon ?? '' }}</td>
            </tr>
        </tr>
    </table>

    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>kategori</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>user</th>
                <th>Lokasi Awal</th>
                <th>Lokasi Tujuan</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{$item->member->kategori->nama_kategori}}</td>
                    <td>{{ $item->member->kode_kabin }}</td>
                    <td>{{ $item->member->nopol }}</td>
                    <td>{{$item->user}}</td>
                    <td>{{$item->lokasi1->nama_lokasi}}</td>
                    <td>{{$item->lokasi2->nama_lokasi}}</td>
                    <td>{{tanggal_indonesia($item->tanggal_awal, FALSE)}}</td>
                    <td>{{tanggal_indonesia($item->tanggal_akhir, FALSE)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td><b>Terimakasih dan Hati hati dijalan</b></td>
            <td class="text-center">
                Administrator
                <br>
                <br>
                <br>
                <br>
                {{ auth()->user()->name }}
            </td>
        </tr>
    </table>
</body>
</html>