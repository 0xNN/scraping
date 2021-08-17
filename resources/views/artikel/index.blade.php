@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    
Dashboard

@stop

@section('content')
    
<div class="row">

  @foreach ($links as $item)      
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          @if($item->id == 1)
            <h3>{{ $total_lk21 }}</h3>
          @endif
          @if($item->id == 2)
            <h3>{{ $total_apik }}</h3>
          @endif
          @if($item->id == 3)
            <h3>{{ $total_duta }}</h3>
          @endif
          <p>{{ $item->nama_website }}</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="{{ route('artikel.link', $item->id) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  @endforeach
</div>


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
