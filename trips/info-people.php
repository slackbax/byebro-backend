<div id="peopleInfo">
    <?php $ppl = 1 ?>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-primary"><i class="fa fa-user"></i> Participantes del viaje</h3>
        </div>

        <div class="box-body">
            <p class="lead">Mensaje de introducción</p>

            <div id="divPart">
                <?php foreach ($c_p as $i => $p): ?>
                    <div id="part<?php echo $ppl ?>">
                        <?php if (!$p->par_encargado): ?>
                            <hr>
                            <div class="pull-right"><button type="button" class="btn btn-sm btn-danger del-part" id="del-part<?php echo $ppl ?>"><i class="fa fa-minus"></i> Eliminar</button></div>
                        <?php endif ?>

                        <?php if ($p->par_encargado): ?>
                            <div class="row">
                                <div class="form-group col-xs-4">
                                    <label class="label-checkbox">
                                        <input class="minimal incharge" type="checkbox" name="incharge" id="iNincharge" value="true" checked disabled>
                                        Encargado del Viaje
                                    </label>
                                </div>
                            </div>
                        <?php endif ?>

                        <div class="row">
                            <div class="form-group col-md-6 col-lg-3 has-feedback has-success" id="grutpart<?php echo $ppl ?>">
                                <label class="control-label" for="iNrutpart<?php echo $ppl ?>">RUT/DNI *</label>
                                <input type="text" class="form-control inrut" id="iNrutpart<?php echo $ppl ?>" name="irutpart[]" placeholder="Ingresa el documento de identificación del participante" required value="<?php echo $p->par_rut ?>">
                                <input type="hidden" name="iidpart[]" value="<?php echo $p->par_id ?>">
                                <i class="fa fa-check form-control-feedback" id="iconrutpart<?php echo $ppl ?>"></i>
                                <p class="help-block">Si es RUT, ingresa con formato completo (Ej. 12.345.678-9)</p>
                            </div>

                            <div class="form-group col-md-6 col-lg-3 has-feedback has-success" id="gedadpart<?php echo $ppl ?>">
                                <label class="control-label" for="iNedadpart<?php echo $ppl ?>">Edad *</label>
                                <input type="text" class="form-control inedad" id="iNedadpart<?php echo $ppl ?>" name="iedadpart[]" placeholder="Ingresa la edad del participante" required value="<?php echo $p->par_edad ?>">
                                <i class="fa fa-check form-control-feedback" id="iconedadpart<?php echo $ppl ?>"></i>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-lg-3 has-feedback has-success" id="gnamepart<?php echo $ppl ?>">
                                <label class="control-label" for="iNnamepart<?php echo $ppl ?>">Nombres *</label>
                                <input type="text" class="form-control inname" id="iNnamepart<?php echo $ppl ?>" name="inamepart[]" placeholder="Ingresa los nombres del participante" required value="<?php echo $p->par_nombres ?>">
                                <i class="fa fa-check form-control-feedback" id="iconnamepart<?php echo $ppl ?>"></i>
                            </div>

                            <div class="form-group col-md-6 col-lg-3 has-feedback has-success" id="glnppart<?php echo $ppl ?>">
                                <label for="iNlnppart<?php echo $ppl ?>">Apellido Paterno *</label>
                                <input type="text" class="form-control inap" id="iNlnppart<?php echo $ppl ?>" name="ilnppart[]" placeholder="Ingresa el apellido paterno" required value="<?php echo $p->par_ap ?>">
                                <i class="fa fa-check form-control-feedback" id="iconlnppart<?php echo $ppl ?>"></i>
                            </div>

                            <div class="form-group col-md-6 col-lg-3 has-feedback has-success" id="glnmpart<?php echo $ppl ?>">
                                <label class="control-label" for="iNlnmpart<?php echo $ppl ?>">Apellido Materno *</label>
                                <input type="text" class="form-control inam" id="iNlnmpart<?php echo $ppl ?>" name="ilnmpart[]" placeholder="Ingresa el apellido materno" required value="<?php echo $p->par_am ?>">
                                <i class="fa fa-check form-control-feedback" id="iconlnmpart<?php echo $ppl ?>"></i>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-lg-3 has-feedback has-success" id="gemailpart<?php echo $ppl ?>">
                                <label class="control-label" for="iNemailpart<?php echo $ppl ?>">Correo Electrónico *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control inemail" id="iNemailpart<?php echo $ppl ?>" name="iemailpart[]" placeholder="Ingresa e-mail de contacto" required value="<?php echo $p->par_email ?>">
                                </div>
                                <i class="fa fa-check form-control-feedback" id="iconemailpart<?php echo $ppl ?>"></i>
                            </div>

                            <?php $tmp = explode(')', $p->par_telefono) ?>
                            <?php $code = str_replace('(', '', $tmp[0]) ?>
                            <?php $phone = $tmp[1] ?>

                            <div class="form-group col-md-2 col-lg-1 has-success">
                                <label class="control-label" for="iNcodpart<?php echo $ppl ?>">País</label>
                                <select class="form-control" id="iNcodpart<?php echo $ppl ?>" name="icodpart[]">
                                    <option value="+56"<?php if ($code == '+56'): ?> selected<?php endif ?>>Chile (56)</option>
                                    <option value="+54"<?php if ($code == '+54'): ?> selected<?php endif ?>>Argentina (54)</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4 col-lg-3 has-feedback has-success" id="gphonepart<?php echo $ppl ?>">
                                <label class="control-label" for="iNphonepart<?php echo $ppl ?>">Teléfono *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control inphone" id="iNphonepart<?php echo $ppl ?>" name="iphonepart[]" placeholder="Ingresa teléfono de contacto" required value="<?php echo $phone ?>">
                                </div>
                                <i class="fa fa-check form-control-feedback" id="iconphonepart<?php echo $ppl ?>"></i>
                                <p class="help-block">Teléfono de nueve dígitos (Ej. 912345678)</p>
                            </div>
                        </div>
                    </div>
                <?php $ppl++ ?>
                <?php endforeach ?>
                <input type="hidden" id="iNnumpart" value="<?php echo $ppl-1 ?>">
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