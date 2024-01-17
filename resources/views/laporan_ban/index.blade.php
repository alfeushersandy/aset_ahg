@extends('layouts.master')

@section('title')
    Historical Ban
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Ban</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation" class="active"> <a href="{{ route('laporan-ban.index') }}">
        <span>Daftar Ban pada Aset</span>
    </a></li>
    <li role="presentation"><a href="{{ route('laporanban.historyBan') }}">
        <span>History Pemakaian Ban</span>
    </a></li>
    <li role="presentation"><a href="{{ route('service.detail') }}">
        <span>History Per Ban</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <form action="" class="form-kode-kendaraan">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <label for="kode_produk" class="col-lg-2">kode Kendaraan</label>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" id="kode_kendaraan" name="kode_kendaraan">
                                    @foreach ($member as $member)
                                        <option value="{{ $member->id }}">{{ $member->kode_kabin }} || {{$member->nopol}}</option>
                                    @endforeach
                                </select>
                                <div style="margin-top: 25px;">
                                    <button class="btn btn-info btn-flat" type="button" id="cari">Cari</button>
                                    <button  class="btn btn-danger btn-flat" type="button" onclick="notaBesar('Laporan Service')" id="cari">Cetak PDF</button>
                                </div>
                            </div>
                        </div>
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
                        <th>No</th>
                        <th>Kode Ban</th>
                        <th>Nomor seri</th>
                        <th>Tanggal Datang</th>
                        <th>Tanggal Pakai</th>
                        <th>Kode Aset</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan.detail')
@endsection

@push('scripts')
<script>
     let table, table1;

    // // function cari_otomatis(){
        
        $(function() {
                table = $('.table-penjualan').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                autoWidth: false,
                data: [],
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'kode_ban'},
                    {data: 'nomor_seri'},
                    {data: 'tgl_beli'},
                    {data: 'tgl_pakai'},
                    {data: 'member'},
                ],
            });
        });
        
    $("#cari").on("click", function (event) {
        let id = $('#kode_kendaraan').val();
            table.ajax.url("laporan-ban/getData/"+id);
            table.ajax.reload();
        });

    $('#kode_kendaraan').select2();

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function notaBesar(title) {
        let id = $('#kode_kendaraan').val();
        let tanggal_awal = $('#tanggal_awal').val();
        let tanggal_akhir = $('#tanggal_akhir').val();
        let url = "/service/laporan/"+id+'/'+tanggal_awal+'/'+tanggal_akhir;
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