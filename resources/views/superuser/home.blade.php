@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Dashboard Superuser</h5>
                <span class="float-right">
                    <a href="javascript:void(0)" id="create-new-produk" class="btn btn-success">Tambah Produk</a>
                </span>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="laravel_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>No</th>
                                <th>Foto Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajax-produk-modal" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="produkCrudModal"></h4>
            </div>
            <div class="modal-body">
                <form id="produkForm" name="produkForm" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="produk_id" id="produk_id">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Nama Barang</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Barang" value="" maxlength="50" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Kategori</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="kategori" name="kategori" placeholder="Kategori" value="" maxlength="50" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Harga</label>
                    <div class="col-sm-12">
                        <input type="number" class="form-control" id="harga" name="harga" placeholder="Harga" value="" required="">
                    </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Image</label>
                        <div class="col-sm-12">
                            <input id="gambar" type="file" name="gambar" accept="image/*" onchange="readURL(this);">
                            <input type="hidden" name="hidden_image" id="hidden_image">
                        </div>
                    </div>
                    <img id="modal-preview" src="https://via.placeholder.com/150" alt="Preview" class="form-group hidden" width="100" height="100">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="btn-save" value="create">Save changes
                        </button>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">  </div>
            </div>
            </div>
        </div>
</div>
@endsection

@section('script')
    <script>
            var SITEURL = '{{URL::to('')}}';
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{URL::to('superuser/home')}}',
            type: 'GET',
        },
        columns: [{
                data: 'id',
                name: 'id',
                'visible': false
            },
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'gambar',
                name: 'gambar',
                orderable: false
            },
            {
                data: 'nama',
                name: 'nama'
            },
            {
                data: 'kategori',
                name: 'kategori'
            },
            {
                data: 'harga',
                name: 'harga'
            },
            {
                data: 'diskon',
                name: 'diskon'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false
            },
        ],
        order: [
            [0, 'desc']
        ]
    });

    /*  When user click add user button */
    $('#create-new-produk').click(function() {
        $('#btn-save').val("create-product");
        $('#produk_id').val('');
        $('#produkForm').trigger("reset");
        $('#produkCrudModal').html("Add New Product");
        $('#ajax-produk-modal').modal('show');
        $('#modal-preview').attr('src', 'https://via.placeholder.com/150');
    });
    /* When click edit user */
    $('body').on('click', '.edit-produk', function() {
        var product_id = $(this).data('id');
        $.get('produk/' + product_id + '/edit', function(data) {
            $('#nama-error').hide();
            $('#kategori-error').hide();
            $('#harga-error').hide();
            $('#produkCrudModal').html("Edit Produk");
            $('#btn-save').val("edit-produk");
            $('#ajax-produk-modal').modal('show');
            $('#produk_id').val(data.id);
            $('#nama').val(data.nama);
            $('#kategori').val(data.kategori);
            $('#harga').val(data.harga);
            $('#modal-preview').attr('alt', 'No image available');
            if (data.gambar) {
                $('#modal-preview').attr('src', '{{URL::to("gambar/produk")}}/' + data.gambar);
                $('#hidden_image').attr('src', '{{URL::to("gambar/produk")}}/' +data.gambar);
            }
        })
    });

    // when delete
    $('body').on('click', '#delete-produk', function() {
        var product_id = $(this).data("id");
        if (confirm("Are You sure want to delete !")) {
            $.ajax({
                type: "get",
                url: '{{URL::to("superuser/produk/delete")}}/' + product_id,
                success: function(data) {
                    var oTable = $('#laravel_datatable').dataTable();
                    oTable.fnDraw(false);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    });
});

// modal submit
$('body').on('submit', '#produkForm', function(e) {
    e.preventDefault();
    var actionType = $('#btn-save').val();
    $('#btn-save').html('Sending..');
    var formData = new FormData(this);
    $.ajax({
        type: 'POST',
        url: '{{URL::to('superuser/produk/store')}}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (data) => {
            $('#produkForm').trigger("reset");
            $('#ajax-produk-modal').modal('hide');
            $('#btn-save').html('Save Changes');
            var oTable = $('#laravel_datatable').dataTable();
            oTable.fnDraw(false);
        },
        error: function(data) {
            console.log('Error:', data);
            $('#btn-save').html('Save Changes');
        }
    });
});

function readURL(input, id) {
    id = id || '#modal-preview';
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $(id).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        $('#modal-preview').removeClass('hidden');
        $('#start').hide();
    }
}

    </script>
@endsection
