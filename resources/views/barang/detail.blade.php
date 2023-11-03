@extends('layouts.master')

@section('title')
    Daftar Sparepart dengan Kelompok :  {{$kelompok}}
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Sparepart dengan Kelompok :  {{$kelompok}}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
            <button  class="btn btn-danger btn-flat" type="button" onclick="notaBesar('Aset By Lokasi')" id="cari">Cetak PDF</button>
            <button  class="btn btn-primary btn-flat" type="button" onclick="kembali()">Kembali</button>
        <div class="box">
            <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered table-detail">
                        <thead>
                            <th width="5%">No</th>
                            <th>Kode Barang</th>
                            <th>Kelompok</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Merek</th>
                            <th>Stok</th>
                        </thead>
                        @php
                            $no = 1;
                        @endphp
                        <tbody>
                            @foreach ($barang as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->kode_barang}}</td>
                                    <td>{{$item->kelompok}}</td>
                                    <td>{{$item->nama_barang}}</td>
                                    <td>{{$item->harga}}</td>
                                    <td>{{$item->merek}}</td>
                                    <td>{{$item->stok}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        let table;
        $(function(){
            table = $('.table-detail').DataTable();
        })

        function kembali()
        {
            window.location.href = "{{route('dashboard')}}";
        }

        function notaBesar(title) {

            popupCenter(url, title, 900, 675);
        }

        function popupCenter(url, title, w, h) {
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

        const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left       = (width - w) / 2 / systemZoom + dualScreenLeft
        const top        = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow  = window.open(url, title, 
        `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
        `
        );

        if (window.focus) newWindow.focus();
    }

    </script>
@endpush