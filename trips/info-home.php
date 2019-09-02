<div id="homesInfo">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-primary"><i class="fa fa-home"></i> Alojamiento</h3>
        </div>

        <div class="box-body">
            <p class="lead">Mensaje de introducción</p>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title">Alojamientos disponibles</h3>
        </div>

        <?php $hou = $ac->getByCity($c_d->cid_id) ?>
        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-6 col-lg-4 has-feedback" id="galoja">
                    <label class="control-label" for="iNaloja">Alojamiento *</label>
                    <select class="form-control" id="iNaloja" name="ialoja" required>
                        <option value="">Selecciona un alojamiento</option>
                        <?php foreach ($hou as $k => $h): ?>
                        <option value="<?php echo $h->alo_id ?>"><?php echo $h->alo_nombre ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>

        <div id="aloja-detail" style="display: none">
            <div class="box-header with-border">
                <h3 class="box-title">Detalles</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Descripción</label>
                        <p class="form-control-static" id="aloja-descrip"></p>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 col-lg-3">
                        <label class="control-label">Dirección</label>
                        <p class="form-control-static" id="aloja-address"></p>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 col-lg-3">
                        <label class="control-label">Número de habitaciones</label>
                        <p class="form-control-static" id="aloja-rooms"></p>
                    </div>

                    <div class="form-group col-md-4 col-lg-3">
                        <label class="control-label">Número de baños</label>
                        <p class="form-control-static" id="aloja-baths"></p>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 col-lg-3">
                        <label class="control-label">Imagen de referencia</label>
                        <p class="form-control-static" id="aloja-pic"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <div class="row">
                <div class="col-xs-6">
                    <button type="button" class="btn btn-default" id="btn-back-extras"><i class="fa fa-chevron-circle-left"></i> Volver al paso 3</button>
                </div>
                <div class="col-xs-6 text-right">
                    <button type="button" class="btn btn-info" id="btn-summary"><i class="fa fa-search"></i> Ver mi resumen</button>
                </div>
            </div>
        </div>
    </div>
</div>