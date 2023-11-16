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
                        <td>Kode Permintaan</td>
                        <td>: {{ $permintaan->kode_permintaan}}</td>
                    </tr>
                    <tr>
                        <td>Kode Kendaraan</td>
                        <td>: {{ $permintaan->member->kode_kabin }}</td>
                    </tr>
                    <tr>
                        <td>Hari,Tanggal</td>
                        <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
                    </tr>
                    <tr>
                        <td>Mekanik</td>
                        <td>: {{ $permintaan->mekanik->nama_petugas}}</td>
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
                                <input type="hidden" name="id_permintaan" id="id_permintaan" value="{{ $permintaan->id }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
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
                        <form action="{{ route('permintaan.store') }}" class="form-sparepart" method="post">
                            @csrf
                            <input type="hidden" name="id_permintaan" value="{{ $permintaan->id }}">
                            <input type="hidden" name="total" id="total" value="{{$total}}">
                            <input type="hidden" name="total_item" id="total_item" value="{{$total_item}}">
                            <input type="hidden" name="bayar" id="bayar">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" value="Rp. {{format_uang($total)}}" readonly>
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

@includeIf('sparepartdetail.produk')
@includeIf('sparepartdetail.ban')
@includeIf('sparepartdetail.form')
@endsection

@push('scripts')
<script>
    let table, table2, table3;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('permintaandetail.data', $permintaan->id) }}',
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
        table3 = $('.table-ban').DataTable({
            order : [1, 'DESC'],
                responsive: true,
                processing: true,
                serverSide: false,
                autoWidth: false,
                data: [],
                columns: [
                    {data: 'select_all'},
                    {data: 'id_barang'},
                    {data: 'nomor_seri'},
                    {data: 'kode_ban'},
                    {data: 'tgl_beli'},
                ],
            });
    


        $('.btn-simpan').on('click', function () {
            $('.form-sparepart').submit();
        });
    });

    $('#tombol_simpan').on('click', function(e){
        let stok = $('#stok').val();
        let id_barang = $('#id_barang').val();
        let url = '/permintaandetail/' + id_barang + '/detail';
        if(! e.preventDefault()){
            $.post('{{ route('permintaandetail.store') }}', $('#modal-aset-form form').serialize())
            .done(response => {
                if($('#modal-aset-form [name=id_kategori]').val() == 5)
                {
                        if(parseInt($('#jumlah').val()) <=  stok){
                            let quantity = $('#jumlah').val();
                            $('#modal-aset-form').modal('hide');
                            $('#modal-ban').modal('show');
                            $('#modal-ban [id=quantity]').val(quantity);
                            table3.ajax.url(url)
                            table3.ajax.reload();
                    }else{
                        alert(`Stok tersedia adalah ${stok}`);
                    }
                    }else{
                        $('#modal-aset-form').modal('hide');
                        table.ajax.reload();
                        }
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        }
    })

    $('#tombol_save').on('click', function(){
        let checkbox = table3.$('[name="id_detail_barang[]"]:checked');
        let quantity = $('#quantity').val();

        if(checkbox.length > quantity || checkbox.length < quantity){
            alert(`check sesuai dengan yang anda inputkan !! jumlah yang anda inputkan adalah : ${quantity}`)
        }else{
            $('.form-ban').submit();
        }
        
    })

    function tampilProduk() {
        $('#modal-produk').modal('show');
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

    function tampilForm(id, kode, harga, id_kategori, nama_barang, stok) {
        hideProduk();
        $('#modal-aset-form').modal('show');
        $('#id_permintaan').val(id);
        $('#id_barang').val(id);
        $('#kode_barang').val(kode);
        $('#harga').val(harga);
        $('#id_kategori').val(id_kategori);
        $('#nama_barang').val(nama_barang);
        $('#stok').val(stok);
        if( id_kategori == 8){
            $('#tombol_simpan').text('Next');
        }else{
            $('#tombol_simpan').text('Simpan');
        }
    }

    function tambahProduk() {
        $.post('{{ route('permintaandetail.store') }}', $('.form-produk').serialize())
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