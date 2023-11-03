<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Service</title>

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
    <h1 class="text-center">FORM PERMINTAAN SERVICE</h1>

    <table width="60%">
    <tr>
            <td>Kode Permintaan</td>
            <td>: {{$permintaan->kode_permintaan}}</td>
        </tr>
        <tr>
            <td>Tanggal Permohonan</td>
            <td>: {{tanggal_indonesia($permintaan->tanggal)}}</td>
        </tr>
        <tr>
            <td>Nama Pemohon</td>
            <td>: {{ $permintaan->user }}</td>
        </tr>
        <tr>
            <td>Unit</td>
            <td>: {{ $permintaan->lokasi->nama_lokasi ?? '' }}</td>
        </tr>
    </table>
    <hr>
    <table width="52%">
        <tr>
            <td>Kategori Alat</td>
            <td>: {{ $permintaan->member->kategori->nama_kategori }}</td>
        </tr>
        <tr>
            <td>No. Lambung</td>
            <td>: {{ $permintaan->member->kode_kabin ?? '' }}</td>
        </tr>
        <tr>
            <td>Nopol/Identitas</td>
            <td>: {{ $permintaan->member->nopol ?? '' }}</td>
        </tr>
        <tr>
            <td>KM</td>
            <td>: {{ $permintaan->km ?? '' }}</td>
        </tr>
    </table>
    <hr>
    <table width="50%">
        <tr>
            <td>Keluhan : </td>
        </tr>
    </table>
    <table width="50%" style="margin-left: 190px; margin-top:-50px">
    @foreach ($trimed as $item)
        <tr>
            <td>{{$loop->iteration}}.</td>
            <td>{{$item}}</td>
        </tr>
    @endforeach
    </table>
    <hr>
    <br>
    <table width="100%">
            <tr>
                <td class="text-center">
                    Admin
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
                    Menyetujui
                    <br>
                    <br>
                    <br>
                    <br>             
                Muchamad Faqih
                </td>
                <td class="text-center">
                    Mengetahui
                    <br>
                    <br>
                    <br>
                    <br>
                Agung Sedayu
                </td>
            </tr>
    </table>
</body>
</html>