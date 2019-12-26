$(document).ready(function () {

    function validateForm() {
        $('#submitLoader').css('display', 'inline-block');
        var _pass = true;

        $('.pincode-input-text').each(function () {
            if ($(this).val() === '') {
                $('#submitLoader').css('display', 'none');
                $('#goldpass, #gnewpass, #gcnpass').removeClass('has-success').addClass('has-error');
                _pass = false;

                swal({
                    title: "Error!",
                    text: "Debes ingresar todos los campos de contraseña para cambiarla.",
                    type: "error"
                });
            }
        });

        return _pass;
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type) {
            new Noty({
                text: '<b>¡Éxito!</b><br>Las contraseñas han sido guardadas correctamente.<br>Volviendo a la pantalla de inicio...',
                type: 'success',
                callbacks: {
                    afterClose: function () {
                        location.href = 'index.php';
                    }
                }
            }).show();

            $('#btnClear').click();
            $('#formChangePass').clearForm();
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
        url: 'admin/users/ajax.editPassword.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    $('#iNoldpass').pincodeInput({
        inputs: 4,
        complete: function (value) {
            $('#goldpass').removeClass('has-error').removeClass('has-success');

            $.ajax({
                type: 'POST',
                url: 'admin/users/ajax.checkPass.php',
                dataType: 'json',
                data: {id: $('#uid').val(), pass: value}
            }).done(function (r) {
                if (r.msg === false) {
                    $('#goldpass').addClass('has-error');
                    $('#iNoldpass').pincodeInput().data('plugin_pincodeInput').clear();

                    swal("Error!", "La contraseña de acceso actual ingresada no es correcta.", "error");
                } else {
                    $('#goldpass').addClass('has-success');
                }
            });
        }
    });
    $('#iNnewpass').pincodeInput({
        inputs: 4,
        complete: function (value) {
            $('#gnewpass').removeClass('has-error').removeClass('has-success');

            if ($.trim(value) !== '' && $.trim($('#iNcnpass').val()) !== '') {
                if (value !== $('#iNcnpass').val()) {
                    swal("Error!", "Las contraseñas de acceso ingresadas no coinciden.", "error");

                    $('#gnewpass, #gcnpass').removeClass('has-success').addClass('has-error');
                    $('#iNcnpass').pincodeInput().data('plugin_pincodeInput').clear();
                } else {
                    $('#gnewpass, #gcnpass').removeClass('has-error').addClass('has-success');
                }
            }
        }
    });
    $('#iNcnpass').pincodeInput({
        inputs: 4,
        complete: function (value) {
            $('#gcnpass').removeClass('has-error').removeClass('has-success');

            if ($.trim(value) !== '' && $.trim($('#iNnewpass').val()) !== '') {
                if (value !== $('#iNnewpass').val()) {
                    swal("Error!", "Las contraseñas de acceso ingresadas no coinciden.", "error");

                    $('#gnewpass, #gcnpass').removeClass('has-success').addClass('has-error');
                    $('#iNcnpass').pincodeInput().data('plugin_pincodeInput').clear();
                } else {
                    $('#gnewpass, #gcnpass').removeClass('has-error').addClass('has-success');
                }
            }
        }
    });

    $('#submitLoader').css('display', 'none');

    $('#btnClear').click(function () {
        $('#goldpass, #gnewpass, #gcnpass').removeClass('has-error').removeClass('has-success');
    });

    $('#formChangePass').submit(function () {
        $(this).ajaxSubmit(options);
        return false;
    });
});