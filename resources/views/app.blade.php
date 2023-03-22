<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/external/admin-lte/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/external/admin-lte/css/icheck-bootstrap.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="/external/admin-lte/css/tempusdominus-bootstrap-4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/external/admin-lte/css/adminlte.min.css">

    @routes
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="hold-transition layout-top-nav">

<div id="preloader-custom">
    <div class="spinner-custom"></div>
</div>

@inertia

<!-- jQuery -->
<script src="/external/admin-lte/js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/external/admin-lte/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/external/admin-lte/js/adminlte.min.js"></script>

<script src="/external/moment/moment.min.js"></script>
<script src="/external/moment/locale/ru.js"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script src="/external/admin-lte/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
    $(function () {

    });
</script>
</body>
</html>
