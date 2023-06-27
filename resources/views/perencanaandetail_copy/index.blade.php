@extends('layouts.master')

@section('title')
    Transaksi Sparepart
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-pembelian tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Sparepart</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Kode Rencana</td>
                        <td>: {{ $perencanaan->kode_rencana}}</td>
                    </tr>
                    <tr>
                        <td>Kode Kendaraan</td>
                        <td>: {{$perencanaan->member->kode_kabin}}</td>
                    </tr>
                    <tr>
                        <td>Hari,Tanggal</td>
                        <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_perencanaan" id="id_perencanaan" value="{{ $perencanaan->id_perencanaan }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="hidden" name="id_kategori" id="id_kategori">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button">Sparepart</button>
                                </span>
                                <span class="input-group-btn">
                                    <button onclick="tampilBan()" class="btn btn-info btn-flat" type="button">Ban</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('perencanaan.store') }}" class="form-sparepart" method="post">
                            @csrf
                            <input type="hidden" name="id_perencanaan" value="{{ $perencanaan->id_perencanaan }}">
                            <input type="hidden" name="total" id="total" value="{{$total}}">
                            <input type="hidden" name="total_item" id="total_item" value="{{$total_item}}">
                            <input type="hidden" name="bayar" id="bayar">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" value="{{'Rp. '. format_uang($total)}}" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('perencanaandetail.produk')
@includeIf('perencanaandetail.ban')
@includeIf('perencanaandetail.form')
@endsection

@push('scripts')
<script>
    let table, table2, id_global;
    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('perencanaan_detail.data', $perencanaan->id_perencanaan) }}",
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_barang'},
                {data: 'nama_barang'},
                {data: 'harga'},
                {data: 'jumlah'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
   

        table2 = $('.table-produk').DataTable();
    
        

        $('.btn-simpan').on('click', function () {
            $('.form-sparepart').submit();
        });

    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function tampilBan() {
        $('#modal-ban').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        hideProduk();
        tambahProduk();
    }

    function tampilForm(id, kode, harga) {
        hideProduk();
        $('#modal-aset-form').modal('show');
        $('#id_permintaan').val(id);
        $('#id_barang').val(id);
        $('#id_kategori').val(id_kategori);
        $('#kode_barang').val(kode);
        $('#harga').val(harga);
    }

    function tambahProduk() {
        $.post('{{ route('perencanaan_detail.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#kode_produk').focus();
                table.ajax.reload()
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
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
                    table.ajax.reload() 
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush