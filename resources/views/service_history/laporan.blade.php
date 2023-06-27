<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN SERVICE PDF</title>

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
        <h1 class="text-center">Laporan Pemakaian Service Kendaraan {{ $member->kode_kabin }}</h1>
        <h2 class="text-center">Periode {{tanggal_indonesia($tanggal_awal, false)}} - {{tanggal_indonesia($tanggal_akhir, false)}}</h2>

    @foreach ($permintaan as $item)
    <table width="100%" style="margin-top: 15px">
        <tr>
            <td width="10%">Kode Permintaan : {{ $item->kode_permintaan ?? '' }}</td>
            <td width="90%">Lokasi : {{$item->nama_lokasi}}</td>
        </tr>  
        <tr>
            <td width="70%">Tanggal Permintaan : {{tanggal_indonesia($item->tanggal, false)}}</td>
        </tr>  
        <tr>
            <td width="100%">Keluhan : {{$item->Keluhan}}</td>
        </tr> 
    </table>
    <table class="data" width="100%" style="margin-top: 15px">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        @php
            $no = 1;
            $detail = DB::table('permintaan_detail')
                        ->leftjoin('barang', 'barang.id_barang', '=', 'permintaan_detail.id_barang')
                        ->where('id_permintaan', $item->id)->get();
            $sum = $detail->sum('subtotal'); 
        @endphp
        <tbody>
            @foreach ($detail as $item)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->nama_barang}}</td>
                    <td class="text-right">{{$item->jumlah}}</td>
                    <td class="text-right">{{$item->satuan}}</td>
                    <td class="text-right">{{"Rp. " .  format_uang($item->subtotal)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><b>Total biaya</b></td>
                <td class="text-right"><b>{{"Rp. " .  format_uang($sum)}}</b></td>
            </tr>
        </tfoot>
    </table>
    @endforeach    
    <h2 class="text-right">Total Biaya : {{'Rp. ' . format_uang($total)}}</h2>
</body>
</html>