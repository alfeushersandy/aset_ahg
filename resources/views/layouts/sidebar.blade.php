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
            <li class="header">Gudang</li>
            <li>
                <a href="{{ route('permintaan.index') }}">
                    <i class="fa fa-truck"></i> <span>Barang Masuk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('mobilisasi.index') }}">
                    <i class="fa fa-truck"></i> <span>Barang Keluar</span>
                </a>
            </li>
            <li class="header">REPORT</li>
            <li>
                <a href="{{ route('service.history') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan Service</span>
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
            @else
            <li>
                <a href="{{ route('permintaan.index') }}">
                    <i class="fa fa-money"></i> <span>Permintaan Service</span>
                </a>
            </li>
            <li>
                <a href="{{ route('pemeriksaan.index') }}">
                    <i class="fa fa-money"></i> <span>pemeriksaan Service</span>
                </a>
            </li>
            <li>
                <a href="{{ route('service.index') }}">
                    <i class="fa fa-money"></i> <span>Service On Progress</span>
                </a>
            </li>
            <li>
                <a href="{{ route('service.index') }}">
                    <i class="fa fa-money"></i> <span>Service Selesai</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>