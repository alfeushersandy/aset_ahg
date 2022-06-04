@extends('layouts.master')

@section('title')
    Mobilisasi / Mutasi Aset
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

    /* .table-pembelian tbody tr:last-child {
        display: none;
    } */

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
    <li class="active">Transaksi Mobilisasi / Mutasi Alat</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Kode mobilisasi</td>
                        <td>: {{ $mobilisasi->kode_mobilisasi}}</td>
                    </tr>
                    <tr>
                        <td>Pemohon</td>
                        <td>: {{ $mobilisasi->pemohon }}</td>
                    </tr>
                    <tr>
                        <td>Hari,Tanggal</td>
                        <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
                    </tr>
                    <tr>
                        <td>Tujuan</td>
                        <td>: {{ $mobilisasi->lokasi->nama_lokasi}}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                    
                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Aset</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="text" class="form-control" name="kode_member" id="kode_member">
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
                        <th>Kategori</th>
                        <th>Kode Aset</th>
                        <th width="15%">Seri / Type</th>
                        <th>Lokasi Sekarang</th>
                        <th>User / Operator</th>
                        <th width="15%">tanggal Awal</th>
                        <th width="15%">tanggal Akhir</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row"> 
                    <!-- <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div> -->
                    <div class="col-lg-4">
                        <form action="{{ route('mobilisasi.store') }}" class="form-sparepart" method="post">
                            @csrf
                            <input type="hidden" name="id_mobilisasi" value="{{ $id_mobilisasi }}">
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

@includeif('mobilisasidetail.aset')
@includeif('mobilisasidetail.form')
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
            lengthChange: false,
            ajax: {
                url: '{{ route('mobilisasidetail.data', $id_mobilisasi) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_kategori'},
                {data: 'kode_kabin'},
                {data: 'nopol'},
                {data: 'nama_lokasi'},
                {data: 'user'},
                {data: 'tanggal_awal'},
                {data: 'tanggal_akhir'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false,
        })
        
    table2 = $('.table-aset').DataTable();

        $('.btn-simpan').on('click', function () {
            $('.form-sparepart').submit();
        });
    });

    function tampilProduk() {
        $('#modal-aset').modal('show');
    }

    function tampilForm(id, kode) {
        hideProduk();
        $('#modal-aset-form').modal('show');
        $('#kode_member1').val(kode);
        $('#id_aset').val(id);
        $('#kode_member').val(kode);
    }

    function hideProduk() {
        $('#modal-aset').modal('hide');
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