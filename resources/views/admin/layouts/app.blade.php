<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Fashionwave</title>

    <!-- Favicon included -->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

    <!-- Apple touch icon included -->
    <link rel="apple-touch-icon" href="assets/images/favicon.png">

    <!-- All CSS files included here -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/select2/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/metisMenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/image-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/styles/main.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/summernote.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/summernote-lite.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/admin/extra.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/cookie-consent.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/toastr.min.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Sidebar area start -->
    @include('admin.layouts.partials.sidebar')
    <!-- Sidebar area end -->


    <div class="main-content">
        <!-- Header section start -->
        @include('admin.layouts.partials.header')
        <!-- Header section end -->


        <div class="page-content-wrap">
            <!-- Container Fluid-->
            <div class="page-content">
                @yield('content')
            </div>

            @include('admin.layouts.partials.footer')

        </div>

    </div>
    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelLogout"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelLogout">Ohh No!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary me-2" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('admin/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/js/admin/summernote-init.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/custom/data-table-page.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/image-preview.js') }}"></script>
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>
    <script src="{{ asset('admin/assets/js/custom.js') }}"></script>
    <script src="{{ asset('admin/assets/js/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/plugins/chart.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/js/admin/dashboard.js') }}"></script>


    @stack('scripts')
</body>

</html>