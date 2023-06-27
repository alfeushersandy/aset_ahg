<div class="modal fade" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Produk</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-produk">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama Barang</th>
                        <th>Vol. Minta</th>
                        <th>Harga Perkiraan</th>
                        <th>Subtotal Perkiraan</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($perencanaan as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->barang->nama_barang }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->biaya_perkiraan }}</td>
                                <td>{{ $item->subtotal_perkiraan }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="tampilForm('{{ $item->id_barang}}', '{{$item->barang->kode_barang}}', '{{$item->id_perencanaan_detail}}','{{$item->id_perencanaan}}', '{{$item->sisa_terima}}', '{{$item->biaya_perkiraan}}', '{{$item->barang->id_kategori}}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>