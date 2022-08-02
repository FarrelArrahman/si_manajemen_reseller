<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header pb-0">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('dashboard') }}"><span class="fs-5">Laudable.me Reseller</span></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title" style="margin-left: -15px">
                    <a href="{{ route('user.profile') }}" title="">
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
                        <!-- <div class="dropdown-menu shadow">
                            <a href="{{ route('user.profile') }}" class="dropdown-item link-primary"><i class="fa fa-user me-2 text-primary"></i> Profil</a>
                            <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item link-danger logout-button" href=""><i class="fa fa-power-off me-2 text-danger"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                        </div> -->
                    </a>
                </li>
                
                <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub {{ request()->is('product*') || request()->is('category*') || request()->is('product_variant*') || request()->is('inventory*') ? 'active' : '' }} text-danger">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-tags-fill"></i>
                        <span>Produk</span>
                    </a>
                    <ul class="submenu {{ request()->is('product*') || request()->is('category*') || request()->is('product_variant*') || request()->is('inventory*') ? 'active' : '' }}">
                        @if(auth()->user()->isAdmin())
                        <li class="submenu-item {{ request()->is('category*') ? 'active' : '' }}">
                            <a href="{{ route('category.index') }}">
                                Kategori
                            </a>
                        </li>
                        @endif
                        <li class="submenu-item {{ request()->is('product*') || request()->is('product_variant*') ? 'active' : '' }}">
                            <a href="{{ route('product.index') }}">
                                Katalog
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('inventory*') ? 'active' : '' }}">
                            <a href="{{ route('inventory.index') }}">
                                Varian Produk
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ request()->is('*order') ? 'active' : '' }}">
                    <a href="{{ route('order.index') }}" class='sidebar-link'>
                        <i class="bi bi-cart-fill"></i>
                        <span>Pesanan</span>
                        @if(auth()->user()->isAdmin() && $pending_order_count > 0)
                        <span id="pending_order_count" class="badge bg-danger">{{ $pending_order_count }}</span>
                        @endif
                    </a>
                </li>   

                <li class="sidebar-item {{ request()->is('order_payment*') ? 'active' : '' }}">
                    <a href="{{ route('order_payment.index') }}" class='sidebar-link'>
                        <i class="bi bi-cash-stack"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                <li class="sidebar-item has-sub {{ request()->is('report*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-files"></i>
                        <span>Laporan</span>
                    </a>
                    <ul class="submenu {{ request()->is('report*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->is('report/general*') ? 'active' : '' }}">
                            <a href="{{ route('report.general') }}">
                                Laporan Umum
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('report/selling-recap*') ? 'active' : '' }}">
                            <a href="{{ route('report.sellingRecap') }}">
                                Laporan Rekap Penjualan
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('report/product-selling*') ? 'active' : '' }}">
                            <a href="{{ route('report.productSelling') }}">
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
                        @if($pending_reseller_count > 0)
                        <span id="pending_reseller_count" class="badge bg-danger">{{ $pending_reseller_count }}</span>
                        @endif
                    </a>
                    <ul class="submenu {{ request()->is('user*') ? 'active' : '' }}">
                        <li class="submenu-item {{ request()->is('*staff*') ? 'active' : '' }}">
                            <a href="{{ route('user.index', 'staff') }}">
                                Staff
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('*reseller*') ? 'active' : '' }}">
                            <a href="{{ route('user.index', 'reseller') }}">
                                Reseller 
                                @if($pending_reseller_count > 0)
                                <span class="text-danger" id="pending_reseller_dots">•</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="sidebar-item {{ request()->is('announcement*') ? 'active' : '' }}">
                    <a href="{{ route('announcement.index') }}" class='sidebar-link'>
                        <i class="bi bi-newspaper"></i>
                        <span>Pengumuman</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                <li class="sidebar-item">
                    <a href="{{ route('configuration.index') }}" class='sidebar-link'>
                        <i class="bi bi-gear-fill"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-item">
                    <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="#" class='sidebar-link text-danger'>
                        <i class="fa fa-power-off text-danger"></i>
                        <span>Logout</span>
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>