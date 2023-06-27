@extends('layouts.master')

@section('title')
    Transaksi Penerimaan Barang
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }
    .header tr {
        margin-top: 20px;
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
    <li class="active">Transaksi Penerimaan Barang</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <form id="terima" action="{{route('penerimaan.update_form')}}" method="post">
                    @csrf
                <table width="30%" class="header">
                    <tr>
                        <td>Kode Terima : </td>
                        <td><input type="text" class="form-control" name= "kode_terima" value="{{$penerimaan->nomor_terima}}" readonly></td>
                    </tr>
                    <tr>
                        <td>Tanggal Terima : </td>
                        <td><input type="date" class="form-control" name="tanggal_terima" value="{{$penerimaan->tanggal_terima}}"></td>
                    </tr>
                    <tr>
                        <td>Kode Rencana : </td>
                        <td><input type="text" class="form-control" value="{{$penerimaan->perencanaan->kode_rencana}}" readonly></td>
                    </tr>
                    <tr>
                        <td>Penerima Barang : </td>
                        <td><input type="text" class="form-control" name="penerima" value="{{$penerimaan->penerima}}"></td>
                    </tr>
                </table>
                </form>
            </div>
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_penerimaan" id="id_penerimaan" value="{{ $penerimaan->id_penerimaan}}">
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
                        <th>Harga Terima</th>
                        <th width="15%">Jumlah Terima</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    {{-- <div class="col-lg-8" visible>
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div> --}}
                    <div class="col-lg-4">
                        <form action="{{ route('penerimaan.simpan') }}" class="form-sparepart" method="post">
                            @csrf
                            <input type="hidden" name="id_penerimaan" value="{{ $penerimaan->id_penerimaan}}">
                            {{-- <input type="hidden" name="total" id="total" value="{{$total}}">
                            <input type="hidden" name="bayar" id="bayar"> --}}

                            {{-- <div class="form-group d-flex justify-content-end">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" value="{{'Rp. '. format_uang($total)}}" readonly>
                                </div>
                            </div> --}}
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

@includeIf('penerimaan_edit.produk')
@include('penerimaan_edit.form')
@endsection

@push('scripts')
<script>
    let table, table2;
    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penerimaan.data_Update', $penerimaan->id_penerimaan) }}',
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
   

        
    
        $('.btn-simpan').on('click', function () {
            $('.form-sparepart').submit();
            $('#terima').submit();
        });

    
    });

    var myInput = document.getElementById("jumlah_terima");
    myInput.addEventListener("invalid", validate);
    myInput.addEventListener("keyup", validate);

    function validate() {
    var val = parseFloat(this.value);
    var min = parseFloat(this.min);
    var max = parseFloat(this.max);
    
    if (val < min) {
        this.setCustomValidity('Jumlah Terima tidak bisa kurang dari 0');
    } else if (val > max) {
        this.setCustomValidity('Jumlah Terima maksimal adalah ' + max);
    } else {
        this.setCustomValidity("");
    }
    }

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

    function tampilForm(id, kode, id_rencana_detail, id_rencana,  sisa_terima, biaya_perkiraan) {
        hideProduk();
        $('#modal-form').modal('show');
        $('#id_barang').val(id);
        $('#kode_barang').val(kode);
        $('#id_perencanaan_detail').val(id_rencana_detail);
        $('#id_perencanaan').val(id_rencana);
        $('#harga_terima').val(biaya_perkiraan);
        $('#jumlah_terima').attr('max', sisa_terima)
    }

    function tambahProduk() {
        $.post('{{ route('penerimaan_detail.update') }}', $('.form-produk').serialize())
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
                    location.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush