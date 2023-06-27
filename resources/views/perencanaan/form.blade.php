<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('perencanaan.create') }}" method="post" class="form-horizontal">
            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tanggal" class="col-lg-2 col-lg-offset-1 control-label">Tanggal</label>
                        <div class="col-lg-6">
                            <input type="date" value="{{ date('Y-m-d') }}" name="tanggal" id="tanggal" class="form-control datepicker" required
                                style="border-radius: 0 !important;">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kode_customer" class="col-lg-2 col-lg-offset-1 control-label">Kode Aset</label>
                        <div class="col-lg-6">
                            <select name="id_aset" id="id_aset" class="form-control selectpicker" data-live-search="true" required>
                                <option value="">Pilih Aset</option>
                                @foreach ($kendaraan as $item)
                                <option value={{ $item->id }}>{{ $item->kode_kabin }} || {{ $item->nopol }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_lokasi" class="col-lg-2 col-lg-offset-1 control-label">Unit / Lokasi</label>
                        <div class="col-lg-6">
                            <select name="id_lokasi" id="id_lokasi" class="form-control selectpicker" data-live-search="true" required>
                                <option value="">Pilih Unit/Lokasi</option>
                                @foreach ($lokasi as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user" class="col-lg-2 col-lg-offset-1 control-label">KM</label>
                        <div class="col-lg-6">
                        <input type="number" name="km" id="km" class="form-control datepicker"
                                style="border-radius: 0 !important;">
                            <span class="help-block with-errors"></span>
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