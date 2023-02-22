<?php include 'class/classAccomodation.php' ?>
<?php include 'class/classCity.php' ?>
<?php $a = new Accomodation() ?>
<?php $c = new City() ?>
<?php $ac = $a->get($id) ?>

<section class="content-header">
    <h1>Administración
        <small>Edición de alojamientos</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="index.php?section=tripdetails&sbs=manageaccomodations">Alojamientos creados</a></li>
        <li class="active">Edición de alojamiento</li>
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
                        <input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingresa un nombre para identificar al alojamiento" maxlength="64" required value="<?php echo $ac->alo_nombre ?>">
                        <input type="hidden" name="iid" value="<?php echo $id ?>">
                        <i class="fa form-control-feedback" id="iconname"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-12 has-feedback">
                        <label class="control-label" for="idetalle">Descripción *</label>
                        <textarea id="iNdetalle" name="idetalle" required><?php echo $ac->alo_descripcion ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12 col-lg-6 has-feedback" id="gaddr">
                        <label class="control-label" for="iaddr">Dirección *</label>
                        <input type="text" class="form-control" id="iNaddr" name="iaddr" placeholder="Ingresa la dirección en que se encuentra el alojamiento" maxlength="1024" required value="<?php echo $ac->alo_direccion ?>">
                        <i class="fa form-control-feedback" id="iconaddr"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="grooms">
                        <label class="control-label" for="irooms">Número de habitaciones *</label>
                        <input type="text" class="form-control" id="iNrooms" name="irooms" placeholder="Ingresa número de habitaciones del alojamiento" required value="<?php echo $ac->alo_rooms ?>">
                        <i class="fa form-control-feedback" id="iconrooms"></i>
                    </div>

                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="gbaths">
                        <label class="control-label" for="ibaths">Número de baños *</label>
                        <input type="text" class="form-control" id="iNbaths" name="ibaths" placeholder="Ingresa número de habitaciones del alojamiento" required value="<?php echo $ac->alo_baths ?>">
                        <i class="fa form-control-feedback" id="iconbaths"></i>
                    </div>

                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="gbeds1p">
                        <label class="control-label" for="ibeds1p">Número de camas 1P *</label>
                        <input type="text" class="form-control" id="iNbeds1p" name="ibeds1p" placeholder="Ingresa número de camas de una plaza del alojamiento" required value="<?php echo $ac->alo_beds1p ?>">
                        <i class="fa form-control-feedback" id="iconbeds1p"></i>
                    </div>

                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="gbeds2p">
                        <label class="control-label" for="ibeds2p">Número de camas 2P *</label>
                        <input type="text" class="form-control" id="iNbeds2p" name="ibeds2p" placeholder="Ingresa número de camas de dos plazas del alojamiento" required value="<?php echo $ac->alo_beds2p ?>">
                        <i class="fa form-control-feedback" id="iconbeds2p"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="gpool">
                        <label class="control-label" for="ipool">Número de piscinas *</label>
                        <input type="text" class="form-control" id="iNpool" name="ipool" placeholder="Ingresa número de piscinas del alojamiento" required value="<?php echo $ac->alo_pool ?>">
                        <i class="fa form-control-feedback" id="iconpool"></i>
                    </div>

                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="gbarbs">
                        <label class="control-label" for="ibarbs">Número de quinchos *</label>
                        <input type="text" class="form-control" id="iNbarbs" name="ibarbs" placeholder="Ingresa número de quinchos del alojamiento" required value="<?php echo $ac->alo_barbecue ?>">
                        <i class="fa form-control-feedback" id="iconbarbs"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gcity">
                        <label class="control-label" for="icity">Ciudad *</label>
                        <select class="form-control" id="iNcity" name="icity" required>
                            <option>Selecciona la ciudad en que se encuentra el alojamiento</option>
                            <?php $ct = $c->getAll('d') ?>
                            <?php foreach ($ct as $v): ?>
                                <option value="<?php echo $v->cid_id ?>"<?php if ($v->cid_id == $ac->cid_id): ?> selected<?php endif ?>><?php echo $v->cid_nombre ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12 col-lg-6 has-feedback" id="gurl">
                        <label class="control-label" for="iurl">URL Google maps *</label>
                        <input type="text" class="form-control" id="iNurl" name="iurl" placeholder="Ingresa ubicación Google Maps del alojamiento" required value="<?php echo $ac->alo_url ?>">
                        <i class="fa form-control-feedback" id="iconurl"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="iimage">Imagen de Referencia</label>
                        <div class="controls">
                            <input name="iimage[]" class="multi" id="iimage" type="file" size="16" accept="gif|jpg|png|jpeg" maxlength="10">
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

<script src="admin/accomodations/edit-accomodation.js"></script>