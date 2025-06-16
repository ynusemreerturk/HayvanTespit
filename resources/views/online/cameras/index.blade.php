@extends('online.layouts.master')
@section('title','Kullanıcı Paneli')
@section('title-page','Kameralar')
@section('content')
    <style>
        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #3fa288;
            border-color: #3fa288;
        }
    </style>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <div class="row">
                    <div class="col">
                        <h3>Kameralar</h3>
                    </div>
                </div>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="camerasTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Anahtar Kodu</th>
                        <th>Adı</th>
                        <th>Konumu</th>
                        <th>Durumu</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var table = $('#camerasTable').DataTable( {
            order: [
                [0,'DESC']
            ],
            "dom": "<'row'<'col mt-1'l><'col-9 mt-1'f><'col 'tr>> <'row'<'col 'i><'col mt-1'p>>",
            processing: true,
            serverSide: true,
            scrollY: true,
            scrollCollapse: true,
            ajax: '{{route('user.camera.fetch')}}',
            columns: [
                {data: 'key_code'},
                {data: 'name'},
                {data: 'location'},
                {data: 'state'},
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.18/i18n/Turkish.json",
            },
        } );
    </script>
@endsection
