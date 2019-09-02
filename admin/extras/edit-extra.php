<?php include 'class/classExtra.php' ?>
<?php $extra = new Extra() ?>
<?php $e = $extra->get($id) ?>

<section class="content-header">
    <h1>Administración
        <small><i class="fa fa-angle-right"></i> Edición de adicional</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="index.php?section=tripdetails&sbs=manageextras">Adicionales creados</a></li>
        <li class="active">Edición de adicional</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formNewExtra">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Información del adicional</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gname">
                        <label class="control-label" for="iname">Nombre del adicional *</label>
                        <input type="hidden" name="id" id="iNid" value="<?php echo $id ?>">
                        <input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingresa nombre del adicional" value="<?php echo $e->adi_nombre ?>" required>
                        <i class="fa form-control-feedback" id="iconname"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-4">
                        <label class="label-checkbox">
                            <input class="minimal" type="checkbox" name="igroup" id="iNgroup" value="S"<?php if ($e->adi_grupal == true): ?> checked<?php endif ?>>
                            Adicional grupal
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-12 has-feedback">
                        <label class="control-label" for="idetalle">Descripción del adicional *</label>
                        <textarea id="iNdetalle" name="idetalle" required><?php echo $e->adi_descripcion ?></textarea>
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

<script src="admin/extras/edit-extra.js"></script>