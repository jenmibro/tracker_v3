<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="UTF-8">

<link href="{{ asset('favicon.ico?v=3') }}" type="image/x-icon" rel="Shortcut Icon" />

<!-- vendor styles -->
<!-- @TODO: bundle CSS resources -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
{{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />--}}

<link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/font-awesome.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/animate.css/animate.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.css') }}" />

<!-- App styles -->
<link href="https://fonts.googleapis.com/css?family=Lato|Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/pe-icons/pe-icon-7-stroke.css') }}" />
<link rel="stylesheet" href="{{ asset('css/pe-icons/helper.css') }}" />
<link rel="stylesheet" href="{{ asset('css/stroke-icons/style.css') }}" />
<link rel="stylesheet" href="{{ asset('vendor/datatables/datatables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<script src="{{ asset('js/libs.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
<script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
        'appPath' => route('index'),
    ]); ?>
</script>
