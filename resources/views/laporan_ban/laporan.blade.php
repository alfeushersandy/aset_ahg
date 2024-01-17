<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN PEMAKAIAN BAN</title>

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
        <h1 class="text-center">Laporan Pemakaian Ban</h1>
    <table class="data" width="100%" style="margin-top: 15px">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Sparepart</th>
                <th>Nama Barang</th>
                <th>Jumlah Terpakai</th>
                <th>Satuan</th>
                <th>Biaya Terpakai</th>
            </tr>
        </thead>
        @php
            $no = 1;
        @endphp
        <tbody>
            @foreach ($permintaan as $item)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->kode_barang}}</td>
                    <td>{{$item->nama_barang}}</td>
                    <td>{{$item->sum_item}}</td>
                    <td>{{$item->satuan}}</td>
                    <td>{{"Rp. " . format_uang($item->sum_harga)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>    
</body>
</html>