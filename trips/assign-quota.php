<?php include 'class/classViaje.php' ?>
<?php include 'class/classParticipante.php' ?>
<?php $v = new Viaje() ?>
<?php $p = new Participante() ?>
<?php $vi = $v->get($id) ?>
<?php $par = $p->getByViaje($id) ?>
<?php $n_par = count($par) ?>
<?php $quota = $vi->vi_valor / $n_par ?>

<section class="content-header">
    <h1>Viajes
        <small>Asignación de cuotas</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="index.php?section=trips&sbs=managequotas">Viajes registrados</a></li>
        <li class="active">Asignación de cuotas</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formNewQuota">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Participantes</h3>
            </div>

            <div class="box-body">
                <input type="hidden" name="iid" value="<?php echo $id ?>">
                <input type="hidden" id="iNnpart" value="<?php echo $n_par ?>">

                <?php foreach ($par as $k => $v): ?>
                <div class="row">
                    <div class="form-group col-md-6 col-lg-4">
                        <p class="form-control-static"><?php echo $v->par_nombres . ' ' . $v->par_ap . ' ' . $v->par_am ?></p>
                        <input type="hidden" class="form-control" name="ipart[]" value="<?php echo $v->par_id ?>">
                    </div>

                    <div class="form-group col-md-2 col-lg-1">
                        <label class="label-checkbox">
                            <input type="checkbox" id="opt_<?php echo $v->par_id ?>" class="minimal opt-quota">
                        </label>
                    </div>

                    <div class="form-group col-md-4 col-lg-3" id="gamount">
                        <input type="text" class="form-control input-number quota" id="iNamount_<?php echo $v->par_id ?>" name="iamount[]" placeholder="Ingresa monto de la cuota especial" value="<?php echo number_format($quota, 0, ',', '.') ?>" readonly>
                    </div>
                </div>
                <?php endforeach ?>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4">
                        <p class="form-control-static text-bold">Total</p>
                    </div>

                    <div class="form-group col-md-4 col-md-offset-2 col-lg-3 col-lg-offset-1 has-success" id="gsum">
                        <input type="text" class="form-control input-lg input-number" id="iNsum" value="<?php echo number_format($vi->vi_valor, 0, ',', '.') ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4">
                        <p class="form-control-static text-bold">Valor referencia</p>
                    </div>

                    <div class="form-group col-md-4 col-md-offset-2 col-lg-3 col-lg-offset-1 has-success" id="gtotal">
                        <input type="text" class="form-control input-lg input-number" id="iNtotal" value="<?php echo number_format($vi->vi_valor, 0, ',', '.') ?>" readonly>
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

<script src="trips/assign-quota.js"></script>
