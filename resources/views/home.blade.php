@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Dashboard User</h5>

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
                            </tr>
                        </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
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
            url: '{{URL::to('/home')}}',
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
                "className": "text-center",
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
        ],
        order: [
            [0, 'asc']
        ]
    });
});
</script>

@endsection
