@extends('layouts.master')

@section('title')
    Detail Aset
@endsection

@section('content')
<div class="accordion" id="accordionExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Detail Aset
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
        <div class="accordion-body">
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
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          History Service
        </button>
      </h2>
      <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
        <div class="accordion-body">
        @foreach ($service as $item)
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
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          History Transfer Aset
        </button>
      </h2>
      <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <table class="data" width="100%" style="margin-top: 15px">
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