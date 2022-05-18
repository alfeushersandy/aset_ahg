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
            <td width="15%">Tanggal Cetak</td>
            <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td>Kode Kendaraan</td>
            <td>: {{ $member->kode_kabin ?? '' }}</td>
        </tr>
    </table>
        <h1 class="text-center">Laporan Pemakaian Service Kendaraan {{ $member->kode_kabin }}</h1>
    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Permintaan</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permintaan as $key => $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->kode_permintaan }}</td>
                    <td c>{{ $item->nama_barang }}</td>
                    <td class="text-right">{{ $item->jumlah }}</td>
                    <td class="text-right">{{ format_uang($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><b>Total biaya</b></td>
                <td class="text-right"><b>{{ format_uang($sum) }}</b></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>