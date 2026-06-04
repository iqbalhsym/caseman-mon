<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Casemanager') - RSUI</title>

  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">

  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css">

  <!-- Tribute.js -->
  <link rel="stylesheet" href="https://unpkg.com/tributejs/dist/tribute.css">

  <style>
    /* Custom modifications for Caseman-mon */
    .sidebar .nav .nav-item.active > .nav-link {
        background: #f4f5f7;
    }
    .sidebar .nav .nav-item .nav-link .menu-icon {
        color: #1f3bb3;
    }
    .btn-primary {
        color: #fff !important;
    }

    /* Caseman-mon tweaks */
    .content-wrapper {
        padding: 1.5rem 1.5rem;
    }

    [contenteditable="true"]:empty:before {
        content: attr(data-placeholder);
        color: #adb5bd;
        pointer-events: none;
        display: block; /* For Firefox */
    }

    /* Condensed Table Styles */
    .table-condensed {
        font-size: 0.85rem;
    }
    .table-condensed th, .table-condensed td {
        padding: 8px 12px !important;
        vertical-align: middle;
    }
    .table-condensed h6 {
        font-size: 0.85rem;
        margin-bottom: 0;
    }
    .table-condensed p {
        font-size: 0.75rem;
        margin-bottom: 0;
    }
    .badge {
        padding: 4px 8px;
        font-size: 0.75rem;
    }

    /* Small Modal Styles */
    .modal-sm-custom {
        max-width: 450px;
    }
    .modal-content {
        border-radius: 10px;
    }
    .modal-header, .modal-footer {
        padding: 12px 20px;
    }
    .modal-body {
        padding: 15px 20px;
    }
    .form-group {
        margin-bottom: 0.75rem;
    }
    .form-group label {
        font-size: 0.85rem;
        margin-bottom: 4px;
    }
    .form-control {
        font-size: 0.85rem;
        padding: 8px 12px;
        height: auto;
    }

    @media (max-width: 991px) {
        .navbar .navbar-brand-wrapper .navbar-brand.brand-logo {
            display: flex !important;
            width: auto;
        }
        .navbar .navbar-menu-wrapper .navbar-nav {
            flex-direction: row;
            align-items: center;
        }
        .welcome-text {
            font-size: 1.1rem !important;
            margin-bottom: 0 !important;
        }
        .welcome-sub-text {
            display: none; /* Hide sub-text on mobile to save space */
        }
    }

    @media (min-width: 992px) {
        .sidebar {
            position: fixed;
            top: 97px; /* Menyesuaikan tinggi navbar */
            height: calc(100vh - 97px);
            overflow-y: auto;
            z-index: 11;
        }
        .sidebar .nav {
        padding-top: 20px; /* Memberikan ruang agar menu 'Beranda' tidak mentok ke atas */
        }
        .main-panel {
            margin-left: 235px; /* Menyesuaikan lebar sidebar */
            width: calc(100% - 235px);
        }
    }
  </style>

  @stack('style')
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>

<div>

  <a class="navbar-brand brand-logo d-flex align-items-center gap-2" href="{{ route('admin.dashboard.index') }}">

    <img src="{{ asset('images/logorsui.png') }}" alt="Logo RSUI" style="height: 36px; width: auto; object-fit: contain;">

    <h4 class="font-weight-bold mb-0 " style="font-size: 16px; white-space: nowrap;">Casemanager</h4>

  </a>

</div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold ms-0">
            <h1 class="welcome-text">Selamat Datang, <span class="text-black fw-bold">{{ Auth::user()->name ?? 'User' }}</span></h1>
            <h3 class="welcome-sub-text">Sistem Monitoring Casemanager RSUI </h3>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/profil.png') }}" alt="Profile image"> </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="fw-light text-muted mb-0">{{ Auth::user()->email ?? '' }}</p>
              </div>

              <a class="dropdown-item" href="{{ route('admin.user.index') }}">
                  <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i>Profil
              </a>
              @if(Auth::check() && !Auth::user()->telegram_chat_id)
                  @php $encodedId = base64_encode(Auth::user()->id); @endphp
                  <a class="dropdown-item" href="https://t.me/rsui_casemanager_bot?start={{ $encodedId }}" target="_blank">
                      <i class="dropdown-item-icon mdi mdi-send text-info me-2"></i>Hubungkan Telegram (App)
                  </a>
                  <a class="dropdown-item" href="https://web.telegram.org/k/#?tgaddr=tg://resolve?domain=rsui_casemanager_bot&start={{ $encodedId }}" target="_blank">
                      <i class="dropdown-item-icon mdi mdi-monitor text-primary me-2"></i>Hubungkan Telegram (Web)
                  </a>
              @else
                  <a class="dropdown-item" href="#">
                      <i class="dropdown-item-icon mdi mdi-check-circle text-success me-2"></i>Telegram Terhubung
                  </a>
              @endif
              <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="dropdown-item-icon mdi mdi-power text-danger me-2"></i>Logout
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
              </form>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->

    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          @if(Auth::check() && Auth::user()->role_id == 1)
          <li class="nav-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Beranda</span>
            </a>
          </li>
          @endif

          @if(Auth::check() && Auth::user()->role_id == 1)
          <li class="nav-item nav-category">Master Data</li>
          <li class="nav-item {{ Request::is('admin/user*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.user.index') }}">
              <i class="menu-icon mdi mdi-account-multiple"></i>
              <span class="menu-title">Pengguna</span>
            </a>
          </li>

          <li class="nav-item {{ Request::is('admin/lokasi*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.lokasi.index') }}">
              <i class="menu-icon mdi mdi-map-marker"></i>
              <span class="menu-title">Lokasi Ruangan</span>
            </a>
          </li>
          <li class="nav-item {{ Request::is('admin/shift*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.shift.index') }}">
              <i class="menu-icon mdi mdi-calendar-clock"></i>
              <span class="menu-title">Jadwal Shift</span>
            </a>
          </li>
          <li class="nav-item {{ Request::is('admin/penjamin*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.penjamin.index') }}">
              <i class="menu-icon mdi mdi-shield-check"></i>
              <span class="menu-title">Penjamin</span>
            </a>
          </li>
          <li class="nav-item {{ Request::is('admin/obat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.obat.index') }}">
              <i class="menu-icon mdi mdi-pill"></i>
              <span class="menu-title">Data Obat</span>
            </a>
          </li>
          @endif

          @if(Auth::check() && Auth::user()->role_id != 4)
          <li class="nav-item nav-category">Transaksi & Laporan</li>
          <li class="nav-item {{ Request::is('admin/permintaan/create') || Request::is('admin/permintaan/*/edit') || Request::is('admin/permintaan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.permintaan.index') }}">
              <i class="menu-icon mdi mdi-file-document-edit-outline"></i>
              <span class="menu-title">Permintaan</span>
            </a>
          </li>
          @endif

          @if(Auth::check() && Auth::user()->role_id != 4)
          @if(Auth::check() && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2))
          <li class="nav-item nav-category">Monitoring</li>
          @endif
          <li class="nav-item {{ Request::is('admin/list-permintaan*') ? 'active' : '' }}">
            {{-- <a class="nav-link" href="{{ route('admin.list-permintaan.index') }}">
              <i class="menu-icon mdi mdi-format-list-checks"></i>
              <span class="menu-title">List Permintaan</span>
            </a> --}}
            </li>
          @if(Auth::check() && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2))
          <li class="nav-item {{ Request::is('admin/laporan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.laporan.index') }}">
              <i class="menu-icon mdi mdi-chart-bar"></i>
              <span class="menu-title">Laporan</span>
            </a>
          </li>
          @endif
          @endif

          <li class="nav-item {{ Request::is('admin/viewer*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.viewer.index') }}">
              <i class="menu-icon mdi mdi-monitor-dashboard"></i>
              <span class="menu-title">Viewer Status</span>
            </a>
          </li>

          <li class="nav-item nav-category">Sistem</li>
          <li class="nav-item">
            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="menu-icon mdi mdi-logout text-danger"></i>
              <span class="menu-title text-danger">Logout</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->

      <div class="main-panel">
        <div class="content-wrapper">
          {{ $slot }}
        </div>
        <!-- content-wrapper ends -->

        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Casemanager - Dikelola oleh Unit SIMRS & IT</span>
            <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center"> &copy; {{ date('Y') }}. Rumah Sakit Universitas Indonesia.</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>

  <!-- inject:js -->
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/template.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>

  <!-- Custom Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

  <!-- Tribute.js -->
  <script src="https://unpkg.com/tributejs/dist/tribute.min.js"></script>

  <script>
    function showToast(message, type = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: type,
            title: message
        });
    }

    // Global AJAX setup for CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  </script>

  @stack('script')
</body>

</html>
