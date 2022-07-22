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
                            <label for="nama" id="label_nama_aset" class="col-lg-2 col-lg-offset-1 control-label">Kode kendaraan</label>
                            <div class="col-lg-6">
                                <input type="text" name="nama" id="nama" class="form-control" required autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_pol" id="label_kode_aset" class="col-lg-2 col-lg-offset-1 control-label">Kode Aset/No Polisi</label>
                            <div class="col-lg-6">
                                <input type="text" name="no_pol" id="no_pol" class="form-control" required autofocus>
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
                            <label for="id_lokasi" class="col-lg-2 col-lg-offset-1 control-label">Unit / Lokasi</label>
                            <div class="col-lg-6">
                                <select name="id_lokasi" id="id_lokasi" class="form-control">
                                    <option value="">Pilih Unit/Lokasi</option>
                                    @foreach ($lokasi as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
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
                    <!-- form untuk IT -->
                        <div class="form-group it">
                            <div class="form-group row">
                                <label for="motherboard" id="label_motherboard_aset" class="col-lg-2 col-lg-offset-1 control-label">Motherboard</label>
                                <div class="col-lg-6">
                                    <input type="text" name="motherboard" id="motherboard" class="form-control" required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="processor" id="label_processor_aset" class="col-lg-2 col-lg-offset-1 control-label">Processor</label>
                                <div class="col-lg-6">
                                    <input type="text" name="processor" id="processor" class="form-control" required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ram" id="label_ram_aset" class="col-lg-2 col-lg-offset-1 control-label">Ram</label>
                                <div class="col-lg-6">
                                    <input type="text" name="ram" id="ram" class="form-control" required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="vga" id="label_vga_aset" class="col-lg-2 col-lg-offset-1 control-label">VGA</label>
                                <div class="col-lg-6">
                                    <input type="text" name="vga" id="vga" class="form-control" required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="os" id="label_os_aset" class="col-lg-2 col-lg-offset-1 control-label">Operting System</label>
                                <div class="col-lg-6">
                                    <input type="text" name="os" id="os" class="form-control" required>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keyboard" id="label_keyboard" class="col-lg-2 col-lg-offset-1 control-label">Keyboard</label>
                                <div class="col-lg-6">
                                    <select name="keyboard" id="keyboard" class="form-control" required>
                                        <option value="1">Ada/Bisa</option>
                                        <option value="0">Tidak ada/rusak</option>
                                    </select>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mouse" id="label_mouse" class="col-lg-2 col-lg-offset-1 control-label">Mouse</label>
                                <div class="col-lg-6">
                                    <select name="mouse" id="mouse" class="form-control" required>
                                        <option value="1">Ada/Bisa</option>
                                        <option value="0">Tidak ada/rusak</option>
                                    </select>
                                    <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="network" id="label_network" class="col-lg-2 col-lg-offset-1 control-label">Network</label>
                                <div class="col-lg-6">
                                    <select name="network" id="network" class="form-control" required>
                                        <option value="1">Ada/Bisa</option>
                                        <option value="0">Tidak ada/rusak</option>
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