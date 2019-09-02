$(document).ready(function () {
    function validateForm() {
        var values = true;

        if (values) {
            $('#submitLoader').css('display', 'inline-block');
            return true;
        } else {
            return false;
        }
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type === true) {
            new Noty({
                text: '<b>¡Éxito!</b><br> El participante ha sido guardado correctamente.',
                type: 'success'
            }).show();

            $('.has-feedback').each(function () {
                $(this).removeClass('has-success');
            });
            $('.form-control-feedback').each(function () {
                $(this).removeClass('fa-check');
            });
            $('#formNewPartOnQuot').clearForm();
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
            } else {
                console.log('aca');
            }
        }
    }

    var optionsQ = {
        url: 'participants/ajax.insertParticipantOnQuot.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    var optionsT = {
        url: 'participants/ajax.insertParticipantOnTrip.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    var tableQuot = $("#tquotations").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            {className: "text-center"},
            null,
            null,
            {className: "text-center"},
            {className: "text-center"},
            {width: "100px", className: "text-center"},
            {width: "80px", className: "text-center"},
            {"orderable": false, width: "55px", className: "text-center"}],
        'order': [[0, 'desc']],
        'processing': true,
        'language': {
            'processing': '<i class="fa fa-spinner fa-spin"></i>'
        },
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'participants/ajax.getServerAvailableQuotations.php',
            type: 'GET',
            length: 20
        }
    });

    $('#submitLoader').css('display', 'none');

    $('#iNcot').on('ifChecked', function () {
        tableQuot.ajax.url('participants/ajax.getServerAvailableQuotations.php').load();
        $('#filter-title').html('Cotizaciones registradas');
    });

    $('#iNvia').on('ifChecked', function () {
        tableQuot.ajax.url('participants/ajax.getServerAvailableTrips.php').load();
        $('#filter-title').html('Viajes registrados');
    });

    $('#tquotations').on('click', '.quotDetails', function () {
        var uid = $(this).attr('id').split("_").pop();
        $('dd').each(function () {
            $(this).html('');
        });
        $('#summary-extras, #summary-no-extras').css('display', 'none');
        $('#table-extras tbody').empty();
        $('#modal-title').html('Detalles de cotización');

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

    }).on('click', '.quotAdd', function () {
        var uid = $(this).attr('id').split("_").pop();
        $('#iNtid').val('');
        $('#iNqid').val('').val(uid);
    }).on('click', '.tripDetails', function () {
        var uid = $(this).attr('id').split("_").pop();
        $('dd').each(function () {
            $(this).html('');
        });
        $('#summary-extras, #summary-no-extras').css('display', 'none');
        $('#table-extras tbody').empty();
        $('#modal-title').html('Detalles de viaje');

        $.ajax({
            url: 'trips/ajax.getTripData.php',
            type: 'POST',
            dataType: 'json',
            data: {id: uid}
        }).done(function (d) {
            console.log(d);
            $('#date-ini').html(getDateBD(d.data.vi_fecha_ini));
            $('#date-end').html(getDateBD(d.data.vi_fecha_ter));
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

    }).on('click', '.tripAdd', function () {
        var uid = $(this).attr('id').split("_").pop();
        $('#iNqid').val('');
        $('#iNtid').val('').val(uid);
    });

    $('#modal-add').on('change', '.inrut', function () {
        if ($.trim($(this).val()) !== '') {
            $.ajax({
                type: 'POST',
                url: 'participants/ajax.getParticipantByCot.php',
                dataType: 'json',
                data: {rut: $('#iNrutpart').val(), cot: $('#iNqid').val()}
            }).done(function (r) {
                if (r.par_id !== null) {
                    $('#iNrutpart').val('').focus();

                    swal({
                        title: "Error!",
                        text: "El DNI ingresado ya se encuentra entre los participantes registrados.",
                        type: "error"
                    });
                } else {
                    $('#grutpart').addClass('has-success');
                    $('#iconrutpart').addClass('fa-check');
                }
            });
        }
    }).on('change', '.inname, .inap, .inam', function () {
        var idn = $(this).attr('id').split('N');

        if ($.trim($(this).val()) !== '') {
            $('#g' + idn[1]).removeClass('has-error').addClass('has-success');
            $('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
        } else {
            $('#g' + idn[1]).removeClass('has-success');
            $('#icon' + idn[1]).removeClass('fa-check');
        }
    }).on('change', '.inedad', function () {
        var idn = $(this).attr('id').split('N');
        $('#g' + idn[1]).removeClass('has-error has-success');
        $('#icon' + idn[1]).removeClass('fa-remove fa-check');
        var v = $.trim($(this).val());

        if (v !== '') {
            if (!$.isNumeric(v)) {
                $(this).val('');
                $('#g' + idn[1]).addClass('has-error');
                $('#icon' + idn[1]).addClass('fa-remove');

                swal({
                    title: "Error!",
                    text: "El valor ingresado debe ser un valor numérico.",
                    type: "error"
                });
            } else {
                if (parseInt(v, 10) < 18) {
                    $(this).val('');
                    $('#g' + idn[1]).addClass('has-error');
                    $('#icon' + idn[1]).addClass('fa-remove');

                    swal({
                        title: "Error!",
                        text: "El participante debe ser mayor de edad.",
                        type: "error"
                    });
                } else {
                    $('#g' + idn[1]).addClass('has-success');
                    $('#icon' + idn[1]).addClass('fa-check');
                }
            }
        }
    }).on('change', '.inemail', function () {
        var idn = $(this).attr('id').split('N');
        $('#g' + idn[1]).removeClass('has-error has-success');
        $('#icon' + idn[1]).removeClass('fa-remove fa-check');

        var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

        if ($(this).val() !== '') {
            if (!email_reg.test($.trim($(this).val()))) {
                $(this).val('');
                $('#g' + idn[1]).addClass('has-error');
                $('#icon' + idn[1]).addClass('fa-remove');

                swal({
                    title: "Error!",
                    text: "El correo ingresado no es correcto.",
                    type: "error"
                });
            } else {
                $('#g' + idn[1]).addClass('has-success');
                $('#icon' + idn[1]).addClass('fa-check');
            }
        }
    }).on('change', '.inphone', function () {
        var idn = $(this).attr('id').split('N');
        $('#g' + idn[1]).removeClass('has-error has-success');
        $('#icon' + idn[1]).removeClass('fa-remove fa-check');
        var v = $.trim($(this).val());

        if (v !== '') {
            if (!$.isNumeric(v)) {
                $(this).val('');
                $('#g' + idn[1]).addClass('has-error');
                $('#icon' + idn[1]).addClass('fa-remove');

                swal({
                    title: "Error!",
                    text: "El valor ingresado debe ser un valor numérico.",
                    type: "error"
                });
            } else {
                if (v.length < 9) {
                    $(this).val('');
                    $('#g' + idn[1]).addClass('has-error');
                    $('#icon' + idn[1]).addClass('fa-remove');

                    swal({
                        title: "Error!",
                        html: "El número de teléfono ingresado debe tener al menos 9 dígitos.",
                        type: "error"
                    });
                } else {
                    $('#g' + idn[1]).addClass('has-success');
                    $('#icon' + idn[1]).addClass('fa-check');
                }
            }
        }
    }).on('hide.bs.modal', function () {
        if ($('#iNqid').val() !== '') {
            tableQuot.ajax.url('participants/ajax.getServerAvailableQuotations.php').load();
            $('#filter-title').html('Cotizaciones registradas');
        } else {
            tableQuot.ajax.url('participants/ajax.getServerAvailableTrips.php').load();
            $('#filter-title').html('Viajes registrados');
        }
    });

    $('#formNewPartOnQuot').submit(function () {
        if ($('#iNqid').val() !== '')
            $(this).ajaxSubmit(optionsQ);
        else
            $(this).ajaxSubmit(optionsT);

        return false;
    });
});