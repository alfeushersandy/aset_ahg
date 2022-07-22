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
@includeIf('member.formedit')
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

        $('#modal-form-edit').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form-edit form').attr('action'), $('#modal-form-edit form').serialize())
                    .done((response) => {
                        $('#modal-form-edit').modal('hide');
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
            var departemen = $(this).val();
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
                if(departemen == 1){
                    $('.peralatan').show();
                    $('#label_kategori').text('Kategori');
                    $('#label_nama_aset').text('Kode Kendaraan');
                    $('#label_kode_aset').text('Kode Aset/No Polisi')
                    $('.it').hide()
                }else if(departemen == 3){
                    $('.peralatan').show();
                    $('.it').hide()
                    $('#label_kategori').text('Kategori IT');
                    $('#label_nama_aset').text('No Inventory');
                    $('#label_kode_aset').text('Nama Inventory')
                        $('#id_kategori').on('change', function(){
                            kategori = $('#id_kategori').val()
                            if(kategori == 6){
                                $('.it').show()
                                $('#motherboard').attr('required');
                                $('#ram').attr('required');
                                $('#vga').attr('required');
                                $('#os').attr('required');
                                $('#processor').attr('required');
                                $('#keyboard').attr('required');
                                $('#mouse').attr('required');
                                $('#network').attr('required');
                                $('#keterangan').attr('required');
                            }else{
                                $('#motherboard').removeAttr('required');
                                $('#ram').removeAttr('required');
                                $('#vga').removeAttr('required');
                                $('#os').removeAttr('required');
                                $('#processor').removeAttr('required');
                                $('#keyboard').removeAttr('required');
                                $('#mouse').removeAttr('required');
                                $('#network').removeAttr('required');
                                $('.it').hide()
                            }
                        })
                }else{
                    $('.peralatan').hide();
                    $('.it').hide()
                }
        
            });
   
    });

    

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
        $('#modal-form-edit').modal('show');
        $('#modal-form-edit .modal-title').text('Edit Member');

        $('#modal-form-edit form')[0].reset();
        $('#modal-form-edit form').attr('action', url);
        $('#modal-form-edit [name=_method]').val('put');
        $('#modal-form-edit [name=nama]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form-edit [name=nopol]').val(response.nopol);
                $('#modal-form-edit [name=user]').val(response.user);
                $('#modal-form-edit [name=kode_kabin]').val(response.kode_kabin);
                $('#modal-form-edit [name=id_kategori]').val(response.id_kategori);
                $('#modal-form-edit [name=id_lokasi]').val(response.id_lokasi);
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