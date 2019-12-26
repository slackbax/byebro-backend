<div id="peopleInfo">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-primary"><i class="fa fa-user"></i> Participantes del viaje</h3>
        </div>

        <div class="box-body">
            <p class="lead">Mensaje de introducción</p>

            <div id="divPart">
                <div id="part1">
                    <div class="row">
                        <div class="form-group col-xs-4">
                            <label class="label-checkbox">
                                <input class="minimal incharge" type="checkbox" name="incharge" id="iNincharge" value="true" checked disabled>
                                Encargado del Viaje
                            </label>
                        </div>

                        <div class="form-group col-xs-3 col-xs-offset-5 text-right">
                            <button type="button" class="btn btn-warning btn-sm" id="btn-copy-cot"><i class="fa fa-copy"></i> Copiar cotizante como encargado</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-lg-3 has-feedback" id="grutpart1">
                            <label class="control-label" for="iNrutpart1">RUT/DNI *</label>
                            <input type="text" class="form-control inrut" id="iNrutpart1" name="irutpart[]" placeholder="Ingresa el documento de identificación del participante" required>
                            <i class="fa form-control-feedback" id="iconrutpart1"></i>
                            <p class="help-block">Si es RUT, ingresa con formato completo (Ej. 12.345.678-9)</p>
                        </div>

                        <div class="form-group col-md-6 col-lg-3 has-feedback" id="gedadpart1">
                            <label class="control-label" for="iNedadpart1">Edad *</label>
                            <input type="text" class="form-control inedad" id="iNedadpart1" name="iedadpart[]" placeholder="Ingresa la edad del participante" required>
                            <i class="fa form-control-feedback" id="iconedadpart1"></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-lg-3 has-feedback" id="gnamepart1">
                            <label class="control-label" for="iNnamepart1">Nombres *</label>
                            <input type="text" class="form-control inname" id="iNnamepart1" name="inamepart[]" placeholder="Ingresa los nombres del participante" required>
                            <i class="fa form-control-feedback" id="iconnamepart1"></i>
                        </div>

                        <div class="form-group col-md-6 col-lg-3 has-feedback" id="glnppart1">
                            <label for="iNlnppart1">Apellido Paterno *</label>
                            <input type="text" class="form-control inap" id="iNlnppart1" name="ilnppart[]" placeholder="Ingresa el apellido paterno" required>
                            <i class="fa form-control-feedback" id="iconlnppart1"></i>
                        </div>

                        <div class="form-group col-md-6 col-lg-3 has-feedback" id="glnmpart1">
                            <label class="control-label" for="iNlnmpart1">Apellido Materno *</label>
                            <input type="text" class="form-control inam" id="iNlnmpart1" name="ilnmpart[]" placeholder="Ingresa el apellido materno" required>
                            <i class="fa form-control-feedback" id="iconlnmpart1"></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-lg-3 has-feedback" id="gemailpart1">
                            <label class="control-label" for="iNemailpart1">Correo Electrónico *</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <input type="text" class="form-control inemail" id="iNemailpart1" name="iemailpart[]" placeholder="Ingresa e-mail de contacto" required>
                            </div>
                            <i class="fa form-control-feedback" id="iconemailpart1"></i>
                        </div>

                        <div class="form-group col-md-2 col-lg-1">
                            <label class="control-label" for="iNcodpart1">País *</label>
                            <select class="form-control" id="iNcodpart1" name="icodpart[]">
                                <option value="+56">Chile (56)</option>
                                <option value="+54">Argentina (54)</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 col-lg-3 has-feedback" id="gphonepart1">
                            <label class="control-label" for="iNphonepart1">Teléfono *</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <input type="text" class="form-control inphone" id="iNphonepart1" name="iphonepart[]" placeholder="Ingresa teléfono de contacto" required>
                            </div>
                            <i class="fa form-control-feedback" id="iconphonepart1"></i>
                            <p class="help-block">Teléfono de nueve dígitos (Ej. 912345678)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pull-right">
                <button type="button" class="btn btn-sm btn-warning" id="btn-add-part"><i class="fa fa-plus"></i> Agregar participante</button>
            </div>
        </div>

        <div class="box-footer">
            <div class="row">
                <div class="col-xs-6">
                    <button type="button" class="btn btn-default" id="btn-back-trip"><i class="fa fa-chevron-circle-left"></i> Volver al paso 1</button>
                </div>
                <div class="col-xs-6 text-right">
                    <button type="button" class="btn btn-info" id="btn-extras">Siguiente paso: Adicionales <i class="fa fa-chevron-circle-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>