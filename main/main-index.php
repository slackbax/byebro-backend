<?php
$interval = 3;
$last_f = 5;
?>
<?php include 'class/classCotizacion.php' ?>
<?php include 'class/classViaje.php' ?>
<?php include 'class/classCotizante.php' ?>
<?php $c = new Cotizacion() ?>
<?php $v = new Viaje() ?>
<?php $co = new Cotizante() ?>
<?php $us_co = $co->getByUser($_SESSION['bb_userid']) ?>

<div class="row">
    <div class="col-md-12">
        <section class="content-header">
            <div class="callout">
                <h2><i class="fa fa-check"></i>
                    <small>Bienvenido(a),</small>
                    <?php echo $_SESSION['bb_userfname'] ?>
                </h2>
                <h4>a la plataforma de administración de <strong>ByeBro</strong>!</h4>
            </div>
        </section>

        <section class="content container-fluid">
            <div class="row">
                <?php //$cot = ($_admin) ? $c->getAll() : $c->getByCotizante($us_co->co_id) ?>
                <div class="col-sm-6 col-lg-4 col-lg-offset-2">
                    <a class="text-white" href="index.php?section=quotations&sbs=managequotations">
                        <div class="info-box bg-blue">

                            <span class="info-box-icon"><i class="fa fa-usd"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Cotizaciones</span>
                                <span class="info-box-number"><?php //echo count($cot) ?></span>
                                <span class="progress-description">Registrada<?php //if (count($cot) != 1) echo 's' ?></span>
                            </div>
                        </div>
                    </a>
                </div>

                <?php $cot = ($_admin) ? $c->getByState(1) : $c->getByStateCotizante(1, $us_co->co_id) ?>
                <div class="col-sm-6 col-lg-4">
                    <a class="text-white" href="">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-usd"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Cotizaciones</span>
                                <span class="info-box-number"><?php echo count($cot) ?></span>
                                <span class="progress-description">Ingresada<?php if (count($cot) != 1) echo 's' ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <?php $via = ($_admin) ? $v->getByState(1) : $v->getByStateCotizante(1, $us_co->co_id) ?>
                <div class="col-sm-6 col-lg-4">
                    <a class="text-white" href="">
                        <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-plane"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Viajes</span>
                                <span class="info-box-number"><?php echo count($via) ?></span>
                                <span class="progress-description">Registrado<?php if (count($via) != 1) echo 's' ?></span>

                            </div>
                        </div>
                    </a>
                </div>

                <?php $via = ($_admin) ? $v->getByState(2) : $v->getByStateCotizante(2, $us_co->co_id) ?>
                <div class="col-sm-6 col-lg-4">
                    <a class="text-white" href="">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-plane"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Viajes</span>
                                <span class="info-box-number"><?php echo count($via) ?></span>
                                <span class="progress-description">Iniciado<?php if (count($via) != 1) echo 's' ?></span>
                            </div>
                        </div>
                    </a>
                </div>

                <?php $via = ($_admin) ? $v->getByState(3) : $v->getByStateCotizante(3, $us_co->co_id) ?>
                <div class="col-sm-6 col-lg-4">
                    <a class="text-white" href="">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-plane"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Viajes</span>
                                <span class="info-box-number"><?php echo count($via) ?></span>
                                <span class="progress-description">Terminado<?php if (count($via) != 1) echo 's' ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="main/main-index.js"></script>