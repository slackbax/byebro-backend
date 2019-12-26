$(document).ready(function () {
    var tableUsr = $("#tpersonal").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            null,
            null,
            null,
            {className: "text-center"},
            {className: "text-center"},
            {className: "text-center"},
            {"orderable": false, width: "50px", className: "text-center"}],
        'order': [[2, 'asc'], [3, 'asc'], [1, 'asc']],
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'admin/staff/ajax.getServerPersonal.php',
            type: 'GET',
            length: 20
        }
    });

    $("#tpersonal").on('click', '.personalDelete', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer desactivar el empleado?",
            text: "Esta acción impedirá que el empleado pueda ser asignado a un cargo.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    url: 'admin/staff/ajax.delPersonal.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>El empleado ha sido desactivado correctamente.',
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

                    tableUsr.ajax.reload();
                });
            }
            else if (result.dismiss === swal.DismissReason.cancel) {
                tableUsr.$('tr.selected').removeClass('selected');
            }
        });
    });

    $("#tpersonal").on('click', '.personalActivate', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer activar el empleado?",
            text: "Esta acción permitirá que el empleado pueda ser asignado a un cargo.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    url: 'admin/staff/ajax.activatePersonal.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>El empleado ha sido activado correctamente.',
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

                    tableUsr.ajax.reload();
                });
            }
            else if (result.dismiss === swal.DismissReason.cancel) {
                tableUsr.$('tr.selected').removeClass('selected');
            }
        });
    });
});