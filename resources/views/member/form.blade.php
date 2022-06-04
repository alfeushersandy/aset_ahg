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
                            <label for="id_kategori" class="col-lg-2 col-lg-offset-1 control-label">Kategori</label>
                            <div class="col-lg-6">
                                <select name="id_kategori" id="id_kategori" class="form-control" required>
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama" class="col-lg-2 col-lg-offset-1 control-label">Kode kendaraan</label>
                            <div class="col-lg-6">
                                <input type="text" name="nama" id="nama" class="form-control" required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_pol" class="col-lg-2 col-lg-offset-1 control-label">Kode Aset/No Polisi</label>
                            <div class="col-lg-6">
                                <input type="text" name="no_pol" id="no_pol" class="form-control" required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row_user">
                            <label for="user" class="col-lg-2 col-lg-offset-1 control-label">User</label>
                            <div class="col-lg-6">
                                <input type="text" name="user" id="user" class="form-control" required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id_lokasi" class="col-lg-2 col-lg-offset-1 control-label">Unit / Lokasi</label>
                            <div class="col-lg-6">
                                <select name="id_lokasi" id="id_lokasi" class="form-control" required>
                                    <option value="">Pilih Unit/Lokasi</option>
                                    @foreach ($lokasi as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                    </div>

                    <!-- form untuk IT -->
                    <div class="form-group it">
                        <div class="form-group row">
                            <label for="id_kategori" class="col-lg-2 col-lg-offset-1 control-label">Kategori</label>
                            <div class="col-lg-6">
                                <select name="id_kategori" id="id_kategori" class="form-control" required>
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama_it" class="col-lg-2 col-lg-offset-1 control-label">Kode Aset</label>
                            <div class="col-lg-6">
                                <input type="text" name="nama_it" id="nama_it" class="form-control" required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tipe_it" class="col-lg-2 col-lg-offset-1 control-label">Tipe</label>
                            <div class="col-lg-6">
                                <input type="text" name="tipe_it" id="tipe_it" class="form-control" required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row_user">
                            <label for="user_it" class="col-lg-2 col-lg-offset-1 control-label">User</label>
                            <div class="col-lg-6">
                                <input type="text" name="user_it" id="user_it" class="form-control" required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="id_lokasi_it" class="col-lg-2 col-lg-offset-1 control-label">Unit / Lokasi</label>
                            <div class="col-lg-6">
                                <select name="id_lokasi_it" id="id_lokasi_it" class="form-control" required>
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