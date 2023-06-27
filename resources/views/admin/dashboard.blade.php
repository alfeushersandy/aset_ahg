@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $kategori_c }}</h3>

                <p>Total Kategori</p>
            </div>
            <div class="icon">
                <i class="fa fa-cube"></i>
            </div>
            <a href="{{ route('kategori.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $produk }}</h3>

                <p>Total Sparepart</p>
            </div>
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ route('barang.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $member }}</h3>

                <p>Total Aset</p>
            </div>
            <div class="icon">
                <i class="fa fa-id-card"></i>
            </div>
            <a href="{{ route('member.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <!-- ./col -->
</div>
<hr>
<h3>Berdasarkan Kategori</h3>
<div class="row">
    @foreach ($kategori as $item)
    @php
        $j_kategori = DB::table('member')->where('id_kategori', $item->id_kategori)->count();
    @endphp
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{$j_kategori}}</h3>

                <p>{{$item->nama_kategori}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-cube"></i>
            </div>
            <a href="{{ route('member.bykategori', $item->id_kategori) }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endforeach
</div>
<hr>
<h3>Kelompok Sparepart</h3>
<div class="row">
    @foreach ($sparepart as $item)
    @php
        $j_sparepart = DB::table('barang')->where('kelompok', $item->kelompok)->count();
    @endphp
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{$j_sparepart}}</h3>

                <p>{{$item->kelompok}}</p>
            </div>
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ $item->kelompok ? route('barang.kelompok', $item->kelompok) : route('barang.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endforeach
</div>
<hr>

<!-- /.row -->
<!-- Main row -->

<!-- /.row (main row) -->
<h3>Total Aset Aktif Per Lokasi</h3>
<div class="row">
    @foreach ($lokasi as $item)
    <div class="col-lg-3 col-xs-6">
        @php
            $aset = DB::table('member')->where('id_lokasi', $item->id_lokasi)->count();
        @endphp
        <!-- small box -->
        <div class="small-box bg-grey">
            <div class="inner">
                <h3>{{$aset}}</h3>
                <p><b>{{ $item->nama_lokasi }}</b></p>
            </div>
            <div class="icon">
                <i class="fa fa-id-card"></i>
            </div>
            <a href="{{ route('member.bylokasi', $item->id_lokasi) }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>    
    @endforeach
</div>
@endsection
