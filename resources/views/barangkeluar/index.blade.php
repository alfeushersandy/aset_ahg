@extends('layouts.master')

@section('title')
    Daftar Barang Keluar
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Barang Keluar</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('gudang.create') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Tambah Permintaan Barang Keluar</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-barang-keluar">
                    <thead>
                        <th width="5%">
                            <input type="checkbox" name="select_all" id="select_all">
                        </th>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Pemohon</th>
                        <th>Keperluan</th>
                        <th>Detail Keperluan</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($barang_keluar as $item)
                            <tr>
                                <td>{{$loop}}</td>
                                <td>{{$item->tanggal}}</td>
                                <td>{{$item->pemohon_id}}</td>
                                <td>{{$item->keperluan}}</td>
                                <td>{{$item->kode_keperluan}}</td>
                                <td>{{$item->keterangan}}</td>
                                <td>{{$item->status}}</td>
                                <td>
                                    <a href="">edit</a>
                                    <a href="">delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@includeIf('barangkeluar.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table-barang-keluar').DataTable()


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
        $('#label_keperluan').empty();
        $('div[name="input_keperluan"]').empty();

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
    }


    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
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
</script>
@endpush