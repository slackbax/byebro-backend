$(document).ready(function () {

    function validateForm() {
        $('#submitLoader').css('display', 'inline-block');
        var _pass = true;

        $('.pincode-input-text').each(function () {
            if ($(this).val() === '') {
                $('#submitLoader').css('display', 'none');
                $('#gpassword').removeClass('has-success').addClass('has-error');
                _pass = false;

                swal({
                    title: "Error!",
                    text: "Debes ingresar una contraseña para el usuario.",
                    type: "error"
                });
            }
        });

        return _pass;
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type === true) {
            new Noty({
                text: '<b>¡Éxito!</b><br> El usuario ha sido guardado correctamente.',
                type: 'success'
            }).show();

            $('#btnClear').click();
            $('#formNewUser').clearForm();
            $('input:file').MultiFile('reset');
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
        url: 'admin/users/ajax.insertUser.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    $('#inputPincode').pincodeInput({
        inputs: 4,
        complete: function () {
            $('#gpassword').removeClass('has-error').addClass('has-success');
        }
    });
    $('#submitLoader').css('display', 'none');

    $('#iNusername').change(function () {
        $('#gusername').removeClass('has-error has-success');
        $('#iconUsername').removeClass('fa-remove fa-check');

        if ($(this).val() !== '') {
            $.ajax({
                type: 'POST',
                url: 'admin/users/ajax.existUsername.php',
                dataType: 'json',
                data: {username: $(this).val()}
            }).done(function (r) {
                if (r.msg === true) {
                    $('#gusername').addClass('has-error');
                    $('#iconUsername').addClass('fa-remove');
                    $('#iNusername').val('');

                    swal({
                        title: "Error!",
                        text: "El nombre de usuario elegido ya se encuentra registrado.",
                        type: "error"
                    });
                } else {
                    $('#gusername').addClass('has-success');
                    $('#iconUsername').addClass('fa-check');
                }
            });
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

    $('#iNname, #iNlastnamep, #iNlastnamem, #iNpassword').change(function () {
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
        $('#gname, #glastnamep, #glastnamem, #gemail, #gusername, #gpassword').removeClass('has-error has-success');
        $('#iconname, #iconlastnamep, #iconlastnamem, #iconEmail, #iconUsername').removeClass('fa-remove fa-check');
    });

    $('#formNewUser').submit(function () {
        $(this).ajaxSubmit(options);
        return false;
    });
});