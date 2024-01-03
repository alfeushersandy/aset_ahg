<div class="modal fade" id="modal-ban" tabindex="-1" role="dialog" aria-labelledby="modal-produk" style="overflow: scroll">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Ban</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="quantity">
                <form action="" method="post" class="form-ban">
                @csrf
                <table class="table table-striped table-bordered table-ban">
                    <thead>
                        <th width="5%">
                            <input type="checkbox" name="select_all" id="select_all">
                        </th>
                        <th width="5%">Nama Barang</th>
                        <th>Nomor Seri</th>
                        <th>Kode Ban</th>
                        <th>Tgl Beli</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button id="tombol_save" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
            <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
        </div>
    </div>
</div>