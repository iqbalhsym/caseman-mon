@section('title', '403 Forbidden')

<x-staradmin>

    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center text-center error-page bg-primary">
        <div class="row flex-grow">
          <div class="col-lg-9 mx-auto text-white">
            <div class="row align-items-center d-flex flex-row">
              <div class="col-lg-6 text-lg-right pr-lg-4">
                <h1 class="display-3 mb-0 text-white">403</h1>
              </div>
              <div class="col-lg-6 error-page-divider text-lg-left pl-lg-4">
                <h2 class="text-white">MAAF!</h2>
                <h3 class="font-weight-light text-white">{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</h3>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 text-center mt-xl-2">
                <a class="text-white font-weight-medium text-decoration-none btn btn-dark text-primary" href="{{ route('admin.dashboard.index') }}">Kembali ke Dashboard</a>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12 mt-xl-2">
                <p class="text-white font-weight-medium text-center">Copyright &copy; {{ date('Y') }} RSUI.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

</x-staradmin>
