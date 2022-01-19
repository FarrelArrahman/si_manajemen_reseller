<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="index.html"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo" srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">
                    <div class="row">
                        <div class="col-3">
                            <div role="button" class="avatar avatar-lg bg-warning">
                                <img src="{{ asset('images/faces/2.jpg') }}" alt="">
                            </div>
                        </div>
                        <div class="col-9">
                            <h4 class="mb-0">Admin123</h4>
                            <p>Admin</p>
                        </div>
                    </div>
                </li>
                
                <li class="sidebar-item active ">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-tags-fill"></i>
                        <span>Produk</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a href="#">
                                Nama Produk
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a href="#">
                                Inventori Produk
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-cart-fill"></i>
                        <span>Pesanan</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-cash-stack"></i>
                        <span>Pembayaran</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a href="#">
                                Daftar Pembayaran
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a href="#">
                                Metode Pembayaran
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-files"></i>
                        <span>Laporan</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a href="#">
                                Laporan Rekap Penjualan
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a href="#">
                                Laporan Penjualan Produk
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-people-fill"></i>
                        <span>User</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a href="#">
                                Admin
                            </a>
                        </li>
                        <li class="submenu-item ">
                            <a href="#">
                                Reseller
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-newspaper"></i>
                        <span>Pengumuman</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-gear-fill"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>