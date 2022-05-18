@extends('layouts.master')

@section('title')
    Historical Service
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Historical Service</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th>No</th>
                        <th>Unit Aset</th>
                        <th>Total Item</th>
                        <th>Total Biaya</th>
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
                ajax: {
                url: '{{ route('service.allUnit') }}',
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'kode_member'},
                    {data: 'jumlah'},
                    {data: 'biaya'},
                ],
            });
        });
        
    $("#cari").on("click", function (event) {
        let id = $('#kode_kendaraan').val();
        table.ajax.url("data/"+id);
        table.ajax.reload();
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
        let id = $('#kode_kendaraan').val();
        let url = "/service/laporan/"+id;
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