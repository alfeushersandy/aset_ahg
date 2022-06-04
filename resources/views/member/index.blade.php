@extends('layouts.master')

@section('title')
    Daftar Customer
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Customer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('member.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Kode Asset</th>
                            <th>Identitas Aset</th>
                            <th>User / Operator</th>
                            <th>Lokasi</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('member.form')
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
                url: '{{ route('member.data') }}',
            },
            columns: [
                {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_member'},
                {data: 'nama_kategori'},
                {data: 'kode_kabin'},
                {data: 'nopol'},
                {data: 'user'},
                {data: 'nama_lokasi'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });

        $('#departemen').on('change', function(){
            let departemen = $(this).val();
            kategori(departemen);
            if(departemen == 1){
                $('.peralatan').show();
                $('.it').hide();
                $('.it input').removeAttr('required autofokus')
                $('.it select').removeAttr('required')
            }else if(departemen == 3){
                $('.peralatan').hide();
                $('.it').show();
                $('.peralatan input').removeAttr('required autofokus')
                $('.peralatan select').removeAttr('required')
            }
    
            });
    });

    function kategori(departemen){
        $.ajax({
                url: 'member/getcategory/'+departemen,
                type:'GET',
                data: {"_token":"{{csrf_token() }}"},
                dataType: "json",
                success: function(data)
                    {
                        $('select[name="id_kategori"]').empty();
                        $.each(data, function(key, kategori) {
                        $('select[name="id_kategori"]').append('<option value="'+ kategori.id_kategori +'">' + kategori.nama_kategori+ '</option>');
                        });
                    }
                        })
                }

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah Member');
        
        $('.peralatan').hide();
        $('.it').hide();
        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
    }

    function editForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit Member');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=no_pol]').val(response.no_pol);
                $('#modal-form [name=user]').val(response.user);
                $('#modal-form [name=telepon]').val(response.telepon);
                $('#modal-form [name=alamat]').val(response.alamat);
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

    
</script>
@endpush