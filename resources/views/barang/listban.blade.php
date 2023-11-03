<div class="modal fade" id="modal-ban" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">list Ban</h4>
                </div>
                <div class="modal-body">
                    <div class="btn-group">
                        <button onclick="addBan('{{ route('ban.insertBan') }}')" class="btn btn-success btn-xs btn-flat add-detail-ban" style="margin-bottom: 30px"><i class="fa fa-plus-circle"></i> Tambah</button>
                    </div>
                    <table class="table table-stiped table-bordered mt-4 table-ban">
                        <thead>
                            <th>Nomor Seri Produk</th>
                            <th>Kode Ban</th>
                            <th>Tanggal Beli</th>
                            <th>Tanggal Pakai</th>
                            <th>Kode Aset</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary" onclick="simpanBan()" id="simpan-ban"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>