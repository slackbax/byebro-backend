<div id="tripInfo">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-primary"><i class="fa fa-plane"></i> Detalles de viaje</h3>
        </div>

        <div class="box-body">
            <p class="lead">Mensaje de introducción</p>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback" id="gdates">
                    <label class="control-label" for="iNdates">Fechas de viaje *</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="iNdates" name="idates" required>
                        <i class="fa form-control-feedback" id="icondates"></i>
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
                <div class="form-group col-md-6 col-lg-4 has-feedback" id="gocity">
                    <label class="control-label" for="iNocity">Ciudad de origen *</label>
                    <select class="form-control" id="iNocity" name="iocity" required>
                        <option value="">Selecciona la ciudad de origen</option>
                        <?php $ct = $c->getAll('o') ?>
                        <?php foreach ($ct as $v): ?>
                            <option value="<?php echo $v->cio_id ?>"><?php echo $v->cio_nombre ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group col-md-6 col-lg-4 has-feedback" id="gdcity">
                    <label class="control-label" for="iNdcity">Ciudad de destino *</label>
                    <select class="form-control" id="iNdcity" name="idcity" required>
                        <option value="">Selecciona la ciudad de destino</option>
                        <?php $ct = $c->getAll('d') ?>
                        <?php foreach ($ct as $v): ?>
                            <option value="<?php echo $v->cid_id ?>"><?php echo $v->cid_nombre ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title">Datos personales del cotizante</h3>
        </div>

        <?php $c_u = $co->getByUser($_SESSION['bb_userid']) ?>

        <div class="box-body">
            <div class="row">
                <div class="form-group col-xs-4">
                    <label class="label-checkbox">
                        <input class="minimal" type="checkbox" name="isuser" id="iNisuser"<?php echo (empty($c_u->co_id)) ? ' disabled' : '' ?>>
                        Yo soy el cotizante
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-xs-6 col-md-3 col-lg-2">
                    <label class="label-checkbox">
                        <input type="radio" name="opDNI" id="opt_rut" class="opt-dni minimal" value="rut" checked>
                        RUT
                    </label>
                </div>

                <div class="form-group col-xs-6 col-md-9 col-lg-10">
                    <label class="label-checkbox">
                        <input type="radio" name="opDNI" id="opt_dni" class="opt-dni minimal" value="otro">
                        Otro (DNI, Pasaporte, etc.)
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-lg-3 has-feedback" id="grut">
                    <label class="control-label" for="irut">RUT *</label>
                    <input type="text" class="form-control" id="iNrut" name="irut" placeholder="Ingresa el RUT del cotizante" required>
                    <i class="fa form-control-feedback" id="iconrut"></i>
                </div>

                <div class="form-group col-md-6 col-lg-3 has-feedback" id="gdni" style="display: none">
                    <label class="control-label" for="idni">DNI *</label>
                    <input type="text" class="form-control" id="iNdni" name="idni" placeholder="Ingresa el documento de identificación del cotizante">
                    <i class="fa form-control-feedback" id="icondni"></i>
                </div>

                <input type="hidden" id="iNid" name="iid">
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback" id="gname">
                    <label class="control-label" for="iname">Nombres *</label>
                    <input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingresa los nombres del cotizante" required>
                    <i class="fa form-control-feedback" id="iconname"></i>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback" id="glastnamep">
                    <label for="ilastnamep">Apellido Paterno *</label>
                    <input type="text" class="form-control" id="iNlastnamep" name="ilastnamep" placeholder="Ingresa el apellido paterno" required>
                    <i class="fa form-control-feedback" id="iconlastnamep"></i>
                </div>

                <div class="form-group col-md-6 col-lg-4 has-feedback" id="glastnamem">
                    <label class="control-label" for="ilastnamem">Apellido Materno *</label>
                    <input type="text" class="form-control" id="iNlastnamem" name="ilastnamem" placeholder="Ingresa el apellido materno" required>
                    <i class="fa form-control-feedback" id="iconlastnamem"></i>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback" id="gemail">
                    <label class="control-label" for="iemail">Correo Electrónico *</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <input type="text" class="form-control" id="iNemail" name="iemail" placeholder="Ingresa e-mail de contacto" required>
                    </div>
                    <i class="fa form-control-feedback" id="iconemail"></i>
                </div>

                <div class="form-group col-md-2 col-lg-1">
                    <label class="control-label" for="icod">País</label>
                    <select class="form-control" id="iNcod" name="icod">
                        <option value="+56">Chile (56)</option>
                        <option value="+54">Argentina (54)</option>
                    </select>
                </div>

                <div class="form-group col-md-4 col-lg-3 has-feedback" id="gphone">
                    <label class="control-label" for="iphone">Teléfono *</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <input type="text" class="form-control inphone" id="iNphone" name="iphone" placeholder="Ingresa teléfono de contacto" required>
                    </div>
                    <i class="fa form-control-feedback" id="iconphone"></i>
                    <p class="help-block">Teléfono de nueve dígitos (Ej. 912345678)</p>
                </div>
            </div>
        </div>

        <div class="box-footer text-right">
            <button type="button" class="btn btn-info" id="btn-people">Siguiente paso: Participantes <i class="fa fa-chevron-circle-right"></i></button>
        </div>
    </div>
</div>