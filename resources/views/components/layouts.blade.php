@extends('adminlte::page')

@section('title', 'Beranda')

@section('content')
    <div class="pt-3">
        {{ $slot }}
    </div>
@stop

@section('css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('style')
    <style>
        /* Global Modern UI Override for AdminLTE */
        body { font-family: 'Plus Jakarta Sans', sans-serif !important; background-color: #f4f6f9; }
        .mobile-container { max-width: 100% !important; padding: 0 !important; }
        .dashboard-grid { margin-top: 15px; }

        /* Modern Cards */
        .card, .user-card, .chart-box, .stat-card, .recent {
            border: none !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover, .user-card:hover, .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06) !important;
        }

        /* Modern Tables */
        table.user-table th { background: #f8f9fa !important; color: #4b5563; font-weight: 600; border-bottom: 2px solid #e5e7eb; }
        table.user-table td { border-bottom: 1px solid #f3f4f6; color: #374151; }
        .user-row { transition: background 0.2s ease; }
        .user-row:hover { background-color: #f9fafb !important; }

        /* Modern Buttons */
        .btn, .action-btn { border-radius: 8px !important; font-weight: 500 !important; box-shadow: none !important; }
        .btn-primary { background-color: #104837 !important; border-color: #104837 !important; }
        .btn-primary:hover { background-color: #0b3327 !important; border-color: #0b3327 !important; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(16, 72, 55, 0.2) !important; }

        /* Form Inputs */
        input.search, select { border: 1px solid #e5e7eb !important; transition: border-color 0.2s ease, box-shadow 0.2s ease; }
        input.search:focus, select:focus { border-color: #104837 !important; box-shadow: 0 0 0 3px rgba(16, 72, 55, 0.1) !important; outline: none; }
    </style>
@stop

@section('js')
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" style="display: none;">
        @csrf
    </form>
    @stack('script')
@stop
