$(document).ready(function () {
    var tablePck = $("#tpacks").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            null,
            null,
            {"orderable": false, width: "50px", className: "text-center"}],
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'admin/food/ajax.getServerFood.php',
            type: 'GET',
            length: 20
        }
    });

    $("#tpacks").on('click', '.foodDeactivate', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer desactivar el pack de comida?",
            text: "Esta acción impedirá que el pack esté disponible para elección dentro de la plataforma.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    url: 'admin/food/ajax.delFood.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid, type: 'o'}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>El pack ha sido desactivado correctamente.',
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

                    tablePck.ajax.reload();
                });
            }
            else if (result.dismiss === swal.DismissReason.cancel) {
                tablePck.$('tr.selected').removeClass('selected');
            }
        });
    });

    $("#tpacks").on('click', '.foodActivate', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer activar el pack de comida?",
            text: "Esta acción permitirá que el pack esté disponible para elección dentro de la plataforma.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if(result.value) {
                $.ajax({
                    url: 'admin/food/ajax.activateFood.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid, type: 'o'}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>El pack ha sido activado correctamente.',
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

                    tablePck.ajax.reload();
                });
            }
            else if (result.dismiss === swal.DismissReason.cancel) {
                tablePck.$('tr.selected').removeClass('selected');
            }
        });
    });
});