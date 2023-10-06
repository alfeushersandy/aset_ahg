@extends('layouts.master')

@section('title')
    Daftar Penerimaan Barang
@endsection

@push('styles')
    <link rel="stylesheet" href="{{asset('css/terima.css')}}">
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Daftar Penerimaan Barang</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="editForm()" class="btn btn-success btn-xs btn-flat revisi"><i class="fa fa-plus-circle"></i>Revisi</button>
                <button onclick="addForm('{{ route('penerimaan.create') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Input Penerimaan Barang</button>
                @if (session('id_penerimaan'))
                    <button onclick="direct('{{ route('penerimaan_detail.index') }}')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Transaksi aktif</button>   
                @endif
            </div>
            <div class="box-body table-responsive">
                <table id="example" class="table table-stiped table-bordered nowrap table-barang-keluar" >
                    <thead>
                        <th>No</th>
                        <th>Nomor Terima</th>
                        <th>Kode Rencana</th>
                        <th>Tanggal Terima</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Vol Terima</th>
                        <th>Sisa Vol Terima</th>
                        <th>Harga Terima</th>
                        <th>Jumlah Terima</th>
                        <th>Status Penerimaan</th>
                        <th>Kode Aset</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($penerimaan as $item)
                            <tr data-id="{{$item->id_penerimaan}}">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->nomor_terima}}</td>
                                <td>{{$item->kode_rencana}}</td>
                                <td>{{date('d-m-Y', strtotime($item->tanggal_terima))}}</td>
                                <td>{{$item->kode_barang}}</td>
                                <td>{{$item->nama_barang}}</td>
                                <td>{{$item->jumlah_terima}}</td>
                                <td>{{$item->sisa_vol_terima}}</td>
                                <td>{{'Rp. '. format_uang($item->harga_terima)}}</td>
                                <td>{{'Rp. '. format_uang($item->subtotal_terima)}}</td>
                                <td>{{$item->status_penerimaan}}</td>
                                <td>{{$item->kode_kabin}}</td>
                                <td>aksi</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@includeIf('penerimaan.form')
@includeIf('penerimaan.formedit')
@includeIf('penerimaan.rencana')
@endsection

@push('scripts')
<script>
    let table;
    let id_global;

    $(function () {
        table = $('.table-barang-keluar').DataTable({
            columnDefs: [{
            targets: 5,
            className: 'text-nowrap'
        }]
    });

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


        $('#keperluan').on('change', function(){
            if($(this).val() == 'service'){
                $('div[name=input_keperluan]').empty();
                $('#label_keperluan').text('kode_service');
                $.ajax({
                    url: 'kodeservice',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data)
                    {
                        $('div[name=input_keperluan]').append("<select name='kode_keperluan' id='kode_keperluan' class='form-control' required></select>");
                        $.each(data, function(key, kode){
                            $('select[name="kode_keperluan"]').append('<option value="'+kode.kode_permintaan+'">' + kode.kode_permintaan + '</option>');
                        });
                    }
                })
            }else if($(this).val() == 'lain lain'){
                $('div[name="input_keperluan"]').empty();
                $('#label_keperluan').text('Catatan Keperluan');
                $('div[name="input_keperluan"]').append('<input type="text" name="kode_keperluan" id="kode_keperluan" class="form-control">');
            }else{
                $('#label_keperluan').empty();
                $('div[name="input_keperluan"]').empty();
            }
        })
    });

    $('#rencana').on("click", function(){
        $('#modal-rencana').modal('show');
    })

    function direct(url)
    {
        window.location.href = url
    }


    function addForm(url) {
        $('#modal-form').modal('show');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
    }


    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function editForm() {
        if(id_global){
            let link = 'penerimaan/edit/' + id_global;
            window.location = link;
        }
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

    function pilih(kode_rencana, id_perencanaan)
    {
        $('#modal-rencana').modal('hide');
        $('#rencana').val(kode_rencana);
        $('#id_perencanaan').val(id_perencanaan);
    }
</script>
@endpush