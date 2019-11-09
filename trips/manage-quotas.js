$(document).ready(function () {
    var tableQuot = $("#ttrips").DataTable({
        "columns": [
            {width: "30px", className: "text-right"},
            {className: "text-center"},
            null,
            null,
            {className: "text-center"},
            {className: "text-center"},
            null,
            {className: "text-right"},
            {width: "80px", className: "text-center"},
            {"orderable": false, width: "75px", className: "text-center"}],
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
            url: 'trips/ajax.getServerTripQuotas.php',
            type: 'GET',
            length: 20
        }
    });

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
    });
});