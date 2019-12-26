$(document).ready(function () {
    var tableQuot = $("#tquotations").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            {className: "text-right"},
            {className: "text-center"},
            {className: "text-center"},
            {className: "text-center"},
            {className: "text-right"},
            {className: "text-right"},
            {className: "text-right"},
            {className: "text-center"},
            {width: "80px", className: "text-center"},
            {"orderable": false, width: "30px", className: "text-center"}],
        'order': [[0, 'desc']],
        'processing': true,
        'language': {
            'processing': '<i class="fa fa-spinner fa-spin"></i>'
        },
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'finance/ajax.getServerOC.php',
            type: 'GET',
            length: 20
        }
    });

    $('#tquotations').on('click', '.quotDetails', function () {
        var uid = $(this).attr('id').split("_").pop();
        $('dd').each(function () {
            $(this).html('');
        });
        $('#summary-extras, #summary-no-extras').css('display', 'none');
        $('#table-extras tbody').empty();

        $.ajax({
            url: 'quotations/ajax.getQuotationData.php',
            type: 'POST',
            dataType: 'json',
            data: {id: uid}
        }).done(function (d) {
            console.log(d);
            $('#date-ini').html(getDateBD(d.data.cot_fecha_ini));
            $('#date-end').html(getDateBD(d.data.cot_fecha_ter));
            $('#city-o').html(d.data.cio_nombre);
            $('#city-d').html(d.data.cid_nombre);
            $('#cot-data').html(d.data.co_nombres + ' ' + d.data.co_ap + ' ' + d.data.co_am);
            $('#cot-email').html(d.data.co_email);
            $('#cot-phone').html(d.data.co_telefono);
            $('#num-part').html(d.partic.length);

            if (d.extras.length > 0) {
                $('#summary-extras').css('display', 'block');
                $.each(d.extras, function (e, v) {
                    $('#table-extras').append('<tr><td>' + v.adi_nombre + '</td><td class="text-center">' + v.adi_cantidad + '</td></tr>');
                })
            } else $('#summary-no-extras').css('display', 'block');
        });

    }).on('click', '.quotValue', function () {
        var uid = $(this).attr('id').split("_").pop();

        swal({
            title: "Ingresa el valor para la cotización:",
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí",
            inputValidator: (value) => {
                return !value && '¡Debes ingresar un valor para la cotización!'
            },
            preConfirm: (input) => {
                $.ajax({
                    url: 'quotations/ajax.setValue.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid, val: input}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>La cotización ha sido valorizada correctamente.',
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

                    tableQuot.ajax.reload();
                });
            },
            allowOutsideClick: () => !swal.isLoading()
        });
    }).on('click', '.quotReject', function () {
        var uid = $(this).attr('id').split("_").pop();
        $(this).parent().parent().addClass('selected');

        swal({
            title: "¿Estás seguro de querer rechazar la cotización?",
            text: "Esta acción dejará la cotización en estado RECHAZADA.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sí"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'quotations/ajax.rejQuotation.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: uid}
                }).done(function (response) {
                    if (response.type) {
                        new Noty({
                            text: '<b>¡Éxito!</b><br>La cotización ha sido rechazada correctamente.',
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

                    tableQuot.ajax.reload();
                });
            } else if (result.dismiss === swal.DismissReason.cancel) {
                tableQuot.$('tr.selected').removeClass('selected');
            }
        });
    }).on('click', '.quotTrip', function () {
        var uid = $(this).attr('id').split("_").pop();
        location.href = 'index.php?section=trips&sbs=createtrip&cotid='+uid;
    });
});