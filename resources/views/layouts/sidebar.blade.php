<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <!-- <div class="logo">
                    <a href="index.html"><img src="{{ asset('images/logo/logo.png') }}" alt="Logo" srcset=""></a>
                </div> -->
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title" style="margin-left: -15px">
                    <a href="#" title="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="row">
                            <div class="col-3">
                                <div class="avatar avatar-lg bg-warning">
                                    <img src="{{ Storage::url(auth()->user()->photo) }}" alt="">
                                </div>
                            </div>
                            <div class="col-9">
                                <h5 title="{{ auth()->user()->name ?? 'Guest' }}" class="mb-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">{{ auth()->user()->name ?? 'Guest' }}</h5>
                                <p>{{ auth()->user()->role ?? 'User' }}</p>
                            </div>
                        </div>
                        <div class="dropdown-menu shadow">
                            <a href="{{ route('user.profile') }}" class="dropdown-item link-primary"><i class="fa fa-user me-2 text-primary"></i> Profil</a>
                            <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item link-danger logout-button" href=""><i class="fa fa-power-off me-2 text-danger"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </div>
                    </a>
                </li>
                
                <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin() || auth()->user()->isReseller())
                <li class="sidebar-item has-sub {{ request()->is('product*') || request()->is('category*') || request()->is('product_variant*') || request()->is('inventory*') ? 'active' : '' }} text-danger">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-tags-fill"></i>
                        <span>Produk</span>
                    </a>
                    <ul class="submenu {{ request()->is('product*') || request()->is('category*') || request()->is('product_variant*') || request()->is('inventory*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->is('category*') ? 'active' : '' }}">
                            <a href="{{ route('category.index') }}">
                                Kategori
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('product*') || request()->is('product_variant*') ? 'active' : '' }}">
                            <a href="{{ route('product.index') }}">
                                Master
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('inventory*') ? 'active' : '' }}">
                            <a href="{{ route('inventory.index') }}">
                                Inventori
                            </a>
                        </li>
                    </ul>
                </li>
                @else
                <li class="sidebar-item {{ request()->is('inventory*') ? 'active' : '' }} text-danger">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-tags-fill"></i>
                        <span>Produk</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-cart-fill"></i>
                        <span>Pesanan</span>
                    </a>
                </li>   

                @if(auth()->user()->isAdmin())
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
                @else
                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-cash-stack"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->isAdmin())
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
                @endif
                
                @if(auth()->user()->isAdmin())
                <li class="sidebar-item has-sub {{ request()->is('user*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-people-fill"></i>
                        <span>User</span>
                        <span class="badge bg-danger">4</span>
                    </a>
                    <ul class="submenu {{ request()->is('user*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->is('*staff*') ? 'active' : '' }}">
                            <a href="{{ route('user.index', 'staff') }}">
                                Staff
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('*reseller*') ? 'active' : '' }}">
                            <a href="{{ route('user.index', 'reseller') }}">
                                Reseller <span class="text-danger">•</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(auth()->user()->isAdmin())
                <li class="sidebar-item {{ request()->is('announcement*') ? 'active' : '' }}">
                    <a href="{{ route('announcement.index') }}" class='sidebar-link'>
                        <i class="bi bi-newspaper"></i>
                        <span>Pengumuman</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->isAdmin())
                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-gear-fill"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>