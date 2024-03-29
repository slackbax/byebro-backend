$(document).ready(function () {

    function validateForm() {
        var values = true;

        if (CKEDITOR.instances.iNdetalle.getData() === '') {
            swal("Error", "Debes ingresar la descripción del adicional.", "error");
            values = false;
        }

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
                text: '<b>¡Éxito!</b><br>El adicional ha sido guardado correctamente.',
                type: 'success'
            }).show();

            $('#btnClear').click();
            $('#formNewExtra').clearForm();
            $('#iNgroup').prop('checked', false).iCheck('update');
            CKEDITOR.instances.iNdetalle.setData('');
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
        url: 'admin/extras/ajax.insertExtra.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    CKEDITOR.replace('iNdetalle', {
        toolbarGroups: [
            {
                'name': 'basicstyles',
                'groups': ['basicstyles']
            },
            {
                'name': 'links',
                'groups': ['links']
            },
            {
                'name': 'paragraph',
                'groups': ['list', 'blocks']
            },
            {
                'name': 'document',
                'groups': ['mode']
            },
            {
                'name': 'insert',
                'groups': ['insert']
            },
            {
                'name': 'styles',
                'groups': ['styles']
            }
        ],
        removeButtons: 'Source,Image,Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar'
    });

    $('#submitLoader').css('display', 'none');

    $('#iNname').change(function () {
        $('#gname').removeClass('has-error has-success');
        $('#iconname').removeClass('fa-remove fa-check');

        if ($(this).val() !== '') {
            $.ajax({
                type: 'POST',
                url: 'admin/extras/ajax.existExtra.php',
                dataType: 'json',
                data: {name: $(this).val()}
            }).done(function (r) {
                if (r.msg === true) {
                    $('#gname').addClass('has-error');
                    $('#iconname').addClass('fa-remove');
                    $('#iNname').val('');

                    swal({
                        title: "Error!",
                        text: "El nombre del adicional elegido ya se encuentra registrado.",
                        type: "error"
                    });
                } else {
                    $('#gname').addClass('has-success');
                    $('#iconname').addClass('fa-check');
                }
            });
        }
    });

    $('#btnClear').click(function () {
        $('#gname').removeClass('has-error has-success');
        $('#iconname').removeClass('fa-remove fa-check');
    });

    $('#formNewExtra').submit(function () {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        $(this).ajaxSubmit(options);
        return false;
    });
});