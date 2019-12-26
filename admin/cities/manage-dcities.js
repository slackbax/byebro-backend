$(document).ready(function () {
    var tableCty = $("#tcities").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            null,
            {className: "text-center"},
            null,
            {"orderable": false, width: "30px", className: "text-center"}],
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'admin/cities/ajax.getServerDCities.php',
            type: 'GET',
            length: 20
        }
    });

    $("#tcities").on('click', '.cityDeactivate', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer desactivar la ciudad?",
            text: "Esta acción impedirá que la ciudad esté disponible para elección dentro de la plataforma.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    url: 'admin/cities/ajax.delCity.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid, type: 'd'}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>La ciudad ha sido desactivada correctamente.',
                            type: 'success'
                        }).show();
                    }
                    else {
                        if (response.code === 0) {
                            new Noty({
                                text: '<b>¡Error!</b><br>' + response.msg,
                                type: 'error'
                            }).show();
                        } else if (response.code === 1) {
                            new Noty({
                                text: response.msg,
                                type: 'error',
                                callbacks: {
                                    afterClose: function () {
                                        document.location.replace('index.php');
                                    }
                                }
                            }).show();
                        }
                    }

                    tableCty.ajax.reload();
                });
            }
            else if (result.dismiss === swal.DismissReason.cancel) {
                tableCty.$('tr.selected').removeClass('selected');
            }
        });
    });

    $("#tcities").on('click', '.cityActivate', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer activar la ciudad?",
            text: "Esta acción permitirá que la ciudad esté disponible para elección dentro de la plataforma.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    url: 'admin/cities/ajax.activateCity.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid, type: 'd'}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>La ciudad ha sido activada correctamente.',
                            type: 'success'
                        }).show();
                    }
                    else {
                        if (response.code === 0) {
                            new Noty({
                                text: '<b>¡Error!</b><br>' + response.msg,
                                type: 'error'
                            }).show();
                        } else if (response.code === 1) {
                            new Noty({
                                text: response.msg,
                                type: 'error',
                                callbacks: {
                                    afterClose: function () {
                                        document.location.replace('index.php');
                                    }
                                }
                            }).show();
                        }
                    }

                    tableCty.ajax.reload();
                });
            }
            else if (result.dismiss === swal.DismissReason.cancel) {
                tableCty.$('tr.selected').removeClass('selected');
            }
        });
    });
});