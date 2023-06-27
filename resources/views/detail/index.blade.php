@extends('layouts.master')

@section('title')
    Detail Barang
@endsection

@section('content')
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Detail Aset
          </a>
        </h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <table>
                            <tr>
                                <td><b>Kode Aset</b></td>
                                <td>: {{$member->kode_kabin}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>Identitas Aset</b></td>
                                <td>: {{$member->nopol}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>: {{$member->kategori->nama_kategori}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Lokasi Sekarang</td>
                                <td>: {{$member->lokasi->nama_lokasi}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>User</td>
                                <td>: {{$member->user}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Lokasi Home Base</td>
                                <td>:</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
              </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingTwo">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            History Service 
          </a>
        </h4>
      </div>
      <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
        <div class="panel-body">
            @foreach ($service as $item)
            <table width="100%" style="margin-top: 15px">
                <tr>
                    <td>Kode Permintaan : {{ $item->kode_permintaan ?? '' }}</td>
                    <td>Lokasi : {{$item->nama_lokasi}}</td>
                </tr>  
                <tr>
                    <td>Tanggal Permintaan : {{tanggal_indonesia($item->tanggal, false)}}</td>
                </tr>  
                <tr>
                    <td>Keluhan : {{$item->Keluhan}}</td>
                </tr> 
            </table>
            <table class="table table-bordered" width="100%" style="margin-top: 15px">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
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
                            <td class="text-right">{{"Rp. " .  format_uang($item->subtotal)}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><b>Total biaya</b></td>
                        <td class="text-right"><b>{{"Rp. " .  format_uang($sum)}}</b></td>
                    </tr>
                </tfoot>
            </table>
            @endforeach  
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingThree">
        <h4 class="panel-title">
          <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            History Mutasi/Mobilisasi Alat
          </a>
        </h4>
      </div>
      <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
        <div class="panel-body">
            <table class="table table-bordered" width="100%" style="margin-top: 15px">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Mobilisasi</th>
                        <th>kode Aset</th>
                        <th>Identitas Aset</th>
                        <th>User</th>
                        <th>Tanggal Mobilisasi</th>
                        <th>Tanggal Kembali</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                @php
                    $no = 1;
                @endphp
                <tbody>
                    @foreach ($mobilisasi as $item)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$item->kode_mobilisasi}}</td>
                            <td>{{$item->kode_kabin}}</td>
                            <td>{{$item->nopol}}</td>
                            <td>{{$item->user}}</td>
                            <td>{{tanggal_indonesia($item->tanggal_awal, false)}}</td>
                            <td>{{$item->tanggal_kembali ? tanggal_indonesia($item->tanggal_kembali, false) : '';}}</td>
                            <td>{{$item->nama_lokasi}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
@endsection