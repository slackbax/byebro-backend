<div id="extrasInfo">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-primary"><i class="fa fa-plus"></i> Adicionales</h3>
        </div>

        <div class="box-body">
            <p class="lead">Mensaje de introducción</p>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title">Servicios individuales</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-md-4 col-lg-3 text-bold">Servicio</div>
                <div class="col-md-5 col-lg-6 text-bold">Descripción</div>
                <div class="form-group col-md-2 col-lg-2 text-bold">Cantidad de servicios</div>
            </div>
            <?php $ext = $ex->getByType(0) ?>
            <?php foreach ($ext as $k => $v): ?>
                <?php $inQuot = 0 ?>
                <?php foreach ($c_e as $i => $e): ?>
                    <?php if ($e->adi_id == $v->adi_id) $inQuot = $e->adi_cantidad ?>
                <?php endforeach ?>
                <div class="row">
                    <div class="col-xs-1 text-center">
                        <label class="label-checkbox">
                            <input class="minimal iextra" type="checkbox" name="iextra[]" id="iNextra<?php echo $v->adi_id ?>" value="<?php echo $v->adi_id ?>"<?php if ($inQuot > 0): ?> checked<?php endif ?>>
                            <input type="hidden" id="iNcantDB<?php echo $v->adi_id ?>" value="<?php echo $inQuot ?>">
                        </label>
                    </div>
                    <div class="col-md-4 col-lg-3" id="iNextraname<?php echo $v->adi_id ?>">
                        <?php echo $v->adi_nombre ?>
                    </div>
                    <div class="col-md-5 col-lg-6">
                        <i><?php echo $v->adi_descripcion ?></i>
                    </div>
                    <div class="form-group col-md-2 col-lg-2">
                        <select class="form-control icantExtras text-center" id="iNcant<?php echo $v->adi_id ?>" name="icant[]"<?php if ($inQuot == 0): ?> disabled<?php endif ?>>
                        </select>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title">Servicios grupales</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-xs-1"></div>
                <div class="col-md-4 col-lg-3 text-bold">Servicio</div>
                <div class="col-md-5 col-lg-6 text-bold">Descripción</div>
                <div class="form-group col-md-2 col-lg-2 text-bold">Cantidad de integrantes</div>
            </div>
            <?php $ex = $ex->getByType(1) ?>
            <?php foreach ($ex as $k => $v): ?>
                <?php $inQuot = 0 ?>
                <?php foreach ($c_e as $i => $e): ?>
                    <?php if ($e->adi_id == $v->adi_id) $inQuot = $e->adi_cantidad ?>
                <?php endforeach ?>
                <div class="row">
                    <div class="col-xs-1 text-center">
                        <label class="label-checkbox">
                            <input class="minimal iextra" type="checkbox" name="iextra[]" id="iNextra<?php echo $v->adi_id ?>" value="<?php echo $v->adi_id ?>"<?php if ($inQuot > 0): ?> checked<?php endif ?>>
                            <input type="hidden" id="iNcantDB<?php echo $v->adi_id ?>" value="<?php echo $inQuot ?>">
                        </label>
                    </div>
                    <div class="col-md-4 col-lg-3" id="iNextraname<?php echo $v->adi_id ?>">
                        <?php echo $v->adi_nombre ?>
                    </div>
                    <div class="col-md-5 col-lg-6">
                        <i><?php echo $v->adi_descripcion ?></i>
                    </div>
                    <div class="form-group col-md-2 col-lg-2">
                        <select class="form-control icantExtras text-center" id="iNcant<?php echo $v->adi_id ?>" name="icant[]"<?php if ($inQuot == 0): ?> disabled<?php endif ?>>
                        </select>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <!--
        <div class="box-header with-border">
            <h3 class="box-title">Comida</h3>
        </div>

        <div class="box-body">comida</div>
        -->

        <div class="box-footer">
            <div class="row">
                <div class="col-xs-6">
                    <button type="button" class="btn btn-default" id="btn-back-people"><i class="fa fa-chevron-circle-left"></i> Volver al paso 2</button>
                </div>
                <div class="col-xs-6 text-right">
                    <button type="button" class="btn btn-info" id="btn-summary"><i class="fa fa-search"></i> Ver mi resumen</button>
                </div>
            </div>
        </div>
    </div>
</div>
