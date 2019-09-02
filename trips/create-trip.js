$(document).ready(function () {

    /*function getCountries() {
        $.ajax({
            type: 'POST',
            url: 'src/rest.allCountries.php',
            dataType: 'json'
        }).done(function (r) {
            $('#iNocountry, #iNdcountry').html('').append($("<option />").val('').text('Selecciona el país'));
            $.each(r, function (k, v) {
                $('#iNocountry, #iNdcountry').append($("<option />").val(v.alpha3Code).text(v.name));
            })
        });
    }*/

    function validateForm() {
        $('#submitLoader').css('display', 'inline-block');
        return true;
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');
        $('#sectionTabs a[href="#trip"]').tab('show');

        if (response.type === true) {
            new Noty({
                text: '<b>¡Éxito!</b><br> El viaje ha sido guardado correctamente.<br>Redirigiendo a la lista de viajes registrados...',
                type: 'success',
                callbacks: {
                    afterClose: function () {
                        document.location.replace('index.php?section=trips&sbs=managetrips');
                    }
                }
            }).show();

            //getCountries();
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
    }

    var options = {
        url: 'trips/ajax.insertTrip.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };
    var nParticipantes = (parseInt($('#iNnumpart').val(), 10) + 1), nTotal = parseInt($('#iNnumpart').val(), 10);
    $('#submitLoader').css('display', 'none');

    var today = new Date(), tomorrow = new Date(), dayafter = new Date();
    tomorrow.setDate(today.getDate() + 1);
    dayafter.setDate(tomorrow.getDate() + 1);

    $('#iNdates').daterangepicker({
        minDate: tomorrow,
        endDate: dayafter
    }).on('apply.daterangepicker', function () {
        $('#gdates').addClass('has-success');
        $('#icondates').addClass('fa-check');
    });
    $('#iNdates').data('daterangepicker').setStartDate($('#iNstartDate').val());
    $('#iNdates').data('daterangepicker').setEndDate($('#iNendDate').val());

    $('.opt-dni').on('ifChanged', function () {
        $('.opt-dni').each(function () {
            var idn = $(this).attr('id').split('_');
            $('#g' + idn[1]).css('display', 'none').removeClass('has-error has-success');
            $('#icon' + idn[1]).removeClass('fa-remove fa-check');
            $('#iN' + idn[1]).removeAttr('required').val('');
        });

        if ($(this).prop('checked', true)) {
            var idn = $(this).attr('id').split('_');
            $('#g' + idn[1]).css('display', 'block');
            $('#iN' + idn[1]).prop('required', true);
        }
    });

    $('#iNphone').change(function () {
        if ($.trim($(this).val()) !== '') {
            if (!$.isNumeric($(this).val())) {
                $(this).val('');
                $('#gphone').addClass('has-error');
                $('#iconphone').addClass('fa-remove');

                swal({
                    title: "Error!",
                    html: "El teléfono ingresado no corresponde a un valor numérico.",
                    type: "error"
                });
            } else {
                var str = $(this).val();
                if (str.length < 9) {
                    $(this).val('');
                    $('#gphone').addClass('has-error');
                    $('#iconphone').addClass('fa-remove');

                    swal({
                        title: "Error!",
                        html: "El número de teléfono ingresado debe tener al menos 9 dígitos.",
                        type: "error"
                    });
                } else {
                    $('#gphone').addClass('has-success');
                    $('#iconphone').addClass('fa-check');
                }
            }
        }
    });

    $('#iNemail').change(function () {
        $('#gemail').removeClass('has-error has-success');
        $('#iconEmail').removeClass('fa-remove fa-check');

        var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

        if ($(this).val() !== '') {
            if (!email_reg.test($.trim($(this).val()))) {
                $(this).val('');
                $('#gemail').addClass('has-error');
                $('#iconEmail').addClass('fa-remove');

                swal({
                    title: "Error!",
                    text: "El correo ingresado no es correcto.",
                    type: "error"
                });
            } else {
                $('#gemail').addClass('has-success');
                $('#iconEmail').addClass('fa-check');
            }
        }
    });

    $('#iNrut').Rut({
        on_error: function () {
            swal("Error!", "El RUT ingresado no es válido.", "error");
            $('#iNrut').val('');
            $('#grut').addClass('has-error');
            $('#iconrut').addClass('fa-remove');
        },
        on_success: function () {
            $.ajax({
                type: 'POST',
                url: 'quotations/ajax.getUserDataByRut.php',
                dataType: 'json',
                data: {rut: $('#iNrut').val()}
            }).done(function (r) {
                if (r.co_id !== '') {
                    $('#grut').removeClass('has-error').addClass('has-success');
                    $('#iconrut').removeClass('fa-remove').addClass('fa-check');
                    $('#iNid').val(r.co_id);
                    $('#iNname').val(r.co_nombres).change();
                    $('#iNlastnamep').val(r.co_ap).change();
                    $('#iNlastnamem').val(r.co_am).change();
                    $('#iNemail').val(r.co_email).change();
                    var tmp = r.co_telefono.split(')');
                    var ph = tmp[0].split('(');
                    $('#iNcod').val(ph[1]);
                    $('#iNphone').val(tmp[1]).change();
                } else {
                    $('#iNid').val('');
                    $('#iNrut').val('');
                    $('#grut').removeClass('has-success');
                    $('#iconrut').removeClass('fa-check');
                    $('#iNname').val('').change();
                    $('#iNlastnamep').val('').change();
                    $('#iNlastnamem').val('').change();
                    $('#iNemail').val('').change();
                    $('#iNphone').val('').change();
                }
            });
        },
        format_on: 'keyup'
    }).change(function () {
        if ($.trim($(this).val()) === '') {
            $('#iNid').val('');
            $('#grut').removeClass('has-error has-success');
            $('#iconrut').removeClass('fa-remove fa-check');
            $('#iNname').val('').change();
            $('#iNlastnamep').val('').change();
            $('#iNlastnamem').val('').change();
            $('#iNemail').val('').change();
            $('#iNphone').val('').change();
        }
    });

    $('#iNdni').change(function () {
        if ($.trim($(this).val()) !== '') {
            $.ajax({
                type: 'POST',
                url: 'quotations/ajax.getUserDataByRut.php',
                dataType: 'json',
                data: {rut: $('#iNdni').val()}
            }).done(function (r) {
                if (r.co_id !== '') {
                    $('#gdni').removeClass('has-error').addClass('has-success');
                    $('#icondni').removeClass('fa-remove').addClass('fa-check');
                    $('#iNid').val(r.co_id);
                    $('#iNname').val(r.co_nombres).change();
                    $('#iNlastnamep').val(r.co_ap).change();
                    $('#iNlastnamem').val(r.co_am).change();
                    $('#iNemail').val(r.co_email).change();
                    var tmp = r.co_telefono.split(')');
                    var ph = tmp[0].split('(');
                    $('#iNcod').val(ph[1]);
                    $('#iNphone').val(tmp[1]).change();
                } else {
                    $('#iNid').val('');
                    $('#iNdni').val('');
                    $('#gdni').removeClass('has-success');
                    $('#iconrut').removeClass('fa-check');
                    $('#iNname').val('').change();
                    $('#iNlastnamep').val('').change();
                    $('#iNlastnamem').val('').change();
                    $('#iNemail').val('').change();
                    $('#iNphone').val('').change();
                }
            });
        }
    });

    $('#iNocountry, #iNdcountry, #iNocity, #iNdcity, #iNcost, #iNname, #iNlastnamep, #iNlastnamem, #iNemail, #iNphone').change(function () {
        var idn = $(this).attr('id').split('N');

        if ($.trim($(this).val()) !== '') {
            $('#g' + idn[1]).removeClass('has-error').addClass('has-success');
            $('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
        } else {
            $('#g' + idn[1]).removeClass('has-success');
            $('#icon' + idn[1]).removeClass('fa-check');
        }
    });

    $('#btn-add-part').click(function () {
        var $divP = $('<div>'),
            $row1 = $('<div>'), $row2 = $('<div>'), $row3 = $('<div>');

        var $rutD = $('<div>'), $nacD = $('<div>'),
            $nameD = $('<div>'), $apD = $('<div>'), $amD = $('<div>'),
            $mailD = $('<div>'), $paisD = $('<div>'), $telD = $('<div>');
        $rutD.addClass('form-group col-md-6 col-lg-3 has-feedback').prop('id', 'grutpart' + nParticipantes);
        $nacD.addClass('form-group col-md-6 col-lg-3 has-feedback').prop('id', 'gedadpart' + nParticipantes);
        $nameD.addClass('form-group col-md-6 col-lg-3 has-feedback').prop('id', 'gnamepart' + nParticipantes);
        $apD.addClass('form-group col-md-6 col-lg-3 has-feedback').prop('id', 'glnppart' + nParticipantes);
        $amD.addClass('form-group col-md-6 col-lg-3 has-feedback').prop('id', 'glnmpart' + nParticipantes);
        $mailD.addClass('form-group col-md-6 col-lg-3 has-feedback').prop('id', 'gemailpart' + nParticipantes);
        $paisD.addClass('form-group col-md-2 col-lg-1').prop('id', 'gcodpart' + nParticipantes);
        $telD.addClass('form-group col-md-4 col-lg-3 has-feedback').prop('id', 'gphonepart' + nParticipantes);

        var $rutLabel = $('<label>'), $nacLabel = $('<label>'),
            $nameLabel = $('<label>'), $apLabel = $('<label>'), $amLabel = $('<label>'),
            $mailLabel = $('<label>'), $paisLabel = $('<label>'), $telLabel = $('<label>');
        $rutLabel.addClass('control-label').text('RUT/DNI *');
        $nacLabel.addClass('control-label').text('Edad *');
        $nameLabel.addClass('control-label').text('Nombres *');
        $apLabel.addClass('control-label').text('Apellido Paterno *');
        $amLabel.addClass('control-label').text('Apellido Materno *');
        $mailLabel.addClass('control-label').text('Correo Electrónico *');
        $paisLabel.addClass('control-label').text('País *');
        $telLabel.addClass('control-label').text('Teléfono *');

        $rutD.append($rutLabel);
        $nacD.append($nacLabel);
        $nameD.append($nameLabel);
        $apD.append($apLabel);
        $amD.append($amLabel);
        $mailD.append($mailLabel);
        $paisD.append($paisLabel);
        $telD.append($telLabel);

        $rutD.append('<input type="text" class="form-control inrut" id="iNrutpart' + nParticipantes + '" name="irutpart[]" placeholder="Ingresa el documento de identificación del participante" required>')
            .append('<i class="fa form-control-feedback" id="iconrutpart' + nParticipantes + '"></i>')
            .append('<p class="help-block">Si es RUT, ingresa con formato completo (Ej. 12.345.678-9)</p>');
        $nacD.append('<input type="text" class="form-control inedad" id="iNedadpart' + nParticipantes + '" name="iedadpart[]" placeholder="Ingresa la edad del participante" required>')
            .append('<i class="fa form-control-feedback" id="iconedadpart' + nParticipantes + '"></i>');

        $nameD.append('<input type="text" class="form-control inname" id="iNnamepart' + nParticipantes + '" name="inamepart[]" placeholder="Ingresa los nombres del participante" required>')
            .append('<i class="fa form-control-feedback" id="iconnamepart' + nParticipantes + '"></i>');
        $apD.append('<input type="text" class="form-control inap" id="iNlnppart' + nParticipantes + '" name="ilnppart[]" placeholder="Ingresa el apellido paterno" required>')
            .append('<i class="fa form-control-feedback" id="iconlnppart' + nParticipantes + '"></i>');
        $amD.append('<input type="text" class="form-control inam" id="iNlnmpart' + nParticipantes + '" name="ilnmpart[]" placeholder="Ingresa el apellido materno" required>')
            .append('<i class="fa form-control-feedback" id="iconlnmpart' + nParticipantes + '"></i>');

        $mailD.append('<div class="input-group"><div class="input-group-addon"><i class="fa fa-envelope"></i></div><input type="text" class="form-control inemail" id="iNemailpart' + nParticipantes + '" name="iemailpart[]" placeholder="Ingresa e-mail de contacto" required></div>')
            .append('<i class="fa form-control-feedback" id="iconemailpart' + nParticipantes + '"></i>');
        $paisD.append('<select class="form-control" id="iNcodpart' + nParticipantes + '" name="icodpart[]"><option value="+56">Chile (56)</option><option value="+54">Argentina (54)</option></select>');
        $telD.append('<div class="input-group"><div class="input-group-addon"><i class="fa fa-phone"></i></div><input type="text" class="form-control inphone" id="iNphonepart' + nParticipantes + '" name="iphonepart[]" placeholder="Ingresa teléfono de contacto" required></div>')
            .append('<i class="fa form-control-feedback" id="iconphonepart' + nParticipantes + '"></i>')
            .append('<p class="help-block">Teléfono de nueve dígitos (Ej. 912345678)</p>');

        $divP.append('<hr>')
            .append('<div class="pull-right"><button type="button" class="btn btn-sm btn-danger del-part" id="del-part' + nParticipantes + '"><i class="fa fa-minus"></i> Eliminar</button></div>');
        $row1.addClass('row')
            .append($rutD)
            .append($nacD);
        $divP.append($row1);
        $row2.addClass('row')
            .append($nameD)
            .append($apD)
            .append($amD);
        $divP.append($row2);
        $row3.addClass('row')
            .append($mailD)
            .append($paisD)
            .append($telD);
        $divP.append($row3);
        $divP.prop('id', 'part' + nParticipantes);
        $('#divPart').append($divP);
        nParticipantes++;
        nTotal++;
    });

    $('#divPart').on('click', '.del-part', function () {
        var id = $(this).attr('id').split('part');
        $('#part' + id[1]).remove();
        nTotal--;
    }).on('change', '.inrut, .inname, .inap, .inam', function () {
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
    });

    $('.iextra').on('ifChanged', function () {
        var id = $(this).attr('id').split('extra');

        if ($(this).prop('checked')) {
            $('#iNcant' + id[1]).prop('disabled', false);
        } else {
            $('#iNcant' + id[1]).val(1).prop('disabled', true);
        }
    });

    $('#iNaloja').change( function() {
        $('#aloja-detail p').each( function() {
            $(this).html('');
        });

        if ($.trim($(this).val()) !== '') {
            $('#aloja-detail').css('display', 'block');
            $('#galoja').removeClass('has-error').addClass('has-success');

            $.ajax({type: 'POST',
                url: 'trips/ajax.getAccomodationDetails.php',
                dataType: 'json',
                data: {id: $(this).val()}
            }).done(function (r) {
                if (r.alo_id !== '') {
                    $('#aloja-detail').css('display', 'block');
                    $('#aloja-descrip').html(r.alo_descripcion);
                    $('#aloja-address').html(r.alo_direccion);
                    $('#aloja-rooms').html(r.alo_rooms);
                    $('#aloja-baths').html(r.alo_baths);
                    $('#aloja-pic').html('<a href="' + r.alo_pic + '" target="_blank"><img width="200px" src="' + r.alo_pic + '">');
                }
            });
        } else {
            $('#galoja').removeClass('has-success');
            $('#aloja-detail').css('display', 'none');
        }
    });


    $('#btn-people').click(function () {
        var chk = true;

        $('#tripInfo').find('input, select').each(function () {
            if ($(this).prop('required') && $.trim($(this).val()) === '') {
                chk = false;
                var id = $(this).attr('id').split('N');
                $('#g' + id[1]).addClass('has-error');
                $('#icon' + id[1]).addClass('fa-remove')
            }
        });

        if (chk) {
            $('#sectionTabs a[href="#people"]').tab('show');
        } else {
            var msg = 'Por favor, completa los campos obligatorios marcados con error.';
            swal("Error!", msg, "error");
        }
    });

    $('#btn-extras').click(function () {
        var chk = true;

        $('#peopleInfo').find('input').each(function () {
            if ($(this).prop('required') && $.trim($(this).val()) === '') {
                chk = false;
                var id = $(this).attr('id').split('N');
                $('#g' + id[1]).addClass('has-error');
                $('#icon' + id[1]).addClass('fa-remove')
            }
        });

        if (chk) {
            $('.icantExtras').each(function () {
                var id = $(this).attr('id');
                var ext = $(this).attr('id').split('cant').pop();

                if ($('#' + id + ' option').length !== nTotal) {
                    $(this).html('');
                    for (var i = 1; i <= nTotal; i++) {
                        if (i === parseInt($('#iNcantDB' + ext).val(), 10))
                            $(this).append($('<option>', {value: i, text: i, selected: true}));
                        else
                            $(this).append($('<option>', {value: i, text: i}));
                    }
                }
            });

            $('#sectionTabs a[href="#extras"]').tab('show');
        } else {
            var msg = 'Por favor, completa los campos obligatorios marcados con error.';
            swal("Error!", msg, "error");
        }
    });

    $('#btn-homes').click(function () {
        $('#sectionTabs a[href="#homes"]').tab('show');
    });

    $('#btn-summary').click(function () {
        if ($('#iNaloja').val() === '') {
            swal("Error!", 'Debes elegir un alojamiento para completar el proceso.', "error");
        } else {
            var tmp = $('#iNdates').val().split(' hasta ');

            $('#date-ini').html(tmp[0]);
            $('#date-end').html(tmp[1]);
            $('#city-o').html($('#iNocity :selected').text());
            $('#city-d').html($('#iNdcity :selected').text());
            $('#cot-data').html($('#iNname').val() + ' ' + $('#iNlastnamep').val() + ' ' + $('#iNlastnamem').val());
            $('#cot-email').html($('#iNemail').val());
            $('#cot-phone').html('(' + $('#iNcod :selected').val() + ')' + $('#iNphone').val());
            $('#num-part').html(nTotal);

            $('#participantes-data').html('');
            $('#par1-data').html($('#iNnamepart1').val() + ' ' + $('#iNlnppart1').val() + ' ' + $('#iNlnmpart1').val());
            $('#par1-rut').html($('#iNrutpart1').val());
            $('#par1-edad').html($('#iNedadpart1').val());
            $('#par1-email').html($('#iNemailpart1').val());
            $('#par1-phone').html('(' + $('#iNcodpart1 :selected').val() + ')' + $('#iNphonepart1').val());
            for (var i = 2; i < nParticipantes; i++) {
                if ($("#iNrutpart" + i).length !== 0) {
                    var $dl = $('<dl>');
                    $dl.addClass('dl-horizontal');
                    $dl.append('<div class="row">' +
                        '<div class="col-md-12 col-lg-12">' +
                        '<dt>Nombre completo</dt>' +
                        '<dd id="par' + i + '-data">' + $('#iNnamepart' + i).val() + ' ' + $('#iNlnppart' + i).val() + ' ' + $('#iNlnmpart' + i).val() + '</dd>' +
                        '</div>' +
                        '</div>');
                    $dl.append('<div class="row">' +
                        '<div class="col-md-6">' +
                        '<dt>RUT/DNI</dt>' +
                        '<dd id="par' + i + '-rut">' + $('#iNrutpart' + i).val() + '</dd>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '<dt>Edad</dt>' +
                        '<dd id="par' + i + '-edad">' + $('#iNedadpart' + i).val() + '</dd>' +
                        '</div>' +
                        '</div>');
                    $dl.append('<div class="row">' +
                        '<div class="col-md-6">' +
                        '<dt>Correo electrónico</dt>' +
                        '<dd id="par' + i + '-email">' + $('#iNemailpart' + i).val() + '</dd>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '<dt>Teléfono</dt>' +
                        '<dd id="par' + i + '-phone">' + '(' + $('#iNcodpart' + i + ' :selected').val() + ')' + $('#iNphonepart' + i).val() + '</dd>' +
                        '</div>' +
                        '</div>');

                    $('#participantes-data').append($dl);
                }
            }

            $('#summary-extras, #summary-no-extras').css('display', 'none');
            $('#table-extras > tbody').html('');
            var extras = 0;
            $('.iextra').each(function () {
                var id = $(this).attr('id').split('extra');

                if ($(this).prop('checked')) {
                    extras++;
                    $('#table-extras > tbody:last-child').append('<tr><td>' + $('#iNextraname' + id[1]).html() + '</td><td class="text-center">' + $('#iNcant' + id[1]).val() + '</td></tr>');
                }
            });
            (extras > 0) ? $('#summary-extras').css('display', 'block') : $('#summary-no-extras').css('display', 'block');

            $('#alo-nombre').html($('#iNaloja :selected').text());
            $('#alo-direccion').html($('#aloja-address').html());
            $('#alo-descripcion').html($('#aloja-descrip').html());

            $('#sectionTabs a[href="#summary"]').tab('show');
        }
    });

    $('#btn-back-trip').click(function () {
        $('#sectionTabs a[href="#trip"]').tab('show');
    });
    $('#btn-back-people').click(function () {
        $('#sectionTabs a[href="#people"]').tab('show');
    });
    $('#btn-back-extras').click(function () {
        $('#sectionTabs a[href="#extras"]').tab('show');
    });
    $('#btn-back-homes').click(function () {
        $('#sectionTabs a[href="#homes"]').tab('show');
    });

    $('#formNewTrip').submit(function () {
        $(this).ajaxSubmit(options);
        return false;
    });
})
;