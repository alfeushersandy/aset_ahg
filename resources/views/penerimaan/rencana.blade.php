<div class="modal fade" id="modal-rencana" tabindex="-1" role="dialog" aria-labelledby="modal-sparepart">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Kode Perencanaan</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sparepart">
                    <thead>
                        <th width="5%">Kode Rencana</th>
                        <th>Tanggal</th>
                        <th>Kode Aset</th>
                        <th>Unit/Lokasi</th>
                        <th>KM</th>
                        <th>Jumlah Item</th>
                        <th>Perkiraan Biaya</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($rencana as $item)
                            <tr>
                                <td>{{ $item->kode_rencana }}</td>
                                <td>{{ tanggal_indonesia($item->tanggal_rencana, false) }}</td>
                                <td>{{ $item->member->kode_kabin }}</td>
                                <td>{{ $item->lokasi->nama_lokasi }}</td>
                                <td class="text-center">{{ $item->km }}</td>
                                <td>{{ $item->total_item }}</td>
                                <td>{{ "Rp. " . format_uang($item->total_harga_perkiraan) }}</td>
                                <td>
                                    <button class="btn btn-primary btn-xs btn-flat" onclick="pilih('{{$item->kode_rencana}}', '{{$item->id_perencanaan}}')">Pilih</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>