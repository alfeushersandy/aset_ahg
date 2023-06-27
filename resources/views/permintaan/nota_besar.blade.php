<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota PDF</title>

    <style>
        table td {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
        }
        table.data td,
        th {
            border: 1px solid rgb(0, 0, 0);
            padding: 10px;
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
        .border {
            border: 1px solid rgb(0, 0, 0);
        }
    </style>
</head>
<body>
    <div class="border">
        <table class="table header" width="100%">
            <thead>
                <tr>
                    <th>PT. ARMADA HADA GRAHA</th>
                    <th>KENDARAAN & PERALATAN</th>
                    <th>{{$permintaan->kode_permintaan}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <h1 class="text-center">Nota Sparepart</h1>
    <table width="100%">
        <tr>
            <td>Tanggal</td>
            <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td>Kode Aset</td>
            <td>: {{ $permintaan->member->kode_kabin ?? '' }}</td>
        </tr>
        <tr>
            <td>Kode Permintaan</td>
            <td>: {{ $permintaan->kode_permintaan ?? '' }}</td>
        </tr>
    </table>
    <br>
    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $item->barang->kode_barang }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td class="text-right">{{ format_uang($item->biaya) }}</td>
                    <td class="text-right">{{ format_uang($item->jumlah) }}</td>
                    <td class="text-right">{{ format_uang($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><b>Total Harga</b></td>
                <td class="text-right"><b>{{ format_uang($permintaan->total_harga) }}</b></td>
            </tr>
        </tfoot>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td class="text-center">
                Logistik
                <br>
                <br>
                <br>
                <br>
                {{ auth()->user()->name }}
            </td>
            <td class="text-center">
                Mekanik
                <br>
                <br>
                <br>
                <br>
                {{ $permintaan->mekanik->nama_petugas }}
            </td>
            <td class="text-center">
                Mengetahui
                <br>
                <br>
                <br>
                <br>
                Muchamad Faqih
            </td>
        </tr>
    </table>
</body>
</html>