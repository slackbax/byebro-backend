$(document).ready(function () {
    var tableQuot = $('#ttrips').DataTable({
        columns: [
            {width: '30px', className: 'text-right'},
            {className: 'text-center'},
            null,
            null,
            {className: 'text-center'},
            {className: 'text-center'},
            null,
            {className: 'text-right'},
            {width: '80px', className: 'text-center'},
            {orderable: false, width: '75px', className: 'text-center'}],
        order: [[0, 'desc']],
        processing: true,
        language: {
            'processing': '<i class="fa fa-spinner fa-spin"></i>'
        },
        buttons: [
            {
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            }
        ],
        serverSide: true,
        ajax: {
            url: 'trips/ajax.getServerTripsStaff.php',
            type: 'GET',
            length: 20
        }
    });

    function validateForm() {
        $('#submitLoader').css('display', 'inline-block');
        return true;
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type) {
            new Noty({
                text: '<b>¡Éxito!</b><br> El personal ha sido asignado correctamente.',
                type: 'success'
            }).show();

            tableQuot.ajax.reload();
            $('#tstaff tbody').html('');
            $('#iNname, #iNcargo').val('').change();

            $.ajax({
                url: 'trips/ajax.getTripStaff.php',
                type: 'POST',
                dataType: 'json',
                data: {id: $('#iid').val()}
            }).done(function (d) {
                $.each(d, function (k, v) {
                    var $row = $('<tr>');
                    $row.append('<td>' + v.per_nombres + ' ' + v.per_ap + ' ' + v.per_am + '</td><td>' + v.car_nombre + '</td><td>' + getDateHourBD(v.car_registro) + '</td>');
                    $('#tstaff tbody').append($row);
                });

                if (d.length === 0) {
                    var $row = $('<tr>');
                    $row.append('<td colspan="3"><em>No se ha asignado personal a este viaje.</em></td>');
                    $('#tstaff tbody').append($row);
                }
            });
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
        url: 'trips/ajax.assignStaff.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    $('#submitLoader').css('display', 'none');

    $('#ttrips').on('click', '.tripDetails', function () {
        var uid = $(this).attr('id').split("_").pop();
        $('dd').each(function () {
            $(this).html('');
        });
        $('#summary-extras, #summary-no-extras').css('display', 'none');
        $('#table-extras tbody').empty();

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
            $('#alo-nombre').html(d.aloja.alo_nombre);
            $('#alo-direccion').html(d.aloja.alo_direccion);
            $('#alo-descripcion').html(d.aloja.alo_descripcion);

            if (d.extras.length > 0) {
                $('#summary-extras').css('display', 'block');
                $.each(d.extras, function (e, v) {
                    $('#table-extras').append('<tr><td>' + v.adi_nombre + '</td><td class="text-center">' + v.adi_cantidad + '</td></tr>');
                })
            } else $('#summary-no-extras').css('display', 'block');
        });
    }).on('click', '.assignStaff', function () {
        $('#iNname').html('').change();
        $('#iNcargo').val('').change();
        $('#tstaff tbody').html('');
        var uid = $(this).attr('id').split("_").pop();
        $('#iid').val(uid);

        $.ajax({
            url: 'trips/ajax.getCityStaff.php',
            type: 'POST',
            dataType: 'json',
            data: {id: uid}
        }).done(function (d) {
            var o = new Option('Selecciona un miembro del staff', '');
            $("#iNname").append(o);

            $.each(d, function (k, v) {
                var o = new Option(v.per_nombres + ' ' + v.per_ap + ' ' + v.per_am, v.per_id);
                $(o).html(v.per_nombres + ' ' + v.per_ap + ' ' + v.per_am);
                $("#iNname").append(o);
            });
        });

        $.ajax({
            url: 'trips/ajax.getTripStaff.php',
            type: 'POST',
            dataType: 'json',
            data: {id: uid}
        }).done(function (d) {
            $.each(d, function (k, v) {
                var $row = $('<tr>');
                $row.append('<td>' + v.per_nombres + ' ' + v.per_ap + ' ' + v.per_am + '</td><td>' + v.car_nombre + '</td><td>' + getDateHourBD(v.car_registro) + '</td>');
                $('#tstaff tbody').append($row);
            });

            if (d.length === 0) {
                var $row = $('<tr>');
                $row.append('<td colspan="3"><em>No se ha asignado personal a este viaje.</em></td>');
                $('#tstaff tbody').append($row);
            }
        });
    });

    $('#iNname, #iNcargo').change(function () {
        var idn = $(this).attr('id').split('N').pop();

        if ($.trim($(this).val()) !== '') {
            $('#g' + idn).removeClass('has-error').addClass('has-success');
        } else {
            $('#g' + idn).removeClass('has-success');
        }
    });

    $('#formNewAssign').submit(function () {
        $(this).ajaxSubmit(options);
        return false;
    });
});