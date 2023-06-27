@extends('layouts.master')

@section('title')
    Historical Service By Sparepart
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Historical Service by Sparepart</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation"> <a href="{{ route('service.history') }}">
        <span>History per item</span>
    </a></li>
    <li role="presentation"><a href="{{ route('service.allArmada') }}">
        <span>Total seluruh item</span>
    </a></li>
    <li role="presentation" class="active"><a href="{{ route('service.detail') }}">
        <span>History Per Sparepart</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <form action="" class="form-kode-kendaraan">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Sparepart</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-success btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Tanggal Awal</label>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="date" class="form-control Datepicker" name="tanggal_awal" id="tanggal_awal">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Tanggal Akhir</label>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="date" class="form-control Datepicker" name="tanggal_akhir" id="tanggal_akhir">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button  class="btn btn-danger btn-flat" type="button" onclick="notaBesar('Laporan Pemakian Sparepart')">Cetak PDF</button>
                    </div>
                    <div class="col-md-2">
                        <button  class="btn btn-info btn-flat" type="button" id="cari">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th>Tanggal</th>
                        <th>kode Permintaan</th>
                        <th>Nama Barang</th>
                        <th>Kode Aset</th>
                        <th>Identitas</th>
                        <th>Quantity</th>
                        <th>Total Harga</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('reportbybarang.produk')
@endsection

@push('scripts')
<script>
     let table, table1;

    // // function cari_otomatis(){
        
        $(function() {
                table = $('.table-penjualan').DataTable({
                order : [1, 'DESC'],
                responsive: true,
                processing: true,
                serverSide: false,
                autoWidth: false,
                data: [],
                columns: [
                    {data: 'tanggal'},
                    {data: 'kode_permintaan'},
                    {data: 'nama_barang'},
                    {data: 'kode_aset'},
                    {data: 'identitas'},
                    {data: 'jumlah'},
                    {data: 'biaya'},
                ],
            });
        });
        
    $("#cari").on("click", function (event) {
        let id = $('#id_produk').val();
        let tanggal_awal = $('#tanggal_awal').val();
        let tanggal_akhir = $('#tanggal_akhir').val();
        if(!tanggal_awal || !tanggal_akhir){
            alert('tanggal awal dan tanggal akhir periode harus diisi');
        }else if(tanggal_akhir < tanggal_awal){
            alert('tanggal awal tidak boleh lebih besar dari tanggal akhir');
        }else{
            table.ajax.url("/service/barang/"+id + '/' + tanggal_awal + '/' + tanggal_akhir);
            table.ajax.reload();
        }
        });

    table1 = $('.table-produk').DataTable();

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        hideProduk();
    }

    function notaBesar(title) {
        let id = $('#id_produk').val();
        let tanggal_awal = $('#tanggal_awal').val();
        let tanggal_akhir = $('#tanggal_akhir').val();
        let url = "/service/cetak/"+id+'/'+tanggal_awal+'/'+tanggal_akhir;
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