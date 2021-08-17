@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    
Dashboard

@stop

@section('content')
<input type="hidden" id="id-link" value="{{ $id }}">

<div class="card">
  <div class="card-header bg-success">
    Grabbing Halaman dari {{ $link->nama_website }}
  </div>
  <div class="card-body">
    <form action="{{ route('artikel.result', ['link' => $id]) }}" method="post">
      @csrf
      <div class="input-group">
        <div class="input-group-prepend">
        </div>
      </div>
      <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text" id="basic-addon3">{{ $link->url }}</span>
        </div>
        <input type="hidden" name="jumlah" id="jumlah" value="{{ $link->jumlah }}">
        <input type="number" min="1" max="300" name="page" id="page" class="form-control form-control-sm" required>
        <div class="input-group-append">
          <button type="submit" class="btn btn-sm btn-outline-success">Crawler Data</button>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-header bg-primary">
    Daftar Film {{ $link->nama_website }}
  </div>
  <div class="card-body">
    <table class="table table-bordered display dt-artikel" style="width:100%">
      <thead>
        <tr>
          <th>#</th>
          <th>Judul</th>
          <th>Rating</th>
          <th>Diterbitkan</th>
          <th>Sutradara</th>
          <th>Image</th>
          <th>URL</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script type="text/javascript">
      $(function () {
        var dataId = $('#id-link').val();
        var table = $('.dt-artikel').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
              url: "{{ route('artikel.list') }}",
              data: {link_id: dataId},
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'judul', name: 'judul'},
                {data: 'rating', name: 'rating'},
                {data: 'diterbitkan', name: 'diterbitkan'},
                {data: 'sutradara', name: 'sutradara'},
                {data: 'image_link', name: 'image_link'},
                {data: 'link_id', name: 'link_id'},
            ]
        });
      });
    </script>
@stop
