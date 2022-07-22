<div class="modal fade" id="modal-permintaan" tabindex="-1" role="dialog" aria-labelledby="modal-permintaan">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih permintaan</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-permintaan">
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
                        @foreach ($permintaan as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>{{ $item->kode_permintaan }}</td>
                                <td>{{ $item->member->kode_kabin }}</td>
                                <td>{{ $item->Keluhan }}</td>
                                <td>{{ $item->mekanik->nama_petugas }}</td>
                                <td>{{ $item->status }}</td>
                                <td>
                                    <a href="{{ route('service.create', $item->id) }}" class="btn btn-primary btn-xs btn-flat">
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