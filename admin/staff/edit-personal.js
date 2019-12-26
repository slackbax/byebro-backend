$(document).ready(function () {

    var n_destinos = parseInt($('#iNndest').val(),10); var d_ind = parseInt($('#iNndest').val(),10);

    function validateForm() {
        var msg = '';

        if (n_destinos > 0) {
            $('#submitLoader').css('display', 'inline-block');
            return true;
        }
        else {
            if (n_destinos === 0) { msg += '<br>- Debes agregar al menos una ciudad al personal.' }
            new Noty({
                text: '<b>¡Error!</b><br>Por favor, corrije los campos con errores.' + msg,
                type: 'error'
            }).show();
            return false;
        }
    }

    function showResponse(response) {
        $('#submitLoader').css('display', 'none');

        if (response.type) {
            new Noty({
                text: '<b>¡Éxito!</b><br> El personal ha sido guardado correctamente.',
                type: 'success'
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
        url: 'admin/staff/ajax.editPersonal.php',
        type: 'post',
        dataType: 'json',
        beforeSubmit: validateForm,
        success: showResponse
    };

    $('#submitLoader').css('display', 'none');

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

    $('#iNusername').devbridgeAutocomplete({
        minChars: 1,
        autoSelectFirst: true,
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'No hay resultados',
        lookup: function (query, done) {
            $('#iNusernameid').val('');
            $('#gusername').removeClass('has-error has-success');
            $('#iconusername').removeClass('fa-remove fa-check');

            $.ajax({
                url: 'admin/staff/ajax.getUsernameResults.php',
                type: 'post',
                dataType: 'json',
                data: {string: query}
            }).done(function (d) {
                //console.log(d);
                if(d.length > 0) {
                    var result = {suggestions: d};
                    done(result);
                } else{
                    $('#gusername').addClass('has-error');
                    $('#iconusername').addClass('fa-remove');
                }
            });
        },
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (selected) {
            $('#gusername').addClass('has-success');
            $('#iconusername').addClass('fa-check');

            var tmp = selected.value.split('] ');
            var id = tmp[0].replace('[', '');
            $('#iNusernameid').val(id);
        },
        onInvalidateSelection: function () {
            $('#iNusername, #iNusernameid').val('');
            $('#gusername').removeClass('has-error has-success');
            $('#iconusername').removeClass('fa-remove fa-check');
        }
    });

    $('#btnAddCity').click(function () {
        if ($.trim($('#iNdcity').val()) !== '') {
            var chk = false;

            $('.idestino').each(function () {
                if ($(this).val() === $('#iNdcity').val()) {
                    chk = true;
                }
            });

            if (!chk) {
                var destiny = $('#iNdcity').val();
                var destinyName = $("#iNdcity option:selected").text();

                var $row = $('<div>');
                $row.attr('id', 'row' + n_destinos).addClass('row');

                var $nameDest = $('<div>');
                var $dl = $('<div>');
                $nameDest.addClass('form-group col-xs-11');
                $dl.addClass('form-group col-xs-1 no-mb text-center');

                var txt_unit = '';
                $row.append('<input type="hidden" class="idestino" name="iid[]" id="iNidest' + n_destinos + '" value="' + destiny + '">');

                txt_unit += destinyName;

                var $name = $('<p>');
                $name.addClass('form-control-static').text(txt_unit);
                $nameDest.append($name);
                $row.append($nameDest);

                $dl.append('<button type="button" class="btn btn-xs btn-danger btnDel" name="btn_' + n_destinos + '" id="btndel_' + n_destinos + '"><i class="fa fa-close"></i></button>');
                $row.append($dl);

                $('#divDestiny-inner').append($row);
                $('#divDestiny').css('display', 'block');
                n_destinos++; d_ind++;
            }
            else {
                swal("Error", "La ciudad elegida ya se encuentra en la lista de ciudades agregadas.", "error");
                $('#iNdcity').val('');
            }
        }
    });

    $('#divDestiny').on('click', '.btnDel', function () {
        var idn = $(this).attr('id').split('_');
        $('#row' + idn[1]).remove();
        d_ind--;

        if (d_ind === 0) {
            n_destinos = 0;
        }
    });

    $('#iNname, #iNlastnamep, #iNlastnamem, #iNphone').change(function () {
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
        $('#gname, #glastnamep, #glastnamem, #gemail, #gphone, #gusername').removeClass('has-error has-success');
        $('#iconname, #iconlastnamep, #iconlastnamem, #iconEmail, #iconphone, #iconusername').removeClass('fa-remove fa-check');
        $('#divDestiny-inner').html('');
        $('#divDestiny').css('display', 'none');
        n_destinos = d_ind = 0;
    });

    $('#formEditPersonal').submit(function () {
        $(this).ajaxSubmit(options);
        return false;
    });
});