<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group departemen">
                        <div class="form-group row">
                            <label for="departemen" class="col-lg-2 col-lg-offset-1 control-label">Departemen</label>
                            <div class="col-lg-6">
                                <select name="departemen" id="departemen" class="form-control departemen-row" required>
                                    <option value="">Pilih Departemen</option>
                                    @foreach ($departemen as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                    </div>  

                    <!-- form untuk peralatan -->
                    <div class="form-group peralatan">
                        <div class="form-group row">
                            <label for="id_kategori" id="label_kategori" class="col-lg-2 col-lg-offset-1 control-label">Kategori</label>
                            <div class="col-lg-6">
                                <select name="id_kategori" id="id_kategori" class="form-control" required>
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="merek" id="label_merek_aset" class="col-lg-2 col-lg-offset-1 control-label">Merk</label>
                            <div class="col-lg-6">
                                <input type="text" name="merek" id="merek" class="form-control" required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" id="label_nama_aset" class="col-lg-2 col-lg-offset-1 control-label">Kode kendaraan/Kode Kabin</label>
                            <div class="col-lg-6">
                                <input type="text" name="nama" id="nama" class="form-control" required autofocus>
                                <span><b>*contoh : TP01, T.01, EX-01, dll</b></span>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="serial_number" id="label_serial_number" class="col-lg-2 col-lg-offset-1 control-label">No Seri/No Mesin</label>
                            <div class="col-lg-6">
                                <input type="text" name="serial_number" id="serial_number" class="form-control">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nopol" id="label_kode_aset" class="col-lg-2 col-lg-offset-1 control-label">Identitas/No Polisi</label>
                            <div class="col-lg-6">
                                <input type="text" name="nopol" id="nopol" class="form-control" required>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="asuransi" id="label_kode_aset" class="col-lg-2 col-lg-offset-1 control-label">Asuransi</label>
                            <div class="col-lg-6">
                                <input type="text" name="asuransi" id="asuransi" class="form-control">
                                <span><b>*isi dengan nama Asuransi bila ada</b></span>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tanggal_pembelian" id="label_tanggal_pembelian" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Perolehan</label>
                            <div class="col-lg-6">
                                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" class="form-control">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="harga_perolehan" id="label_harga_perolehan" class="col-lg-2 col-lg-offset-1 control-label">Harga Perolehan</label>
                            <div class="col-lg-6">
                                <input type="number" name="harga_perolehan" id="harga_perolehan" class="form-control">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row_user">
                            <label for="user" class="col-lg-2 col-lg-offset-1 control-label">User</label>
                            <div class="col-lg-6">
                                <input type="text" name="user" id="user" class="form-control">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id_lokasi_homebase" class="col-lg-2 col-lg-offset-1 control-label">Lokasi Homebase</label>
                            <div class="col-lg-6">
                                <select name="id_lokasi_homebase" id="id_lokasi_homebase" class="form-control" required>
                                    <option value="">Pilih Unit/Lokasi</option>
                                    @foreach ($lokasi as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                    </div>                   
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>