@extends('layouts.master')

@section('title')
    Daftar Barang Keluar
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Barang Keluar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> List Service </button>
                <button onclick="addFormsparepart()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Permintaan Sparepart </button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">
                            <input type="checkbox" name="select_all" id="select_all">
                        </th>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Kode Permintaan</th>
                        <th>Kode Kendaraan</th>
                        <th>Unit / Lokasi</th>
                        <th>User / Operator</th>
                        <th>Keluhan</th>
                        <th>Mekanik</th>
                        <th>Status</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('barangkeluar.form')
@endsection

@push('scripts')
<script>
    let table, table1, table2

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('service.data') }}',
                data:{
                    'csrf_token':$('meta[name=csrf_token]').attr("content")
                }
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'kode_permintaan'},
                {data: 'kode_kabin'},
                {data: 'nama_lokasi'},
                {data: 'user'},
                {data: 'Keluhan'},
                {data: 'nama_petugas'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('.table-supplier').DataTable();
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_beli'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        })
    });

    function addForm() {
        $('#modal-form').modal('show');
    }

    function addFormsparepart() {
        $('#modal-sparepart').modal('show');
    }

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
</script>
@endpush