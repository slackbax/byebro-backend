<?php include 'class/classCity.php' ?>
<?php $c = new City() ?>

<section class="content-header">
    <h1>Administración
        <small>Creación de personal</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Creación de personal</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formNewPersonal">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Información del personal</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gname">
                        <label class="control-label" for="iname">Nombres *</label>
                        <input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingresa nombres del empleado" required>
                        <i class="fa form-control-feedback" id="iconname"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="glastnamep">
                        <label for="ilastnamep">Apellido Paterno *</label>
                        <input type="text" class="form-control" id="iNlastnamep" name="ilastnamep" placeholder="Ingresa apellido paterno" required>
                        <i class="fa form-control-feedback" id="iconlastnamep"></i>
                    </div>

                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="glastnamem">
                        <label class="control-label" for="ilastnamem">Apellido Materno *</label>
                        <input type="text" class="form-control" id="iNlastnamem" name="ilastnamem" placeholder="Ingresa apellido materno" required>
                        <i class="fa form-control-feedback" id="iconlastnamem"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gemail">
                        <label class="control-label" for="iemail">Correo Electrónico *</label>
                        <input type="text" class="form-control" id="iNemail" name="iemail" placeholder="Ingresa e-mail del empleado" required>
                        <i class="fa form-control-feedback" id="iconEmail"></i>
                    </div>

                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gphone">
                        <label class="control-label" for="iphone">Teléfono *</label>
                        <input type="text" class="form-control" id="iNphone" name="iphone" placeholder="Ingresa teléfono de contacto del empleado" required>
                        <i class="fa form-control-feedback" id="iconphone"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gusername">
                        <label class="control-label" for="iusername">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="iNusername" name="iusername" placeholder="Ingresa nombre de usuario del empleado">
                        <input type="hidden" id="iNusernameid" name="iusernameid">
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

                <div id="divDestiny" style="display: none; padding: 2px 10px; background-color: #f2f2f2; border: 2px solid #f2f2f2">
                    <h4>Ciudades agregadas</h4>
                    <div class="row">
                        <div class="form-group col-xs-11">
                            <p class="form-control-static"><strong>Nombre</strong></p>
                        </div>
                        <div class="form-group col-xs-1 text-center">
                            <p class="form-control-static"></p>
                        </div>
                    </div>

                    <div id="divDestiny-inner"></div>
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

<script src="admin/staff/create-personal.js"></script>