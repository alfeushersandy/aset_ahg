<div class="modal fade" id="modal-aset-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{route('perencanaan_detail.store')}}" method="post" class="form-aset">
            @csrf
            @method('post');
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group-row">
                            <input type="hidden" name="id_perencanaan" id="id_perencanaan" value="{{$perencanaan->id_perencanaan}}">
                            <input type="hidden" name="id_barang" id="id_barang">
                            <input type="hidden" name="id_kategori" id="id_Kategori">
                            <input type="text" class="form-control" name="kode_barang" id="kode_barang" readonly>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="user" class="col-lg-2 col-lg-offset-1 control-label">Harga</label>
                        <div class="col-lg-6">
                        <input type="number" name="harga" id="harga" class="form-control datepicker"
                                style="border-radius: 0 !important;">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="jumlah" class="col-lg-2 col-lg-offset-1 control-label">Quantity</label>
                        <div class="col-lg-6">
                            <input type="number" name="jumlah" id="jumlah" class="form-control datepicker" required
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