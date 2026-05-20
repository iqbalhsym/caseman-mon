<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login - Caseman-Mon RSUI</title>
  
  <link rel="stylesheet" href="{{ asset('assets/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  
  <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

  <style>
    /* Background Full Page */
    .content-wrapper {
        background-image: url("{{ asset('images/bg-gedung-rsui.jpeg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Custom Compact Styles with Opacity */
    .auth .auth-form-light {
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        padding: 25px 25px !important; 
        /* Opacity card login (0.85 = 85%) */
        background: rgba(255, 255, 255, 0.88) !important;
        backdrop-filter: blur(5px); /* Memberikan efek blur pada background di belakang card */
    }

    /* Menghilangkan panah atas bawah pada input captcha */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type=number] {
      -moz-appearance: textfield;
    }

    .brand-logo {
        text-align: center;
        margin-bottom: 15px;
    }
    .brand-logo img {
        width: 100px !important; 
    }
    .auth .auth-form-light h4 {
        font-size: 1.1rem;
        margin-bottom: 5px;
        font-weight: 700;
        color: #333;
    }
    .auth .auth-form-light h6 {
        font-size: 0.75rem;
        margin-bottom: 15px !important;
    }
    .form-group {
        margin-bottom: 12px !important;
    }
    .form-group label {
        font-size: 0.7rem;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        color: #555;
    }
    .form-control {
        height: 38px !important;
        font-size: 0.8rem !important;
        padding: 0.5rem 0.75rem !important;
        background: rgba(255, 255, 255, 0.9) !important;
    }
    .input-group-text {
        padding: 0.4rem 0.75rem;
    }
    .captcha-container {
        background: #f8f9fa;
        padding: 4px;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }
    .captcha-img {
        height: 30px;
    }
    .btn-lg {
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .auth-link, .form-check-label {
        font-size: 0.7rem !important;
    }
    .error-text {
        color: #f95f53;
        font-size: 0.7rem;
        margin-top: 3px;
        display: none;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8 mx-auto">
            <div class="auth-form-light text-left">
              <div class="brand-logo">
                <img src="{{ asset('images/logo-rsui-nyamping.png') }}" alt="logo">
              </div>
              <h4 class="text-center">Monitoring Casemanager</h4>
              <h6 class="font-weight-light text-center text-muted">Masuk untuk melanjutkan.</h6>
              
              <form class="pt-2" id="formLogin">
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="username">
                  <div class="error-text" id="err-username"></div>
                </div>
                
                <div class="form-group">
                  <label for="password">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="current-password" style="border-right: none;">
                    <span class="input-group-text bg-white" id="togglePass" style="cursor: pointer; border-left: none; border-color: #dee2e6;">
                        <i class="mdi mdi-eye-outline text-muted"></i>
                    </span>
                  </div>
                  <div class="error-text" id="err-password"></div>
                </div>

                <div class="form-group">
                  <label for="captcha">Captcha</label>
                  <div class="d-flex align-items-center" style="gap: 5px;">
                    <div class="captcha-container d-flex align-items-center justify-content-between flex-grow-1">
                      <img src="{{ $captcha_image ?? '' }}" id="captchaImage" class="captcha-img" alt="Captcha">
                      <button type="button" id="refreshCaptcha" class="btn btn-sm p-1">
                          <i class="mdi mdi-refresh text-primary"></i>
                      </button>
                    </div>
                    <input type="number" class="form-control" id="captcha" name="captcha" placeholder="Hasil" autocomplete="off" style="width: 70px; text-align: center;">
                  </div>
                  <div class="error-text" id="err-captcha"></div>
                </div>
                
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check m-0">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input" name="remember"> Tetap Masuk </label>
                  </div>
                  <a href="#" class="auth-link text-black">Lupa password?</a>
                </div>

                <div id="texterror" class="alert alert-danger py-2 mt-2" style="display:none; font-size: 0.75rem;"></div>

                <div class="mt-3">
                  <button type="submit" id="btnSubmit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn w-100 text-white">MASUK</button>
                </div>
              </form>

              <div class="text-center mt-3 text-muted" style="font-size: 0.65rem; border-top: 1px solid rgba(0,0,0,0.1); padding-top: 10px;">
                Sub Direktorat Pelayanan Medik RSUI<br>
                Dikelola oleh Unit SIMRS & TI<br>
                © {{ date('Y') }} Rumah Sakit Universitas Indonesia
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/template.js') }}"></script>
  <script src="{{ asset('assets/js/settings.js') }}"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function() {
        $('#username').focus();
        $('#togglePass').on('click', function(){
            const inp = $('#password');
            const icon = $(this).find('i');
            if (inp.attr('type') === 'password'){
                inp.attr('type','text');
                icon.removeClass('mdi-eye-outline').addClass('mdi-eye-off-outline');
            } else {
                inp.attr('type','password');
                icon.removeClass('mdi-eye-off-outline').addClass('mdi-eye-outline');
            }
        });

        $('#refreshCaptcha').on('click', function() {
            $.ajax({
                url: "{{ url('/refresh-captcha') }}",
                type: 'GET',
                success: function(data) { $('#captchaImage').attr('src', data.captcha_image); }
            });
        });

        const validateForm = () => {
            let valid = true;
            $('.error-text').hide().text('');
            $('#texterror').hide();
            if ($('#username').val().trim() === '') { $('#err-username').show().text('Username wajib diisi'); valid = false; }
            if ($('#password').val().trim() === '') { $('#err-password').show().text('Password wajib diisi'); valid = false; }
            if ($('#captcha').val().trim() === '') { $('#err-captcha').show().text('Captcha wajib diisi'); valid = false; }
            return valid;
        };

        $('#formLogin').submit(function (e) {
            e.preventDefault();
            if (!validateForm()) return;
            const btn = $('#btnSubmit');
            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>');
            $.ajax({
                data: $(this).serialize(),
                url: "{{ route('login') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    btn.prop('disabled', false).text('MASUK');
                    if (data.status == false){
                        $('#texterror').show().text(data.message);
                        $('#refreshCaptcha').trigger('click');
                        $('#captcha').val('');
                        return false;
                    }
                    window.location.href = data.url;
                },
                error: function (xhr) {
                    btn.prop('disabled', false).text('MASUK');
                    let msg = 'Terjadi kesalahan sistem.';
                    if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    $('#texterror').show().text(msg);
                }
            });
        });
    });
  </script>
</body>
</html>