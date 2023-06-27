<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN BY SPAREPART PDF</title>

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
        <h3 class="text-center">Laporan Pemakaian Sparepart</h3>
        <h2 class="text-center">Periode {{tanggal_indonesia($tanggal_awal, false)}} - {{tanggal_indonesia($tanggal_akhir, false)}}</h2>

    <table class="data" width="100%" style="margin-top: 15px">
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th>kode Permintaan</th>
                <th>Nama Sparepart</th>
                <th>Kode Aset</th>
                <th>Identitas</th>
                <th>Quantity</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $item)
                <tr>
                    <td width="15%">{{date('d-m-Y',strtotime($item->tanggal))}}</td>
                    <td>{{$item->kode_permintaan}}</td>
                    <td>{{$item->nama_barang}}</td>
                    <td>{{$item->kode_kabin}}</td>
                    <td>{{$item->nopol}}</td>
                    <td class="text-center">{{$item->jumlah}}</td>
                    <td>{{"Rp. " . format_uang($item->subtotal)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"></td>
                <td class="text-center"><b>{{$sum_total}}</b></td>
                <td class="text-right"><b>{{"Rp. " .  format_uang($sum)}}</b></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>