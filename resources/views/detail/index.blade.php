<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <style>
      body{
        background: -webkit-linear-gradient(left, #3931af, #00c6ff);
      }
      .download-movie {
        padding: 5px;
      }
      .emp-profile{
          padding: 3%;
          margin-top: 3%;
          margin-bottom: 3%;
          border-radius: 0.5rem;
          background: #fff;
      }
      .profile-img{
          text-align: center;
      }
      .profile-img img{
          width: 70%;
          height: 100%;
      }
      .profile-img .file {
          position: relative;
          overflow: hidden;
          margin-top: -20%;
          width: 70%;
          border: none;
          border-radius: 0;
          font-size: 15px;
          background: #212529b8;
      }
      .profile-img .file input {
          position: absolute;
          opacity: 0;
          right: 0;
          top: 0;
      }
      .profile-head h5{
          color: #333;
      }
      .profile-head h6{
          color: #0062cc;
      }
      .profile-edit-btn{
          border: none;
          border-radius: 1.5rem;
          width: 70%;
          padding: 2%;
          font-weight: 600;
          color: #6c757d;
          cursor: pointer;
      }
      .proile-rating{
          font-size: 12px;
          color: #818182;
          margin-top: 5%;
      }
      .proile-rating span{
          color: #495057;
          font-size: 15px;
          font-weight: 600;
      }
      .profile-head .nav-tabs{
          margin-bottom:5%;
      }
      .profile-head .nav-tabs .nav-link{
          font-weight:600;
          border: none;
      }
      .profile-head .nav-tabs .nav-link.active{
          border: none;
          border-bottom:2px solid #0062cc;
      }
      .profile-work{
          padding: 14%;
          margin-top: -15%;
      }
      .profile-work p{
          font-size: 12px;
          color: #818182;
          font-weight: 600;
          margin-top: 10%;
      }
      .profile-work a{
          text-decoration: none;
          color: #495057;
          font-weight: 600;
          font-size: 14px;
      }
      .profile-work ul{
          list-style: none;
      }
      .profile-tab label{
          font-weight: 600;
      }
      .profile-tab p{
          font-weight: 600;
          color: #0062cc;
      }
    </style>
</head>
<body>
  <div class="container emp-profile">
    <div class="row">
      <div class="col-md-4">
        <div class="profile-img">
          <img src="{{ $artikel->image_link }}" alt=""/>
          <div class="file btn btn-lg btn-primary">
              Change Photo
              <input type="file" name="file"/>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="profile-head">
            <h5>{{ $artikel->judul }}</h5>
            <h6>Genre : Aksi ({{ $artikel->tahun }})</h6>
            <h6>Negara : {{ $artikel->negara }}</h6>
            <p class="proile-rating">RATING : <span class="badge badge-warning"><i class="fas fa-star"></i> {{ $artikel->rating }}</span></p>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Sutradara</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Download</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="aktor-tab" data-toggle="tab" href="#aktor" role="tab" aria-controls="aktor" aria-selected="false">Aktor</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Download</a>
                </li> --}}
            </ul>
            <div class="tab-content profile-tab" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <h6>{{ $artikel->sutradara }}</h6>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="download-movie" id="download-movie"><h6>DOWNLOAD FILM {{ $artikel->judul }}</h6><small>Klik tombol di bawah ini untuk pergi ke halaman website download film {{ $artikel->judul }}.	Terdapat banyak pilihan penyedia file pada halaman tersebut.</small> <a href="https://terbit21.tv/get/?movie=sky-high-2020" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-download"></i> Download Film Ini</a></div>
                </div>
                <div class="tab-pane fade" id="aktor" role="tabpanel" aria-labelledby="aktor-tab">
                    <h6>{{ $artikel->aktor }}</h6>
                </div>
            </div>
        </div>
      </div>
      <div class="col-md-2">
          <div class="badge badge-success">Kualitas : {{ $artikel->kualitas }}</div>
      </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="profile-work">
                {{-- <p>Film Terbaru</p> --}}
                <p>Film Dengan Rating Tertinggi</p>
                @foreach ($terbaru as $item)
                    <a class="btn btn-link" href="{{ route('dashboard.detail', ['tahun' => $item->tahun, 'id' => $item->id]) }}">{{$item->judul}} <span class="badge badge-success"><i class="fas fa-star"></i> {{ $item->rating }}</span> </a><br/>
                @endforeach
            </div>
        </div>
        <div class="col-md-8">

        </div>
    </div>        
  </div>
</body>
</html>