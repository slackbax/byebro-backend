$(document).ready(function () {

    function validateForm() {
        var values = true;

        if (CKEDITOR.instances.iNdetalle.getData() === '') {
            swal("Error", "Debes ingresar la descripción del alojamiento.", "error");
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
                text: '<b>¡Éxito!</b><br> El alojamiento ha sido guardado correctamente.',
                type: 'success'
            }).show();

            $('#pLink').attr('href', response.msg);
            $('#pImg').attr('src', response.msg);
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
        url: 'admin/accomodations/ajax.editAccomodation.php',
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

    $('#iNname, #iNaddr, #iNrooms, #iNbaths, #iNcity').change(function () {
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
        $('#gname, #gaddr, #grooms, #gbaths, #gcity').removeClass('has-error has-success');
        $('#iconname, #iconaddr, #iconrooms, #iconbaths').removeClass('fa-remove fa-check');
    });

    $('#formNewAccomodation').submit(function () {
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        $(this).ajaxSubmit(options);
        return false;
    });
});