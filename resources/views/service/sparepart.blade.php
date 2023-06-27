<div class="modal fade" id="modal-sparepart" tabindex="-1" role="dialog" aria-labelledby="modal-sparepart">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Permintaan Service</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sparepart">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Kode Permintaan</th>
                        <th>Kode Customer</th>
                        <th>Keluhan</th>
                        <th>Mekanik</th>
                        <th>Status</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($sparepart as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ date("d-m-Y", strtotime($item->tanggal)) }}</td>
                                <td>{{ $item->kode_permintaan }}</td>
                                <td>{{ $item->kode_kabin }}</td>
                                <td>{{ $item->Keluhan }}</td>
                                <td>{{ $item->nama_petugas }}</td>
                                <td>{{ $item->status }}</td>
                                <td>
                                    <a href="{{ route('permintaan.sparepart', $item->id) }}" class="btn btn-primary btn-xs btn-flat">
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