@extends('layouts.master')

@section('title')
    Daftar Barang
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Barang</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation" class="active"> <a href="{{ route('barang.index') }}">
        <span>Daftar Barang</span>
    </a></li>
    <li role="presentation"><a href="{{ route('ban.index') }}">
        <span>Daftar Ban</span>
    </a></li>
    <li role="presentation"><a href="{{ route('ban.banPakai') }}">
        <span>Daftar Pemakaian Ban</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm('{{ route('barang.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
                    @if (Auth::user()->level == 1 || Auth::user()->level == 2)
                        <button onclick="deleteSelected('{{ route('barang.delete_selected') }}')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i> Hapus</button>    
                    @endif
                    <button onclick="cetakBarcode('{{ route('barang.cetak_barcode') }}')" class="btn btn-info btn-xs btn-flat"><i class="fa fa-barcode"></i> Cetak Barcode</button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered table-barang">
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Kelompok</th>
                            <th>Merk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('barang.form')
@includeIf('barang.form_ban')
@includeIf('barang.showdetail')
@endsection

@push('scripts')
<script>
    let table,table1;

    $(function () {
        table = $('.table-barang').DataTable({
            responsive: true,
            processing: false,
            serverSide: false,
            autoWidth: false,
            ajax: {
                url: '{{ route('barang.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_barang'},
                {data: 'nama_kategori'},
                {data: 'satuan'},
                {data: 'kelompok'},
                {data: 'merk'},
                {data: 'harga'},
                {data: 'stok'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });


        $('#tombol_simpan').on('click', function(e){
            if (! e.preventDefault()) {
                    $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                        .done((response) => {
                            if($('#modal-form [name=id_kategori]').val() == 5){
                                    $('#modal-form-ban').modal('show');
                                    $('#modal-form-ban .modal-body').empty();
                                    for (let index = 0; index < $('#stok').val(); index++) {
                                        $('#modal-form-ban .modal-body').append(`<div class="form-section">
                                            <div class="form-group row">
                                                <label for="ban" class="col-lg-2 col-lg-offset-1 control-label">Nomor Seri Pabrik</label>
                                                <div class="col-lg-6">
                                                    <input type="text" name="ban[${index}]['nomor_seri']" id="ban" class="form-control" required>
                                                    <span class="help-block with-errors"></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="ban" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Diterima</label>
                                                <div class="col-lg-6">
                                                    <input type="date" name="ban[${index}]['tanggal_beli']" id="ban" class="form-control" value=now() required>
                                                    <span class="help-block with-errors"></span>
                                                </div>
                                            </div>
                                        </div><br>`)
                                    }
                            }else{
                                $('#modal-form').modal('hide');
                                table.ajax.reload();
                            }
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        });
                }
        })

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });

        $('[name=id_kategori]').on('change', function () {
            let kategori = $('#modal-form [name=id_kategori]').val();
            if(kategori == 5){ 
                $('#modal-form .btn-primary').text('Next');
            }else{
                $('#modal-form .btn-primary').html('<i class="fa fa-save"></i> Simpan');
            }
        });
    });

    table1 = $('.table-detail').DataTable({
            order : [1, 'DESC'],
            searching: true,
            processing: true,
            columns: [
                {data: 'kode_barang'},
                {data: 'nama_barang'},
                {data: 'nomor_seri'},
                {data: 'kode_ban'},
                {data: 'tgl_beli'},
                {data: 'tgl_pakai'},
                {data: 'id_aset'},
            ]
        })

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Produk');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama_produk]').focus();
        $('#modal-form .btn-primary').html('<i class="fa fa-save"></i> Simpan');
        $('#modal-form-ban .modal-body').empty();

        
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Barang');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama_produk]').focus();

        $.get(url)
            .done((response) => {
                if(response.id_kategori == 5){
                    $('#modal-form .btn-primary').text('Next');
                }
                console.log(response)
                $('#modal-form [name=nama_barang]').val(response.nama_barang);
                $('#modal-form [name=id_kategori]').val(response.id_kategori);
                $('#modal-form [name=merk]').val(response.merk);
                $('#modal-form [name=satuan]').val(response.satuan);
                $('#modal-form [name=kelompok]').val(response.kelompok);
                $('#modal-form [name=harga_jual]').val(response.harga_jual);
                $('#modal-form [name=diskon]').val(response.diskon);
                $('#modal-form [name=stok]').val(response.stok);
                $('#modal-form .modal-body').append(`
                                            <div class="form-group row">
                                                <div class="col-lg-6">
                                                    <input type="hidden" name="kode_barang" id="kode_barang" class="form-control">
                                                    <span class="help-block with-errors"></span>
                                                </div>`)
                $('#modal-form [name=kode_barang]').val(response.kode_barang);
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

    function showDetail(url) {
            $('#modal-detail').modal('show');

            table1.ajax.url(url);
            table1.ajax.reload();
        }

</script>
@endpush