@extends('adminlte::page')

@section('title', 'Dashboard')

@section('plugins.Datatables', true)
@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    
Dashboard

@stop

@section('content')
    
<div class="modal fade" id="addUtamaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-tambah-edit" name="form-tambah-edit">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Website Film</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_website">Nama Website</label>
                        <input type="text" id="nama_website" name="nama_website" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id" id="id">
                        <label for="url">URL</label>
                        <input type="text" name="url" id="url" class="form-control form-control-sm" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah List</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success" id="tombol-simpan" value="create">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header card-primary">
        @if($links == 3)
         required
        @else
        <a href="javascript:void(0)" class="btn btn-sm btn-primary shadow-sm" id="tombol-utama">
            <i class="fas fa-plus-square" ></i>
        </a>
        @endif
    </div>
    <div class="card-body">
        <table class="table table-bordered dt-link">
        <thead>
            <tr>
            <th>#</th>
            <th>Nama Web</th>
            <th>URL</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Aksi</th>
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
    <link href="{{ asset('css/iziToast.css') }}" rel="stylesheet">
@stop

@section('js')
    <script src="{{ asset('vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/iziToast.js') }}"></script>
    {{-- @include('vendor.lara-izitoast.toast') --}}
    <script> console.log('Hi!'); </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $('#tombol-utama').click(function () {
            $('#button-simpan').val("create-post"); //valuenya menjadi create-post
            $('#id').val(''); //valuenya menjadi kosong
            $('#form-tambah-edit').trigger("reset"); //mereset semua input dll didalamnya
            $('#modal-judul').html("Tambah Pegawai Baru"); //valuenya tambah pegawai baru
            $('#addUtamaModal').modal('show'); //modal tampil
        });

        if ($("#form-tambah-edit").length > 0) {
            $("#form-tambah-edit").validate({
                submitHandler: function (form) {
                    var actionType = $('#tombol-simpan').val();
                    $('#tombol-simpan').html('Sending..');
                    $.ajax({
                        data: $('#form-tambah-edit')
                            .serialize(), //function yang dipakai agar value pada form-control seperti input, textarea, select dll dapat digunakan pada URL query string ketika melakukan ajax request
                        url: "{{ route('link.store') }}", //url simpan data
                        type: "POST", //karena simpan kita pakai method POST
                        dataType: 'json', //data tipe kita kirim berupa JSON
                        success: function (data) { //jika berhasil
                            $('#form-tambah-edit').trigger("reset"); //form reset
                            $('#addUtamaModal').modal('hide'); //modal hide
                            $('#tombol-simpan').html('Simpan'); //tombol simpan
                            var oTable = $('.dt-link').dataTable(); //inialisasi datatable
                            oTable.fnDraw(false); //reset datatable
                            iziToast.success({ //tampilkan iziToast dengan notif data berhasil disimpan pada posisi kanan bawah
                                title: 'Data Berhasil Disimpan',
                                message: 'Successfully',
                                position: 'bottomRight'
                            });
                        },
                        error: function (data) { //jika error tampilkan error pada console
                            console.log('Error:', data);
                            $('#tombol-simpan').html('Simpan');
                        }
                    });
                }
            })
        }

        $(function () {
            var table = $('.dt-link').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('link.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'nama_website', name: 'nama_website'},
                    {data: 'url', name: 'url'},
                    {data: 'jumlah', name: 'jumlah'},
                    {data: 'status', name: 'status'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: true, 
                        searchable: true
                    },
                ]
            });
        });

        $('body').on('click', '.edit', function () {
            var data_id = $(this).data('id');
            $.get('link/' + data_id + '/edit', function (data) {
                console.log(data);
                $('#modal-judul').html("Edit Post");
                $('#tombol-simpan').val("edit-post");
                $('#addUtamaModal').modal('show');
                //set value masing-masing id berdasarkan data yg diperoleh dari ajax get request diatas
                $('#id').val(data.id);
                $('#nama_website').val(data.nama_website);
                $('#url').val(data.url);
                $('#jumlah').val(data.jumlah);
            })
        });

        $(document).ready( function () {
            $(document).on('click', '#edit-status', function() {
                var id = $(this).data('id');
                var text = $(this).text();
                var url = "{{ route('link.update', ":id") }}";
                url = url.replace(':id', id);
                console.log(url);
                $.ajax({
                url: url, //eksekusi ajax ke url ini
                data: {
                    text: text,
                    id: id,
                    _token:'{{ csrf_token() }}',
                },
                dataType: "json",
                type: 'PUT',
                error: function (data) {
                    console.log(data);
                },
                success: function (data) { //jika sukses
                    var oTable = $('.dt-link').dataTable(); //inialisasi datatable
                    oTable.fnDraw(false); //reset datatable
                    iziToast.warning({ //tampilkan izitoast warning
                        title: 'Berhasil',
                        message: "Sukses",
                        position: 'bottomRight'
                    });

                    // setTimeout(function() {
                    //   location.reload(true);
                    // }, 1000);
                }
                })
            })
            })
    </script>
@stop
