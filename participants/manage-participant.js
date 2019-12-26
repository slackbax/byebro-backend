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

        if (response.type) {
            new Noty({
                text: '<b>¡Éxito!</b><br> El participante ha sido editado correctamente.',
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

    var options = {
        url: 'participants/ajax.editParticipant.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    var tableQuot = $("#tquotations").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            {width: "100px"},
            {width: "100px", className: "text-right"},
            null,
            null,
            null,
            {width: "80px", className: "text-center"},
            null,
            {width: "100px", className: "text-center"},
            {"orderable": false, width: "40px", className: "text-center"}],
        'order': [[0, 'desc']],
        'processing': true,
        'language': {
            'processing': '<i class="fa fa-spinner fa-spin"></i>'
        },
        'buttons': [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'participants/ajax.getServerParticipantsQuot.php',
            type: 'GET',
            length: 20
        }
    });

    $('#submitLoader').css('display', 'none');

    $('#iNcot').on('ifChecked', function () {
        tableQuot.ajax.url('participants/ajax.getServerParticipantsQuot.php').load();
        $('#filter-title').html('Participantes registrados en cotizaciones');
    });

    $('#iNvia').on('ifChecked', function () {
        tableQuot.ajax.url('participants/ajax.getServerParticipantsTrip.php').load();
        $('#filter-title').html('Participantes registrados en viajes');
    });

    $('#tquotations').on('click', '.quotEdit', function () {
        var uid = $(this).attr('id').split("_").pop();
        $('#iNid, #iNrutpart, #iNedadpart, #iNnamepart, #iNlnppart, #iNlnmpart, #iNemailpart, #iNphonepart').val('');
        $('#iNcodpart').val(0);

        $.ajax({
            url: 'participants/ajax.getParticipant.php',
            type: 'POST',
            dataType: 'json',
            data: {id: uid}
        }).done(function (d) {
            console.log(d);
            $('#iNid').val(d.par_id);
            $('#iNrutpart').val(d.par_rut);
            $('#iNedadpart').val(d.par_edad);
            $('#iNnamepart').val(d.par_nombres);
            $('#iNlnppart').val(d.par_ap);
            $('#iNlnmpart').val(d.par_am);
            $('#iNemailpart').val(d.par_email);
            var tmp = d.par_telefono.split(')');
            var code = tmp[0].replace('(', '');
            $('#iNcodpart').val(code);
            $('#iNphonepart').val(tmp[1]);
        });
    });

    $('#modal-edit').on('change', '.inname, .inap, .inam', function () {
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
    }).on('hide.bs.modal', function (e) {
        tableQuot.ajax.url('participants/ajax.getServerParticipantsQuot.php').load();
        $('#filter-title').html('Participantes registrados en cotizaciones');
    });

    $('#formEditPart').submit(function () {
        $(this).ajaxSubmit(options);
        return false;
    });
});