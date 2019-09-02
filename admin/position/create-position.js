$(document).ready(function () {

    function validateForm() {
        var values = true;

        if (CKEDITOR.instances.iNdetalle.getData() === '') {
            swal("Error", "Debes ingresar la descripción del cargo.", "error");
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

        if (response.type === true) {
            new Noty({
                text: '<b>¡Éxito!</b><br> El cargo ha sido guardado correctamente.',
                type: 'success'
            }).show();

            $('#btnClear').click();
            $('#formNewPosition').clearForm();
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
        url: 'admin/position/ajax.insertPosition.php',
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
                url: 'admin/position/ajax.existPosition.php',
                dataType: 'json',
                data: {name: $(this).val()}
            }).done(function (r) {
                if (r.msg === true) {
                    $('#gname').addClass('has-error');
                    $('#iconname').addClass('fa-remove');
                    $('#iNname').val('');

                    swal({
                        title: "Error!",
                        text: "El nombre de cargo elegido ya se encuentra registrado.",
                        type: "error"
                    });
                } else {
                    $('#gname').addClass('has-success');
                    $('#iconname').addClass('fa-check');
                }
            });
        }
    });

    $('#iNdetalle').change(function () {
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
        $('#gname').removeClass('has-error has-success');
        $('#iconname').removeClass('fa-remove fa-check');
    });

    $('#formNewPosition').submit(function () {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        $(this).ajaxSubmit(options);
        return false;
    });
});