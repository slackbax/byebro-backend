$(document).ready(function () {

    function getCountries() {
        $.ajax({
            type: 'POST',
            url: 'src/rest.allCountries.php',
            dataType: 'json'
        }).done(function (r) {
            $('#iNcountry').html('').append($("<option />").val('').text('Selecciona el país'));
            $.each(r, function(k, v) {
                $('#iNcountry').append($("<option />").val(v.alpha3Code).text(v.name));
            })
        });
    }

    function validateForm() {
        $('#submitLoader').css('display', 'inline-block');
        return true;
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type === true) {
            new Noty({
                text: '<b>¡Éxito!</b><br> La ciudad ha sido guardada correctamente.',
                type: 'success'
            }).show();

            $('#btnClear').click();
            $('#formNewOCity').clearForm();
            getCountries();
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
        url: 'admin/cities/ajax.insertCity.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    $('#submitLoader').css('display', 'none');
    getCountries();

    $('#iNname').change(function () {
        $('#gname').removeClass('has-error').removeClass('has-success');
        $('#iconname').removeClass('fa-remove fa-check');

        if ($(this).val() !== '') {
            $.ajax({
                type: 'POST',
                url: 'admin/cities/ajax.existCity.php',
                dataType: 'json',
                data: {name: $(this).val(), type: 'd'}
            }).done(function (r) {
                if (r.msg === true) {
                    $('#gname').addClass('has-error');
                    $('#iconname').addClass('fa-remove');
                    $('#iNname').val('');

                    swal({
                        title: "Error!",
                        text: "La ciudad ingresada ya se encuentra registrada.",
                        type: "error"
                    });
                } else {
                    $('#gname').addClass('has-success');
                    $('#iconname').addClass('fa-check');
                }
            });
        }
    });

    $('#iNcode, #iNcountry').change(function () {
        var idn = $(this).attr('id').split('N');

        if ($.trim($(this).val()) !== '') {
            $('#g' + idn[1]).removeClass('has-error').addClass('has-success');
            $('#icon' + idn[1]).removeClass('fa-remove').addClass('fa-check');
        } else {
            $('#g' + idn[1]).removeClass('has-success');
            $('#icon' + idn[1]).removeClass('fa-check');
        }
    });

    $('#btnClear').click(function () {
        $('#gname, #gcode, #gcountry').removeClass('has-error has-success');
        $('#iconname, #iconcode').removeClass('fa-remove fa-check');
    });

    $('#formNewDCity').submit(function () {
        $(this).ajaxSubmit(options);
        return false;
    });
});