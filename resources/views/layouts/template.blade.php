<!DOCTYPE html>
<html lang="en">

@include('layouts.head')

<body class="theme-light">
    <!-- App starts here -->
    <div id="app">
        <!-- Sidebar starts here -->
        @include('layouts.sidebar')
        <!-- Sidebar ends here -->

        <!-- Main starts here -->
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            
            <div class="page-heading mb-2">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>@yield('title')</h3>
                            <p class="text-subtitle text-muted">@yield('sub-title')</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first mb-3">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                @yield('action-button')
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(auth()->user()->isReseller() && (! auth()->user()->reseller || ! auth()->user()->reseller->isActive()) && ! request()->is('*reseller/edit*'))
            <div class="alert alert-info alert-dismissible fade show" role="alert" id="unverified-reseller">
                <i class="fa fa-info-circle me-1"></i> Untuk dapat mengakses seluruh menu dan melakukan pemesanan, harap lengkapi data reseller terlebih dahulu, <a href="{{ route('reseller.edit') }}" class="alert-link">klik di sini.</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')

            @include('layouts.footer')
        </div>
        <!-- Main ends here -->
    </div>
    <!-- App ends here -->
    
    @include('layouts.scripts')
</body>

</html>
