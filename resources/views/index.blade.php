<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.css" rel="stylesheet"/>

    {{-- <link rel="stylesheet" href="http://localhost/film/public/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/film/public/vendor/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
    <link rel="stylesheet" href="http://localhost/film/public/vendor/adminlte/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> --}}

    <style>
      /* Bottom right text */
      .text-block {
        position: absolute;
        text-align: center;
        bottom: 5px;
        background-color: transparent;
        color: white;
        padding-left: 5px;
        padding-right: 5px;
      }

      /* Top left text */
      .top-left {
        position: absolute;
        top: 0px;
        left: 0px;
      }

      /* Top left text */
      .top-right {
        position: absolute;
        top: 0px;
        right: 0px;
      }

      .bottom-right {
        position: absolute;
        bottom: 0px;
        right: 0px;
      }
    </style>
</head>
<body>
  <header>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
      <div class="container-fluid">
        <button
          class="navbar-toggler"
          type="button"
          data-mdb-toggle="collapse"
          data-mdb-target="#navbarExample01"
          aria-controls="navbarExample01"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarExample01">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item active">
              <a class="nav-link" aria-current="page" href="{{ route('dashboard.index') }}">Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('dashboard.about') }}">Tentang Saya</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Navbar -->
  
    <!-- Background image -->
    <div
      class="p-5 text-center bg-image"
      style="
        background-image: url('https://mdbcdn.b-cdn.net/img/new/slides/041.jpg');
        height: 400px;
      "
    >
      <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
        <div class="d-flex justify-content-center align-items-center h-100">
          <div class="text-white">
            <h1 class="mb-3">Grabbing Film pada {{ $active_film->nama_website }}</h1>
            <h4 class="mb-3">Grabbing adalah suatu teknik mengambil text atau secara umum obyek yang ada di situs lain. Data film ini diambil dari website sumber {{ $active_film->nama_website }}</h4>
            <a class="btn btn-secondary btn-lg" href="{{ $sumber }}" role="button">Go To {{ $active_film->nama_website }}</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Background image -->
  </header>
  <div class="container pt-3">
    <div class="row">
      @foreach ($lists as $item)          
        <div class="col-sm-2">
          <div class="card">
            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
              <img src="{{ $item->image_link}}" width="100%" height="280px"/>
              <div class="text-block">
                <h6>{{ $item->judul }}</h6>
              </div>
              <div class="top-left badge bg-warning"><i class="fas fa-star"></i> {{ $item->rating }} </div>
              <div class="top-right badge bg-success">{{ $item->kualitas }} <i class="fas fa-clock"></i> {{ $item->durasi }} </div>
              <a href="#!">
                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
              </a>
            </div>
            <a href="{{ route('dashboard.detail', ['tahun' => $item->tahun, 'id' => $item->id]) }}" class="btn btn-block btn-sm btn-primary">Download</a>
            <div class="card-body">
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="pt-3">
      <div class="text-center">
        {{ $lists->links() }}
      </div>
    </div>
  </div>
  <footer class="text-center text-white" style="background-color: #caced1;">
    <!-- Grid container -->
    <div class="container p-4">
      <!-- Section: Images -->
      <section class="">
        <div class="row">
          @foreach($enam_film as $item)
          <div class="col-lg-2 col-md-12 mb-4 mb-md-0">
            <div
              class="bg-image hover-overlay ripple shadow-1-strong rounded"
              data-ripple-color="light"
            >
              <img
                src="{{ $item->image_link }}"
                class="w-100"
              />
              <a href="{{ route('dashboard.detail', ['tahun' => $item->tahun, 'id' => $item->id]) }}">
                <div
                  class="mask"
                  style="background-color: rgba(251, 251, 251, 0.2);"
                ></div>
              </a>
            </div>
          </div>
          @endforeach
<!--  -->
        </div>
      </section>
      <!-- Section: Images -->
    </div>
    <!-- Grid container -->
  
    <!-- Copyright -->
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
      © {{ date('Y') }} Copyright:
      <a class="text-white" href="#">Grabbing</a>
    </div>
    <!-- Copyright -->
  </footer>

  <!-- MDB -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.js"></script>
</body>
</html>