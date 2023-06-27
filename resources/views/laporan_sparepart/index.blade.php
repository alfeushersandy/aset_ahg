@extends('layouts.master')

@section('title')
    Sparepart Report
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Sparepart Report</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation" class="active"> <a href="{{ route('mobilisasi.report') }}">
        <span>Sparepart Report</span>
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
                            <div class="col-md-3">
                                <div class="input-group">
                                      <div class="form-group">
                                          <label for="tanggal_awal">Tanggal Awal</label>
                                          <input type="date" class="form-control datepicker" name="tanggal_awal" id="tanggal_awal">
                                      </div>
                                      <div class="form-group">
                                          <label for="tanggal_awal">Tanggal Akhir</label>
                                          <input type="date" class="form-control datepicker" name="tanggal_akhir" id="tanggal_akhir">
                                      </div>
                                    <span class="input-group-btn">
                                        <button class="btn btn-info btn-flat" type="button" id="cari">Cari</button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button  class="btn btn-danger btn-flat" type="button" onclick="notaBesar('Rekap Sparepart')" id="cari">Cetak PDF</button>
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
                        <th>Kode Sparepart</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Terpakai</th>
                        <th>Biaya Terpakai</th>
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
                processing: false,
                serverSide: true,
                autoWidth: false,
                ajax: {
                url: '{{ route('sparepart.data') }}',
                },
                columns: [
                    {data: 'kode_barang', sortable:false},
                    {data: 'nama_barang',  sortable:false},
                    {data: 'sum_item'},
                    {data: 'sum_harga'},
                ],
            });
        });
        
    $("#cari").on("click", function (event) {
        let tanggal_awal = $('#tanggal_awal').val();
        let tanggal_akhir = $('#tanggal_akhir').val();
        if(!tanggal_awal || !tanggal_akhir){
            alert('tanggal awal dan tanggal akhir periode harus diisi');
        }else if(tanggal_akhir < tanggal_awal){
            alert('tanggal awal tidak boleh lebih besar dari tanggal akhir');
        }else{
            table.ajax.url("/sparepart/" + tanggal_awal + '/' + tanggal_akhir);
            table.ajax.reload();
        }
        });

    

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
        let tanggal_awal = $('#tanggal_awal').val();
        let tanggal_akhir = $('#tanggal_akhir').val();
        if(!tanggal_awal || !tanggal_akhir){
            alert('isikan tanggal awal dan akhir periode terlebih dahulu !!!')
        }else{
            let url = "/sparepart/laporan/"+tanggal_awal+'/'+tanggal_akhir;
            popupCenter(url, title, 900, 675);
        }
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