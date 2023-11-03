@extends('layouts.master')

@section('title')
    Daftar Permintaan Barang
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Permintaan Barang</li>
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation"> <a href="{{ route('perencanaan.index') }}">
        <span>Permintaan barang</span>
    </a></li>
    <li role="presentation" class="active"><a href="{{ route('permintaan_barang.index') }}">
        <span>daftar Permintaan Barang</span>
    </a></li>
</ul>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('perencanaan.create') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Input Permintaan Barang</button>
                <button onclick="deleteData()" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Hapus</button>
            </div>
            <div class="box-body table-responsive">
                <table id="example" class="table table-stiped table-bordered nowrap table-barang-keluar">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode Rencana</th>
                        <th>Tanggal Minta</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Vol Minta</th>
                        <th>Harga Estimasi</th>
                        <th>Jumlah Estimasi</th>
                        <th>Kode Aset</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @foreach ($permintaan_barang as $item)
                            <tr data-id="{{$item->id_perencanaan_detail}}">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->perencanaan->kode_rencana}}</td>
                                <td>{{$item->perencanaan->tanggal_rencana}}</td>
                                <td>{{$item->barang->kode_barang}}</td>
                                <td>{{$item->barang->nama_barang}}</td>
                                <td>{{$item->jumlah}}</td>
                                <td>{{'Rp. '. format_uang($item->biaya_perkiraan)}}</td>
                                <td>{{'Rp. '. format_uang($item->subtotal_perkiraan)}}</td>
                                <td>{{$item->perencanaan->member->kode_kabin}}</td>
                                <td>{{$item->status}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@includeIf('perencanaan.form')
@endsection

@push('scripts')
<script>
    let table, id_global;

    $(function () {
        table = $('.table-barang-keluar').DataTable()

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

    function addForm(url) {
        $('#modal-form').modal('show');

        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
    }


    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData() {
        let url = '/perencanaan/' + id_global + '/delete' 
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
</script>
@endpush