@extends('layouts.master')

@section('title')
    Daftar Permintaan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Permintaan</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation" class="active"> <a href="{{ route('permintaan.index') }}">
        <span>Permintaan Service</span>
    </a></li>
    <li role="presentation"><a href="{{ route('pemeriksaan.index') }}">
        <span>pemeriksaan Service</span>
    </a></li>
    <li role="presentation"><a href="{{ route('service.index') }}">
        <span>Service On Progress</span>
    </a></li>
    <li role="presentation"><a href="{{ route('service.selesai') }}">
        <span>Service Selesai</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm('{{ route('permintaan.create') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
                    {{-- <button onclick="deleteSelected('{{ route('permintaan.delete_selected') }}')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Hapus</button> --}}
                </div>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Kode Permintaan</th>
                            <th>Kode Aset</th>
                            <th>Unit/Lokasi</th>
                            <th>User</th>
                            <th>Keluhan</th>
                            <th>Mekanik</th>
                            <th>Status</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('permintaan.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('permintaan.data') }}',
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
            ],

            
        });

        

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });
    });
    
    
    

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Permintaan');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_produk]').focus();
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit permintaan');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=tanggal]').focus();

        $.get(url)
            .done((response) => {
                console.log(response);
                $('#modal-form [name=tanggal]').val(response.tanggal);
                $('#modal-form [name=kode_customer]').val(response.kode_customer);
                $('#modal-form [name=Keluhan]').val(response.Keluhan);
                $('#modal-form [name=id_mekanik]').val(response.id_mekanik);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
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

    function deleteSelected(url) {
        if ($('input:checked').length > 1) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, $('.form-produk').serialize())
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                        return;
                    });
            }
        } else {
            alert('Pilih data yang akan dihapus');
            return;
        }
    }

    function cetakBarcode(url) {
        if ($('input:checked').length < 1) {
            alert('Pilih data yang akan dicetak');
            return;
        } else if ($('input:checked').length < 3) {
            alert('Pilih minimal 3 data untuk dicetak');
            return;
        } else {
            $('.form-produk')
                .attr('target', '_blank')
                .attr('action', url)
                .submit();
        }
    }

    
</script>
@endpush