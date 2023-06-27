@extends('layouts.master')

@section('title')
    Daftar Aset di {{$lokasi->nama_lokasi}}
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Aset di {{$lokasi->nama_lokasi}}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
            <button  class="btn btn-danger btn-flat" type="button" onclick="notaBesar('Aset By Lokasi')" id="cari">Cetak PDF</button>
            <button  class="btn btn-primary btn-flat" type="button" onclick="kembali()">Kembali</button>
        <div class="box">
            <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Kode Asset</th>
                            <th>Identitas Aset</th>
                            <th>User / Operator</th>
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
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        let table;
        $(function(){
            table = $('.table').DataTable();
        })

        function kembali()
        {
            window.location.href = "{{route('dashboard')}}";
        }

        function notaBesar(title) {
            let url = "{{route('member.cetak_lokasi', $lokasi->id_lokasi)}}";
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