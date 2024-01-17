<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
            <li class="header">MASTER</li>
            <li>
                <a href="{{ route('departemen.index') }}">
                    <i class="fa fa-building"></i> <span>Departemen</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i> <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mekanik.index') }}">
                    <i class="fa fa-user-circle-o"></i> <span>Petugas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('barang.index') }}">
                    <i class="fa fa-cubes"></i> <span>Barang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Asset</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lokasi.index') }}">
                    <i class="fa fa-map-marker"></i> <span>Lokasi</span>
                </a>
            </li>
            <li class="header">Perencanaan</li>
            <li>
                <a href="{{ route('perencanaan.index') }}">
                    <i class="fa fa-money"></i> <span>Perencanaan</span>
                </a>
            </li>
            <li class="header">Penerimaan Barang</li>
            <li>
                <a href="{{ route('penerimaan.index') }}">
                    <i class="fa fa-share"></i> <span>Penerimaan Barang</span>
                </a>
            </li>
            <li class="header">TRANSAKSI</li>
            <li>
                <a href="{{ route('permintaan.index') }}">
                    <i class="fa fa-wrench"></i> <span>Permintaan Service</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mobilisasi.index') }}">
                    <i class="fa fa-truck"></i> <span>Mobilisasi / Mutasi Aset</span>
                </a>
            </li>
            {{-- <li class="header">Gudang</li>
            <li>
                <a href="{{ route('permintaan.index') }}">
                    <i class="fa fa-truck"></i> <span>Barang Masuk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('gudang.index') }}">
                    <i class="fa fa-truck"></i> <span>Barang Keluar</span>
                </a>
            </li> --}}
            <li class="header">REPORT</li>
            <li>
                <a href="{{ route('service.history') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Service</span>
                </a>
            </li>
            <li>
                <a href="{{ route('sparepart.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Sparepart</span>
                </a>
            </li>
            <li>
                <a href="{{ route('laporan-ban.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Ban</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mobilisasi.report') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Mobilisasi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('perencanaan.laporan') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Perencanaan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('penerimaan.laporan') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Penerimaan Barang</span>
                </a>
            </li>
            <li class="header">SYSTEM</li>
            <li>
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users"></i> <span>User</span>
                </a>
            </li>
            <li>
                <a href="{{ route("setting.index") }}">
                    <i class="fa fa-cogs"></i> <span>Pengaturan</span>
                </a>
            </li>
            @elseif(auth()->user()->level == 2)
            <li class="header">MASTER</li>
            <li>
                <a href="{{ route('departemen.index') }}">
                    <i class="fa fa-building"></i> <span>Departemen</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i> <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mekanik.index') }}">
                    <i class="fa fa-wrench"></i> <span>Petugas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('barang.index') }}">
                    <i class="fa fa-cubes"></i> <span>Barang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Asset</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lokasi.index') }}">
                    <i class="fa fa-map-marker"></i> <span>Lokasi</span>
                </a>
            </li>
            <li class="header">Perencanaan</li>
            <li>
                <a href="{{ route('perencanaan.index') }}">
                    <i class="fa fa-money"></i> <span>Perencanaan</span>
                </a>
            </li>
            <li class="header">Penerimaan Barang</li>
            <li>
                <a href="{{ route('penerimaan.index') }}">
                    <i class="fa fa-share"></i> <span>Penerimaan Barang</span>
                </a>
            </li>
            <li class="header">TRANSAKSI</li>
            <li>
                <a href="{{ route('permintaan.index') }}">
                    <i class="fa fa-truck"></i> <span>Permintaan Service</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mobilisasi.index') }}">
                    <i class="fa fa-truck"></i> <span>Mobilisasi / Mutasi Aset</span>
                </a>
            </li>
            <li class="header">REPORT</li>
            <li>
                <a href="{{ route('service.history') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Service</span>
                </a>
            </li>
            <li>
                <a href="{{ route('sparepart.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Sparepart</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mobilisasi.report') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Mobilisasi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('perencanaan.laporan') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Perencanaan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('penerimaan.laporan') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Penerimaan Barang</span>
                </a>
            </li>
            @elseif(auth()->user()->level == 3)
            <li class="header">Perencanaan</li>
            <li>
                <a href="{{ route('perencanaan.index') }}">
                    <i class="fa fa-money"></i> <span>Perencanaan</span>
                </a>
            </li>
            <li class="header">Penerimaan Barang</li>
            <li>
                <a href="{{ route('penerimaan.index') }}">
                    <i class="fa fa-share"></i> <span>Penerimaan Barang</span>
                </a>
            </li>
            <li class="header">TRANSAKSI</li>
            <li>
                <a href="{{ route('permintaan.index') }}">
                    <i class="fa fa-wrench"></i> <span>Permintaan Service</span>
                </a>
            </li>
            @elseif(auth()->user()->level == 4)
            <li>
                <a href="{{ route('barang.index') }}">
                    <i class="fa fa-cubes"></i> <span>Barang</span>
                </a>
            </li>
            <li>
                <a href="{{ route('service.index') }}">
                    <i class="fa fa-wrench"></i> <span>Permintaan Service</span>
                </a>
            </li>
            <li>
                <a href="{{ route('perencanaan.laporan') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Perencanaan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('penerimaan.laporan') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Penerimaan Barang</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>