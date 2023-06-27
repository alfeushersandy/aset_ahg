<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LAPORAN PERENCANAAN PDF</title>

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
            <td width="15%">Tanggal Cetak</td>
            <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td>Cetak by</td>
            <td>: {{ Auth::user()->name ?? '' }}</td>
        </tr>
    </table>
        <h1 class="text-center">Laporan Pemakaian Service Kendaraan</h1>
        <h2 class="text-center">Periode {{date('d-m-Y', strtotime($tanggal_awal))}} - {{date('d-m-Y',strtotime($tanggal_akhir))}}</h2>
    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Aset</th>
                <th>Total Item</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permintaan as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td class="text-center">{{ $item->kode_kabin}}</td>
                    <td class="text-center">{{ $item->sum_item }}</td>
                    <td class="text-right">{{ format_uang($item->sum_harga) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><b>Total biaya</b></td>
                <td class="text-right"><b>{{ format_uang($sum) }}</b></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>