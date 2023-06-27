@extends('layouts.master')

@section('title')
    Daftar Aset
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Aset</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('member.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah</button>
                <button onclick="editForm()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-pencil"></i>Update</button>
                @if (auth()->user()->level == 1)
                    <button onclick="deleteData()" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-eraser"></i>Hapus</button>
                @endif
                <button onclick="cetak('daftar Aset')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-eraser"></i>cetak</button>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-member">
                    @csrf
                    <table id="example" class="table table-stiped table-bordered text-nowrap">
                        <thead>
                            <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Kategori</th>
                            <th>Kode Asset</th>
                            <th>Merek</th>
                            <th>Identitas Aset</th>
                            <th>Operator</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Asuransi</th>
                            <th>Serial Number</th>
                            <th>Tgl Beli</th>
                            <th>Harga Beli</th>
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
    let id_global;

    $(function () {
        $('body').addClass('sidebar-collapse');
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
                {data: 'merek'},
                {data: 'nopol'},
                {data: 'user'},
                {data: 'nama_lokasi'},
                {data: 'status'},
                {data: 'asuransi'},
                {data: 'serial_number'},
                {data: 'tanggal_pembelian'},
                {data: 'harga_perolehan'},
            ],

            rowCallback: function(row, data, index) {
                if (data.status == "Terjual") {
                    $(row).css('background-color','#32a0a8')
                    $(row).css('color','white')
                }
            }
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
            $('#modal-form-edit [name=id_lokasi]').prop('disabled', false)
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

        $('#example tbody').on('click', 'tr', function () {
        var row = $(this).closest('tr')
        let id = table.row(row).data()
        if ($(this).hasClass('info')) {
            $(this).removeClass('info');
        } else {
            table.$('tr.info').removeClass('info');
            $(this).addClass('info');
        }
        id_global = id.id;
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
                    $('#label_kode_aset').text('Identitas/No Polisi')
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

    function editForm() {
        $('#modal-form-edit').modal('show');
        $('#modal-form-edit .modal-title').text('Edit Member');
        let url = 'member/'+id_global;

        $('#modal-form-edit form')[0].reset();
        $('#modal-form-edit form').attr('action', 'member/'+id_global);
        $('#modal-form-edit [name=_method]').val('put');
        $('#modal-form-edit [name=nama]').focus();
        $('#modal-form-edit [name=id_lokasi]').prop('disabled', true)

        $.get(url)
            .done((response) => {
                $('#modal-form-edit [name=nopol]').val(response.nopol);
                $('#modal-form-edit [name=user]').val(response.user);
                $('#modal-form-edit [name=kode_kabin]').val(response.kode_kabin);
                $('#modal-form-edit [name=id_kategori]').val(response.id_kategori);
                $('#modal-form-edit [name=id_home_base]').val(response.id_home_base);
                $('#modal-form-edit [name=id_lokasi]').val(response.id_lokasi);
                $('#modal-form-edit [name=serial_number]').val(response.serial_number);
                $('#modal-form-edit [name=tanggal_pembelian]').val(response.tanggal_pembelian);
                $('#modal-form-edit [name=merek]').val(response.merek);
                $('#modal-form-edit [name=status]').val(response.status);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function deleteData() {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            let url = "member/"+id_global;
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

    function cetak(title) {
        let url = "/member/nota";
        popupCenter(url, title, 900, 675);
    }

    function popupCenter(url, title, w, h) {
        const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
        const dualScreenTop  = window.screenTop  !==  undefined ? window.screenTop  : window.screenY;

        const width  = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left       = (width - w) / 2 / systemZoom + dualScreenLeft
        const top        = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow  = window.open(url, title, 
        `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
        `
        );

        if (window.focus) newWindow.focus();
    }

    
</script>
@endpush