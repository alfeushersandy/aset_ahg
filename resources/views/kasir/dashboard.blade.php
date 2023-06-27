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
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body text-center">
                <h1>Selamat Datang</h1>
                @if (Auth::user()->level == 3)
                <h2>Anda login sebagai Koordinator Maintenance</h2>
                @elseif (Auth::user()->level == 4)
                <h2>Anda login sebagai Logistik</h2>
                @else
                <h2>Anda login sebagai User</h2>
                @endif
                <br><br>
                <a href="{{ route('service.index') }}" class="btn btn-success btn-lg">Permintaan Service</a>
                <br><br><br>
            </div>
        </div>
    </div>
</div>
<!-- /.row (main row) -->
@endsection