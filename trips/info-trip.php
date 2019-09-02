<div id="tripInfo">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-primary"><i class="fa fa-plane"></i> Detalles de viaje</h3>
        </div>

        <div class="box-body">
            <p class="lead">Mensaje de introducción</p>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback has-success" id="gdates">
                    <label class="control-label" for="iNdates">Fechas de viaje *</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="iNdates" name="idates" required>
                        <input type="hidden" id="iNstartDate" value="<?php echo getDateToForm($c_d->cot_fecha_ini) ?>">
                        <input type="hidden" id="iNendDate" value="<?php echo getDateToForm($c_d->cot_fecha_ter) ?>">
                        <i class="fa fa-check form-control-feedback" id="icondates"></i>
                    </div>
                </div>
            </div>

            <!--
            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback" id="gocountry">
                    <label class="control-label" for="iNocountry">País de origen *</label>
                    <select class="form-control" id="iNocountry" name="iocountry" required>
                        <option>Cargando países...</option>
                    </select>
                </div>

                <div class="form-group col-md-6 col-lg-4 has-feedback" id="gdcountry">
                    <label class="control-label" for="iNdcountry">País de destino *</label>
                    <select class="form-control" id="iNdcountry" name="idcountry" required>
                        <option>Cargando países...</option>
                    </select>
                </div>
            </div>
            -->

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback has-success" id="gocity">
                    <label class="control-label" for="iNocity">Ciudad de origen *</label>
                    <select class="form-control" id="iNocity" name="iocity" readonly>
                            <option value="<?php echo $c_d->cio_id ?>"><?php echo $c_d->cio_nombre ?></option>
                    </select>
                </div>

                <div class="form-group col-md-6 col-lg-4 has-feedback has-success" id="gdcity">
                    <label class="control-label" for="iNdcity">Ciudad de destino *</label>
                    <select class="form-control" id="iNdcity" name="idcity" readonly>
                        <option value="<?php echo $c_d->cid_id ?>"><?php echo $c_d->cid_nombre ?></option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback has-success" id="gcost">
                    <label class="control-label" for="icost">Valor *</label>
                    <input type="text" class="form-control" id="iNcost" name="icost" placeholder="Ingresa el valor del viaje" required value="<?php echo $c_d->cot_valor ?>">
                    <i class="fa fa-check form-control-feedback" id="iconcost"></i>
                </div>
            </div>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title">Datos personales del cotizante</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="form-group col-xs-6 col-md-3 col-lg-2">
                    <label class="label-checkbox">
                        <input type="radio" name="opDNI" id="opt_rut" class="opt-dni minimal" value="rut"<?php if (isRut($c_d->co_rut)): ?> checked<?php endif ?> disabled>
                        RUT
                    </label>
                </div>

                <div class="form-group col-xs-6 col-md-9 col-lg-10">
                    <label class="label-checkbox">
                        <input type="radio" name="opDNI" id="opt_dni" class="opt-dni minimal" value="otro"<?php if (!isRut($c_d->co_rut)): ?> checked<?php endif ?> disabled>
                        Otro (DNI, Pasaporte, etc.)
                    </label>
                </div>
            </div>

            <div class="row">
                <?php if (isRut($c_d->co_rut)): ?>
                    <div class="form-group col-md-6 col-lg-3 has-feedback has-success" id="grut">
                        <label class="control-label" for="irut">RUT *</label>
                        <input type="text" class="form-control" id="iNrut" name="irut" placeholder="Ingresa el RUT del cotizante" readonly value="<?php echo $c_d->co_rut ?>">
                        <i class="fa fa-check form-control-feedback" id="iconrut"></i>
                    </div>
                <?php else: ?>
                    <div class="form-group col-md-6 col-lg-3 has-feedback has-success" id="gdni">
                        <label class="control-label" for="idni">DNI *</label>
                        <input type="text" class="form-control" id="iNdni" name="idni" placeholder="Ingresa el documento de identificación del cotizante" readonly value="<?php echo $c_d->co_rut ?>">
                        <i class="fa fa-check form-control-feedback" id="icondni"></i>
                    </div>
                <?php endif ?>
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback has-success" id="gname">
                    <label class="control-label" for="iname">Nombres *</label>
                    <input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingresa los nombres del cotizante" readonly value="<?php echo $c_d->co_nombres ?>">
                    <i class="fa fa-check form-control-feedback" id="iconname"></i>
                    <input type="hidden" name="coid" value="<?php echo $c_d->co_id ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback has-success" id="glastnamep">
                    <label for="ilastnamep">Apellido Paterno *</label>
                    <input type="text" class="form-control" id="iNlastnamep" name="ilastnamep" placeholder="Ingresa el apellido paterno" readonly value="<?php echo $c_d->co_ap ?>">
                    <i class="fa fa-check form-control-feedback" id="iconlastnamep"></i>
                </div>

                <div class="form-group col-md-6 col-lg-4 has-feedback has-success" id="glastnamem">
                    <label class="control-label" for="ilastnamem">Apellido Materno *</label>
                    <input type="text" class="form-control" id="iNlastnamem" name="ilastnamem" placeholder="Ingresa el apellido materno" readonly value="<?php echo $c_d->co_am ?>">
                    <i class="fa fa-check form-control-feedback" id="iconlastnamem"></i>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback has-success" id="gemail">
                    <label class="control-label" for="iemail">Correo Electrónico *</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <input type="text" class="form-control" id="iNemail" name="iemail" placeholder="Ingresa e-mail de contacto" required value="<?php echo $c_d->co_email ?>">
                    </div>
                    <i class="fa fa-check form-control-feedback" id="iconemail"></i>
                </div>

                <?php $tmp = explode(')', $c_d->co_telefono) ?>
                <?php $code = str_replace('(', '', $tmp[0]) ?>
                <?php $phone = $tmp[1] ?>

                <div class="form-group col-md-2 col-lg-1 has-feedback has-success">
                    <label class="control-label" for="icod">País</label>
                    <select class="form-control" id="iNcod" name="icod">
                        <option value="+56"<?php if ($code == '+56'): ?> selected<?php endif ?>>Chile (56)</option>
                        <option value="+54"<?php if ($code == '+54'): ?> selected<?php endif ?>>Argentina (54)</option>
                    </select>
                </div>

                <div class="form-group col-md-4 col-lg-3 has-feedback has-success" id="gphone">
                    <label class="control-label" for="iphone">Teléfono *</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <input type="text" class="form-control inphone" id="iNphone" name="iphone" placeholder="Ingresa teléfono de contacto" required value="<?php echo $phone ?>">
                    </div>
                    <i class="fa fa-check form-control-feedback" id="iconphone"></i>
                    <p class="help-block">Teléfono de nueve dígitos (Ej. 912345678)</p>
                </div>
            </div>
        </div>

        <div class="box-footer text-right">
            <button type="button" class="btn btn-info" id="btn-people">Siguiente paso: Participantes <i class="fa fa-chevron-circle-right"></i></button>
        </div>
    </div>
</div>