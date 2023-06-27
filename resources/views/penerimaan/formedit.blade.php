<div class="modal fade" id="modal-form-edit" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post');
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="kode_terima" class="col-lg-2 col-lg-offset-1 control-label">Nomor Terima</label>
                        <div class="col-lg-6">
                            <input type="text" name="kode_terima" id="kode_terima" class="form-control" readonly
                                style="border-radius: 0 !important;">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Terima</label>
                        <div class="col-lg-6">
                            <input type="date" value="{{ date('Y-m-d') }}" name="tanggal" id="tanggal" class="form-control datepicker" required autofocus
                                style="border-radius: 0 !important;">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rencana" class="col-lg-2 col-lg-offset-1 control-label">Kode Perencanaan</label>
                        <div class="col-lg-6">
                            <select name="rencana" id="rencana" class="form-control selectpicker" data-live-search="true" disabled>
                                <option value="">Pilih Kode Perencenaan</option>
                                @foreach ($rencana_edit as $item)
                                <option value="{{$item->id_perencanaan}}">{{$item->kode_rencana}}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="penerima" class="col-lg-2 col-lg-offset-1 control-label">Penerima</label>
                        <div class="col-lg-6">
                            <input type="text" name="penerima" id="penerima" class="form-control" required
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