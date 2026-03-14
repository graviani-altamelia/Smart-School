<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Smart School' }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <style>
        body { font-family: 'Instrument Sans', sans-serif; background: #FDFDFC; }
        .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .auth-container-box { background: white; width: 100%; max-width: 1000px; display: flex; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 60px rgba(59, 34, 24, 0.1); }
        .auth-side-banner { background: linear-gradient(135deg, #3B2218 0%, #2D1A12 100%); width: 40%; padding: 50px; color: white; display: flex; flex-direction: column; justify-content: center; }
        .auth-side-form { width: 60%; padding: 50px 60px; }
        .btn-custom-primary { background: #C85B28 !important; border: none !important; color: white !important; padding: 14px !important; border-radius: 12px !important; font-weight: 700 !important; width: 100%; transition: 0.3s; }
        .btn-custom-primary:hover { background: #A0471B !important; transform: translateY(-2px); }
        .form-control, .form-select { border-radius: 12px !important; background: #F9F9F9 !important; border: 1.5px solid #F1F1F1 !important; padding: 12px !important; }
        @media (max-width: 991px) { .auth-side-banner { display: none; } .auth-side-form { width: 100%; padding: 30px; } }
    </style>
    @livewireStyles
</head>
<body>

    {{ $slot }} @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.addEventListener('swal:success', event => {
            Swal.fire({ icon: 'success', title: 'Berhasil', text: event.detail.message, timer: 2000, showConfirmButton: false });
        });
    </script>
</body>
</html>