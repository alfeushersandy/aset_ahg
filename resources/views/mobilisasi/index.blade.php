@extends('layouts.master')

@section('title')
    Daftar mobilisasi / mutasi aset
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar mobilisasi / mutasi aset</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation" class="active"> <a href="{{ route('mobilisasi.index') }}">
        <span>Mobilisasi Aset</span>
    </a></li>
    <li role="presentation"><a href="{{ route('kembali.index') }}">
        <span>Pengembalian Aset</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm('{{ route('mobilisasi.create') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
                </div>
                @if (session('id_mobilisasi'))
                    <div class="btn-group">
                        <a href="{{ route('mobilisasidetail.index') }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-pencil"></i> Transaksi Aktif</a>
                    </div>
                @endif
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-mobilisasi">
                        <thead>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Kode Mobilisasi</th>
                            <th>Pemohon</th>
                            <th>Lokasi Pemohon</th>
                            <th>Keterangan</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Status Kirim</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('mobilisasi.form')
@includeIf('mobilisasi.detail')
@includeIf('mobilisasi.form_kirim')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-mobilisasi').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('mobilisasi.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'kode_mobilisasi'},
                {data: 'pemohon'},
                {data: 'nama_lokasi'},
                {data: 'keterangan'},
                {data: 'tanggal_kembali'},
                {data: 'status'},
                {data: 'status_kirim'},
                {data: 'aksi', searchable: false, sortable: false},
            ],

            
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });

    });

    table1 = $('.table-detail').DataTable({
            processing: true,
            bsort: true,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_kabin'},
                {data: 'identitas'},
                {data: 'user'},
                {data: 'tanggal_awal'},
                {data: 'tanggal_kembali'},
            ]
        })





    function showDetail(url) {
            $('#modal-detail').modal('show');

            table1.ajax.url(url);
            table1.ajax.reload();
        }
    
    
    

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
                $('#modal-form [name=pemohon]').val(response.pemohon);
                $('#modal-form [name=id_lokasi_pemohon]').val(response.id_lokasi_pemohon);
                $('#modal-form [name=keterangan]').val(response.keterangan);
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

    function kirim(){
        $("#modal-form-kirim").modal('show');
    }

    
</script>
@endpush