<div class="modal fade" id="modal-aset" tabindex="-1" role="dialog" aria-labelledby="modal-aset">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Aset</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-aset">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th>Kode Aset</th>
                        <th>Identitas Aset</th>
                        <th>Lokasi</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($member as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td><span class="label label-success">{{ $item->kode_member }}</span></td>
                                <td>{{ $item->kategori->nama_kategori }}</td>
                                <td>{{ $item->kode_kabin }}</td>
                                <td>{{ $item->nopol }}</td>
                                <td>{{ $item->lokasi->nama_lokasi }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="tampilForm('{{ $item->id}}', '{{ $item->kode_member}}')">
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