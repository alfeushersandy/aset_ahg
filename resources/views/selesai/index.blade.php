@extends('layouts.master')

@section('title')
    Daftar Service Selesai
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Service Selesai</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation" > <a href="{{ route('permintaan.index') }}">
        <span>Permintaan Service</span>
    </a></li>
    <li role="presentation"><a href="{{ route('pemeriksaan.index') }}">
        <span>pemeriksaan Service</span>
    </a></li>
    <li role="presentation"><a href="{{ route('service.index') }}">
        <span>Service On Progress</span>
    </a></li>
    <li role="presentation" class="active"><a href="{{ route('service.selesai') }}">
        <span>Service Selesai</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Kode Permintaan</th>
                        <th>Kode Kendaraan</th>
                        <th>Unit / Lokasi</th>
                        <th>User / Operator</th>
                        <th>Keluhan</th>
                        <th>Mekanik</th>
                        <th>Status</th>
                        <th>Waktu Selesai</th>
                    </thead>
                    <tbody>
                        @foreach ($permintaan as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>{{ $item->kode_permintaan }}</td>
                                <td>{{ $item->kode_kabin }}</td>
                                <td>{{ $item->nama_lokasi }}</td>
                                <td>{{ $item->user }}</td>
                                <td>{{ $item->Keluhan }}</td>
                                <td>{{ $item->nama_petugas }}</td>
                                <td>{{ $item->status }}</td>
                                <td>{{ $item->updated_at }}</td>
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
    // let table, table1;

    // $(function () {
    //     // table = $('.table-pembelian').DataTable({
    //     //     responsive: true,
    //     //     processing: true,
    //     //     serverSide: true,
    //     //     autoWidth: false,
    //     //     ajax: {
    //     //         url: '{{ route('service.data') }}',
    //     //     },
    //     //     columns: [
    //     //         {data: 'select_all', searchable: false, sortable: false},
    //     //         {data: 'DT_RowIndex', searchable: false, sortable: false},
    //     //         {data: 'tanggal'},
    //     //         {data: 'kode_permintaan'},
    //     //         {data: 'kode_kabin'},
    //     //         {data: 'user'},
    //     //         {data: 'Keluhan'},
    //     //         {data: 'nama'},
    //     //         {data: 'status'},
    //     //         {data: 'aksi', searchable: false, sortable: false},
    //     //     ]
    //     // });

    //     $('.table-supplier').DataTable();
    //     table1 = $('.table-detail').DataTable({
    //         processing: true,
    //         bSort: false,
    //         dom: 'Brt',
    //         columns: [
    //             {data: 'DT_RowIndex', searchable: false, sortable: false},
    //             {data: 'kode_produk'},
    //             {data: 'nama_produk'},
    //             {data: 'harga_beli'},
    //             {data: 'jumlah'},
    //             {data: 'subtotal'},
    //         ]
    //     })
    // });

    function addForm() {
        $('#modal-permintaan').modal('show');
    }

    function addFormsparepart() {
        $('#modal-sparepart').modal('show');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function selesai(url) {
        $.get(url)
            .done((response) => {
                table.ajax.reload();
            })
            .fail((errors) => {
                alert('Tidak dapat meneruskan perintah');
                return;
            });
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