@extends('admin.layouts.master')
@section('title','Admin Paneli')
@section('title-page','Aksiyonlar')
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
                        <h3>Aksiyonlar</h3>
                    </div>

                </div>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="col">
                    Kameraya Göre Filtreleme :
                    <select id="cameraFilter">
                        <option value="" selected>Tüm Kameralar</option>
                        @foreach($kameralar as $kamera)
                            <option value="{{ $kamera }}">{{ $kamera }}</option>
                        @endforeach
                    </select>
                </div>

                <table class="table table-bordered" id="actionsTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="display: none">İd</th>
                        <th>Fotoğraf</th>
                        <th>Kamera Adı</th>
                        <th>Hayvan Türü</th>
                        <th>Doğruluk</th>
                        <th>Tespit Edildiği Saat</th>
                        <th>Güncelleme</th>
                    </tr>
                    </thead>
                </table>
            </div>

            <!-- Update Modal -->
            <div class="modal fade" id="actionUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Doğruluk Güncelleme</h5>
                        </div>
                        <div class="modal-body">
                            <form id="formUpdate" name="formUpdate" class="form-horizontal">
                                <input type="hidden" name="idUpdate" id="idUpdate">
                                <div class="form-group">
                                    <label for="dogrulukUpdate">Doğruluk</label>
                                    <select name="dogruluk" id="dogrulukUpdate" class="form-control">
                                        <option value="1">Doğru</option>
                                        <option value="0">Yanlış</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Kapat</span>
                                    </button>
                                    <button type="button" id="updateButton" onclick="updatebutton()" class="btn ml-1 text-white" style="background-color: #3fa288;" data-bs-dismiss="modal">
                                        <i class="bx bx-check d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block" >Güncelle</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Update Modal End -->
        </div>
    </div>
@endsection
@section('js')
    <script>
        var table = $('#actionsTable').DataTable( {
            order: [
                [0,'DESC']
            ],
            "dom": "<'row'<'col mt-1'l><'col-9 mt-1'f><'col 'tr>> <'row'<'col 'i><'col mt-1'p>>",
            processing: true,
            serverSide: true,
            scrollY: true,
            scrollCollapse: true,
            ajax: {
                url: '{{ route("action.fetch") }}',
                data: function (d) {
                    d.camera_id = $('#cameraFilter').val();
                }
            },
            columns: [
                {data: 'id',visible: false},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'camera_id' , name:'camera_id'},
                {data: 'animal_type' , name:'animal_type'},
                {data: 'dogruluk' , name:'animal_type'},
                {data: 'created_at', orderable: false},
                {data: 'update', orderable: false, searchable: false},
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.18/i18n/Turkish.json",
            },
        } );
    </script>
    <script>
        $('#cameraFilter').on('change', function () {
            table.draw();
        });

        $('body').on('click', '.actionUpdateModal', function () {
            var id = $(this).data('id');
            $.ajax({
                url: '{{route("action.get",'/')}}'+'/'+id,
                method: 'GET',
                success: function (response) {
                    $('#idUpdate').val(response.id);
                    $('#dogrulukUpdate').val(response.dogruluk);
                },
                error: function (error) {
                }
            });
        });
        function  updatebutton(){
            $.ajax({
                url: '{{route('action.update')}}',
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: document.getElementById('idUpdate').value,
                    dogruluk: document.getElementById('dogrulukUpdate').value,
                },

                success: function (data) {
                    if (data.Error != null) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Başarısız',
                            html: data.Error,
                            showConfirmButton: true,
                            confirmButtonText: "Tamam",
                        });
                    }
                    else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı',
                            html: 'Kamera Başarıyla Güncellendi!',
                            showConfirmButton: true,
                            confirmButtonText: "Tamam",
                        });
                    }
                    table.ajax.reload();
                    $('#formUpdate').trigger("reset");
                    $('#actionUpdateModal').modal('hide');
                },

                error: function (data) {
                    var errors = '';
                    for (datas in data.responseJSON.errors) {
                        errors += data.responseJSON.errors[datas] + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Başarısız',
                        html: 'Bilinmeyen bir hata oluştu.\n' + errors,
                        showConfirmButton: true,
                        confirmButtonText: "Tamam",
                    });
                }
            });
        }

    </script>
@endsection
