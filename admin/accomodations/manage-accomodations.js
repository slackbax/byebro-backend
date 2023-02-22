$(document).ready(function () {
    var tableAccm = $("#taccomodations").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            null,
            null,
            {className: "text-right"},
            {className: "text-right"},
            null,
            {"orderable": false, width: "50px", className: "text-center"}],
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 2, 3, 4, 5]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'admin/accomodations/ajax.getServerAccomodations.php',
            type: 'GET',
            length: 20
        }
    });

    $("#taccomodations").on('click', '.accomDeactivate', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer desactivar el alojamiento?",
            text: "Esta acción impedirá que el alojamiento esté disponible para elección dentro de la plataforma.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'admin/accomodations/ajax.delAccomodation.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>El alojamiento ha sido desactivado correctamente.',
                            type: 'success'
                        }).show();
                    } else {
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

                    tableAccm.ajax.reload();
                });
            } else if (result.dismiss === swal.DismissReason.cancel) {
                tableAccm.$('tr.selected').removeClass('selected');
            }
        });
    });

    $("#taccomodations").on('click', '.accomActivate', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer activar el alojamiento?",
            text: "Esta acción permitirá que el alojamiento esté disponible para elección dentro de la plataforma.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'admin/accomodations/ajax.activateAccomodation.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>El alojamiento ha sido activado correctamente.',
                            type: 'success'
                        }).show();
                    } else {
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

                    tableAccm.ajax.reload();
                });
            } else if (result.dismiss === swal.DismissReason.cancel) {
                tableAccm.$('tr.selected').removeClass('selected');
            }
        });
    });
});