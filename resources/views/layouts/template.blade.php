<!DOCTYPE html>
<html lang="en">

@include('layouts.head')

<body>
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
            
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>@yield('title')</h3>
                            <p class="text-subtitle text-muted">@yield('sub-title')</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            @yield('action-button')
                        </nav>
                    </div>
                </div>
                
            </div>
            
            @yield('content')

            @include('layouts.footer')
        </div>
        <!-- Main ends here -->
    </div>
    <!-- App ends here -->
    
    @include('layouts.scripts')
</body>

</html>
