@extends('layouts.master')

@section('title')
    Daftar Perencanaan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Perencanaan</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation"  class="active"> <a href="{{ route('perencanaan.index') }}">
        <span>Permintaan barang</span>
    </a></li>
    <li role="presentation"><a href="{{ route('permintaan_barang.index') }}">
        <span>daftar Permintaan Barang</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Input Permintaan Barang</button>
                    <button onclick="deleteData()" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Hapus</button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table id="example" class="table table-stiped table-permintaan">
                        <thead>
                            <th width="5%">No</th>
                            <th>Tanggal</th>
                            <th>Kode Perencanaan</th>
                            <th>Kode Aset</th>
                            <th>Unit/Lokasi</th>
                            <th>KM</th>
                            <th>Jumlah Item</th>
                            <th>Perkiraan Biaya</th>
                            <th width="15%">Status</th>
                            {{-- <th width="15%"><i class="fa fa-cog"></i></th> --}}
                        </thead>
                        <tbody>
                            @foreach ($perencanaan as $item)
                            <tr data-id="{{$item->id_penerimaan}}">
                                <td>{{$loop->iteration}}</td>
                                <td>{{tanggal_indonesia($item->tanggal_rencana, false)}}</td>
                                <td>{{$item->kode_rencana}}</td>
                                <td>{{$item->member->kode_kabin}}</td>
                                <td>{{$item->lokasi ? $item->lokasi->nama_lokasi : ""}}</td>
                                <td>{{$item->km}}</td>
                                <td>{{$item->total_item}}</td>
                                <td>{{'Rp. '. format_uang($item->total_harga_perkiraan)}}</td>
                                <td>{{$item->status}}</td>
                            </tr>   
                            @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('perencanaan.form')
@endsection

@push('scripts')
<script>
    let table,id_global;
    table = $('.table-permintaan').DataTable();

    $('#example tbody').on('click', 'tr', function () {
        let id = $(this).data("id");
        if ($(this).hasClass('bg-primary')) {
            $(this).removeClass('bg-primary');
        } else {
            table.$('tr.bg-primary').removeClass('bg-primary');
            $(this).addClass('bg-primary');
        }
        id_global = id;
    });
    

    function showDetail(url) {
            $('#modal-detail').modal('show');

            table1.ajax.url(url);
            table1.ajax.reload();
        }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Permintaan');

        $('#modal-form form').reset();
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