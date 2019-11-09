<?php include 'class/classCity.php' ?>
<?php $c = new City() ?>

<section class="content-header">
    <h1>Administración
        <small>Creación de alojamientos</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Creación de alojamiento</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formNewAccomodation">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Información del alojamiento</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gname">
                        <label class="control-label" for="iname">Nombre *</label>
                        <input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingresa un nombre para identificar al alojamiento" maxlength="64" required>
                        <i class="fa form-control-feedback" id="iconname"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-12 has-feedback">
                        <label class="control-label" for="idetalle">Descripción *</label>
                        <textarea id="iNdetalle" name="idetalle" required></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12 col-lg-6 has-feedback" id="gaddr">
                        <label class="control-label" for="iaddr">Dirección *</label>
                        <input type="text" class="form-control" id="iNaddr" name="iaddr" placeholder="Ingresa la dirección en que se encuentra el alojamiento" maxlength="1024" required>
                        <i class="fa form-control-feedback" id="iconaddr"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="grooms">
                        <label class="control-label" for="irooms">Número de habitaciones *</label>
                        <input type="text" class="form-control" id="iNrooms" name="irooms" placeholder="Ingresa número de habitaciones del alojamiento" required>
                        <i class="fa form-control-feedback" id="iconrooms"></i>
                    </div>

                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="gbaths">
                        <label class="control-label" for="ibaths">Número de baños *</label>
                        <input type="text" class="form-control" id="iNbaths" name="ibaths" placeholder="Ingresa número de habitaciones del alojamiento" required>
                        <i class="fa form-control-feedback" id="iconbaths"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gcity">
                        <label class="control-label" for="icity">Ciudad *</label>
                        <select class="form-control" id="iNcity" name="icity" required>
                            <option>Selecciona la ciudad en que se encuentra el alojamiento</option>
                            <?php $ct = $c->getAll('d') ?>
                            <?php foreach ($ct as $v): ?>
                                <option value="<?php echo $v->cid_id ?>"><?php echo $v->cid_nombre ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="iimage">Imagen de Referencia</label>
                        <div class="controls">
                            <input name="iimage[]" class="multi" id="iimage" type="file" size="16" accept="gif|jpg|png|jpeg" maxlength="1">
                            <p class="help-block">Formatos admitidos: GIF, JPG, JPEG, PNG</p>
                        </div>
                    </div>
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

<script src="admin/accomodations/create-accomodation.js"></script>