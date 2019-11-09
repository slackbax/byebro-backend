$(document).ready(function () {
    function recalculate() {
        var no_quota = 0,
            total_nq = 0,
            total = 0;

        $('.quota').each( function () {
            var id = $(this).attr('id').split('_').pop();

            if ($('#opt_' + id).prop('checked')) {
                no_quota++;
                total_nq += parseInt($(this).val().replace(/\./g, ''),10);
                total += parseInt($(this).val().replace(/\./g, ''),10);
            }
        });

        var nueva_cuota = Math.floor((t_viaje - total_nq) / (n_par - no_quota));

        $('.quota').each( function () {
            var id = $(this).attr('id').split('_').pop();

            if (!$('#opt_' + id).prop('checked')) {
                $('#iNamount_' + id).val(format('#.##0,', nueva_cuota));
                total += nueva_cuota;
            }
        });

        $('#iNsum').val(format('#.##0,', total));
    }

    function validateForm() {
        $('#submitLoader').css('display', 'inline-block');
        return true;
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type) {
            new Noty({
                text: '<b>¡Éxito!</b><br>Las cuotas han sido asignadas correctamente.<br>Redirigiendo a la lista de viajes registrados...',
                type: 'success'/*,
                callbacks: {
                    afterClose: function () {
                        document.location.replace('index.php?section=trips&sbs=managetrips');
                    }
                }*/
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
    }

    var options = {
        url: 'trips/ajax.insertQuotas.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    $('#submitLoader').css('display', 'none');
    var n_par = parseInt($('#iNnpart').val(), 10),
        t_viaje = parseInt($('#iNtotal').val().replace(/\./g, ''), 10);

    $('.opt-quota').on('ifChanged', function () {
        var id = $(this).attr('id').split('_').pop();

        if ($(this).prop('checked')) {
            $('#iNamount_' + id).prop('readonly', false);
        } else {
            $('#iNamount_' + id).prop('readonly', true);
            recalculate();
        }
    });

    $('.quota').change( function () {
        var cur = $(this).val().replace(/\./g, '');
        recalculate();
        $(this).val(format('#.##0,', cur));
    });

    $('#formNewQuota').submit(function () {
        $(this).ajaxSubmit(options);
        return false;
    });
});