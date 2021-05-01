<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>VPC Admin Pannel</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <link rel="stylesheet" href="{{ URL::asset('public/backend/bower_components/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('public/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="{{ URL::asset('public/backend/bower_components/Ionicons/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('public/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('public/backend/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('public/backend/css/skins/_all-skins.min.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-chosen@1.4.2/bootstrap-chosen.css" />
  <!-- fileupload -->

  <!-- myplugins start -->
  <!-- sweetalert -->
  <script src="{{ URL::asset('public/backend/myplugin/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
  <script src="{{ URL::asset('public/backend/myplugin/sweetalert2/dist/sweetalert2.min.js') }}"></script>
  <link rel="stylesheet" href="{{ URL::asset('public/backend/myplugin/sweetalert2/dist/sweetalert2.min.css') }}">

  <!-- Start My plugin validation -->
  <link rel="stylesheet" href="{{ URL::asset('public/backend/myplugin/validation/toastr.css') }}">
  <!-- <link rel="stylesheet" href="{{ URL::asset('public/backend/myplugin/validation/jquery_validation_engine/template.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('public/backend/myplugin/validation/jquery_validation_engine/validationEngine.jquery.css') }}"> -->

  <!-- end My plugin validation -->
  <link rel="stylesheet" href="{{ URL::asset('public/backend/myplugin/validation/jquery-validate/css/jquery.validate.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('public/backend/myplugin/validation/jquery-validate/css/style.css') }}">
  <!-- <script type="text/javascript" src="{{ URL::asset('public/backend/js/jquery.min.js') }}"></script> -->

  <script src="{{ URL::asset('public/backend/myplugin/validation/jquery-validate/js/jquery.validate.js') }}"></script>
  <script src="{{ URL::asset('public/backend/myplugin/validation/jquery-validate/js/additional-methods.min.js') }}"></script>
  
  <!-- start My plugin loader -->
  <link rel="stylesheet" href="{{ URL::asset('public/backend/myplugin/loader/css') }}/main.css">
  <!-- end My plugin loader -->

  <style>
    .btn-mar {
        margin-top: 16px;
    }
    .ui-timepicker-container.ui-timepicker-no-scrollbar.ui-timepicker-standard {
      z-index: 99999999 !important;
    }
    .content {
      overflow-y: auto !important;
    }
    .skin-blue .content-header {
        background: transparent;
        clear: both;
        min-height: 90px;
    }
    .select2-container {
      width:100% !important;
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="#" class="logo">
      <span class="logo-mini"><b>A</b>LT</span>
      <span class="logo-lg">VPC Admin</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ URL::asset('public/backend/img/user1-128x128.jpg') }}" class="user-image" alt="User Image">
              <span class="hidden-xs">Admin</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-footer">
              	<div class="pull-left">
              		<!-- <a href="{/{ URL('/user/profile') }}" class="btn btn-default btn-flat">Profile</a> -->
              	</div>
                <div class="pull-right">
                  <a href="{{ URL('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ URL::asset('public/backend/img/user1-128x128.jpg') }}" class="user-image" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        @include("include.sidebar")
      </ul>
    </section>
  </aside>
  <div class="content-wrapper">
  	@yield('content')
  	</div>
  <div class="control-sidebar-bg"></div>
</div>
<script src="{{ URL::asset('public/backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/backend/bower_components/fastclick/lib/fastclick.js') }}"></script>
<script src="{{ URL::asset('public/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('public/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/backend/bower_components/select2/dist/js/select2.full.js') }}"></script>
<script src="{{ URL::asset('public/backend/bower_components/chart.js/Chart.js') }}"></script>
<script src="{{ URL::asset('public/backend/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ URL::asset('public/backend/js/demo.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/json2html/1.2.0/json2html.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.json2html/1.2.0/jquery.json2html.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!-- fileupload -->
<script type="text/javascript" src="http://simpleupload.michaelcbrook.com/js/simpleUpload/simpleUpload.min.js"></script>

<!-- range datepicker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/moment-range@4.0.1/dist/moment-range.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- full calender -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/main.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/interaction/main.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/daygrid/main.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.1/firebase-app.js"></script>
<!-- map -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-chosen@1.4.2/dist/chosen.jquery-1.4.2/chosen.jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js" integrity="sha512-Z/2pIbAzFuLlc7WIt/xifag7As7GuTqoBbLsVTgut69QynAIOclmweT6o7pkxVoGGfLcmPJKn/lnxyMNKBAKgg==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.css" integrity="sha512-PZ83szWxZ41zcHUPd7NSgLfQ3Plzd7YmN0CHwYMmbR7puc6V/ac5Mm0t8QcXLD7sV/0AuKXectoLvjkQUdIz9g==" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js" integrity="sha512-Z/2pIbAzFuLlc7WIt/xifag7As7GuTqoBbLsVTgut69QynAIOclmweT6o7pkxVoGGfLcmPJKn/lnxyMNKBAKgg==" crossorigin="anonymous"></script>

<!-- Start My plugin validation -->
<!-- <script src="{{ URL::asset('public/backend/myplugin/validation/jquery_validation_engine/jquery.validationEngine.js') }}"></script>
<script src="{{ URL::asset('public/backend/myplugin/validation/jquery_validation_engine/jquery.validationEngine-en.js') }}"></script> -->
<script src="{{ URL::asset('public/backend/myplugin/validation/custom.js') }}"></script>
<script src="{{ URL::asset('public/backend/myplugin/validation/jquery.browser.js') }}"></script>
<script src="{{ URL::asset('public/backend/myplugin/validation/jquery.form.js') }}"></script>
<script src="{{ URL::asset('public/backend/myplugin/validation/toastr.js') }}"></script>

<!-- end My plugin validation -->

<!-- start My plugin loader -->
<script src="{{ URL::asset('public/backend/myplugin/loader/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
<script src="{{ URL::asset('public/backend/myplugin/loader/js/loader.js') }}"></script>

<!-- end My plugin loader -->
<script>
  $(document).ready(function () {
    $(".datatable").DataTable();
    $('.search_select').select2();
    $('.timepicker').timepicker({
      timeFormat: 'h:mm p',
      interval: 60,
      minTime: '10',
      defaultTime: '11',
      startTime: '10:00',
      dynamic: false,
      dropdown: true,
      scrollbar: true
    });
    $('.single_datepicker').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: {{ date("Y", strtotime("-1 year")) }},
        maxYear: parseInt(moment().format('YYYY'),10),
        locale: {
          format: 'YYYY-MM-DD hh:mm A'
        },
        timePicker: true,
      }, function(start, end, label) {
        var years = moment().diff(start, 'years');
        //alert("You are " + years + " years old!");
      });
  });
</script>
</body>
</html>

        
      