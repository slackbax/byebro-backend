<?php include 'class/classPersonal.php' ?>
<?php include 'class/classCity.php' ?>
<?php $p = new Personal() ?>
<?php $per = $p->get($id) ?>
<?php $c = new City() ?>

<section class="content-header">
    <h1>Administración
        <small>Edición de personal</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="index.php?section=personal&sbs=managepersonal">Personal creado</a></li>
        <li class="active">Edición de personal</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formEditPersonal">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Información del personal</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gname">
                        <label class="control-label" for="iname">Nombres *</label>
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <input type="text" class="form-control" id="iNname" name="iname" value="<?php echo $per->per_nombres ?>" placeholder="Ingresa nombres del empleado" required>
                        <i class="fa form-control-feedback" id="iconname"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="glastnamep">
                        <label for="ilastnamep">Apellido Paterno *</label>
                        <input type="text" class="form-control" id="iNlastnamep" name="ilastnamep" value="<?php echo $per->per_ap ?>" placeholder="Ingresa apellido paterno" required>
                        <i class="fa form-control-feedback" id="iconlastnamep"></i>
                    </div>

                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="glastnamem">
                        <label class="control-label" for="ilastnamem">Apellido Materno *</label>
                        <input type="text" class="form-control" id="iNlastnamem" name="ilastnamem" value="<?php echo $per->per_am ?>" placeholder="Ingresa apellido materno" required>
                        <i class="fa form-control-feedback" id="iconlastnamem"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gemail">
                        <label class="control-label" for="iemail">Correo Electrónico *</label>
                        <input type="text" class="form-control" id="iNemail" name="iemail" value="<?php echo $per->per_email ?>" placeholder="Ingresa e-mail del empleado" required>
                        <i class="fa form-control-feedback" id="iconEmail"></i>
                    </div>

                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gphone">
                        <label class="control-label" for="iphone">Teléfono *</label>
                        <input type="text" class="form-control" id="iNphone" name="iphone" value="<?php echo $per->per_telefono ?>" placeholder="Ingresa teléfono de contacto del empleado" required>
                        <i class="fa form-control-feedback" id="iconphone"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gusername">
                        <label class="control-label" for="iusername">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="iNusername" name="iusername" value="<?php echo $per->us_username ?>" placeholder="Ingresa nombre de usuario del empleado">
                        <input type="hidden" id="iNusernameid" name="iusernameid" value="<?php echo $per->us_id ?>">
                        <i class="fa form-control-feedback" id="iconusername"></i>
                    </div>
                </div>
            </div>

            <div class="box-header with-border">
                <h3 class="box-title">Ciudades asociadas</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gdcity">
                        <label class="control-label" for="iNdcity">Ciudad de destino *</label>
                        <select class="form-control" id="iNdcity" name="idcity">
                            <option value="">Selecciona la ciudad de destino</option>
                            <?php $ct = $c->getAll('d') ?>
                            <?php foreach ($ct as $v): ?>
                                <option value="<?php echo $v->cid_id ?>"><?php echo $v->cid_nombre ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-8">
                        <button type="button" class="btn btn-info btn-sm" id="btnAddCity">
                            <i class="fa fa-plus"></i> Agregar ciudad
                        </button>
                    </div>
                </div>

                <?php $cit = $p->getCities($id) ?>
                <div id="divDestiny" style="<?php if (count($cit) == 0): ?>display: none; <?php endif ?>padding: 2px 10px; background-color: #f2f2f2; border: 2px solid #f2f2f2">
                    <h4>Ciudades agregadas</h4>
                    <div class="row">
                        <div class="form-group col-xs-11">
                            <p class="form-control-static"><strong>Nombre</strong></p>
                        </div>
                        <div class="form-group col-xs-1 text-center">
                            <p class="form-control-static"></p>
                        </div>
                    </div>

                    <div id="divDestiny-inner">
                        <?php $id_row = 0 ?>
                        <?php foreach ($cit as $i => $v): ?>
                            <div id="row<?php echo $id_row ?>" class="row">
                                <input class="idestino" name="iid[]" id="iNiidest<?php echo $id_row ?>" value="<?php echo $v->cid_id ?>" type="hidden">

                                <div class="form-group col-xs-11">
                                    <p class="form-control-static"><?php echo $v->cid_nombre ?></p>
                                </div>
                                <div class="form-group col-xs-1 text-center">
                                    <p class="form-control-static">
                                        <button type="button" class="btn btn-xs btn-danger btnDel" name="btn_<?php echo $id_row ?>" id="btndel_<?php echo $id_row ?>"><i class="fa fa-remove"></i></button>
                                    </p>
                                </div>
                            </div>
                            <?php $id_row++ ?>
                        <?php endforeach ?>
                    </div>
                    <input id="iNndest" value="<?php echo $id_row ?>" type="hidden">
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar</button>
                <button type="reset" class="btn btn-default" id="btnClear">Limpiar</button>
                <span class="ajaxLoader" id="submitLoader"></span>
            </div>
        </div>
    </form>
</section>

<script src="admin/staff/edit-personal.js"></script>