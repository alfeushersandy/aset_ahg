@extends('layouts.master')

@section('title')
    Pengembalian Aset
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Pengembalian Aset</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation"> <a href="{{ route('mobilisasi.index') }}">
        <span>Mobilisasi Aset</span>
    </a></li>
    <li role="presentation" class="active"><a href="{{ route('kembali.index') }}">
        <span>Pengembalian Aset</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>kode_mobilisasi</th>
                            <th>Kode Aset</th>
                            <th>Identitas Aset</th>
                            <th>Lokasi Tujuan</th>
                            <th>Tanggal Mobilisasi</th>
                            <th>Aksi</th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

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
                url: '{{ route('kembali.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_mobilisasi', sortable: false},
                {data: 'kode_aset', sortable: false},
                {data: 'identitas', sortable: false},
                {data: 'lokasi_tujuan', sortable: false},
                {data: 'tanggal_awal', sortable: false},
                {data: 'aksi', sortable: false},
            ],

            
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

    
</script>
@endpush