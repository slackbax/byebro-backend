<?php include 'class/classCity.php' ?>
<?php include 'class/classCotizante.php' ?>
<?php include 'class/classCotizacion.php' ?>
<?php include 'class/classParticipante.php' ?>
<?php include 'class/classAccomodation.php' ?>
<?php include 'class/classExtra.php' ?>
<?php $c = new City() ?>
<?php $co = new Cotizante() ?>
<?php $cot = new Cotizacion() ?>
<?php $par = new Participante() ?>
<?php $ex = new Extra() ?>
<?php $ac = new Accomodation() ?>
<?php if (isset($cotid)): ?>
    <?php $c_d = $cot->get($cotid) ?>
    <?php $c_p = $par->getByCotizacion($cotid) ?>
    <?php $c_e = $ex->getByCotizacion($cotid) ?>
<?php endif ?>

<section class="content-header">
    <h1>Viajes
        <small>Creación de viaje</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Creación de viaje</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formNewTrip">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <ul class="nav nav-tabs nav-tabs-bb" id="sectionTabs" role="tablist">
            <li role="presentation" class="active"><a href="#trip" aria-controls="trip" role="tab">Paso 1: Detalles de viaje</a></li>
            <li role="presentation"><a href="#people" aria-controls="people" role="tab">Paso 2: Participantes</a></li>
            <li role="presentation"><a href="#extras" aria-controls="extras" role="tab">Paso 3: Adicionales</a></li>
            <li role="presentation"><a href="#homes" aria-controls="homes" role="tab">Paso 4: Alojamiento</a></li>
            <li role="presentation"><a href="#summary" aria-controls="summary" role="tab">Resumen</a></li>
        </ul>

        <input type="hidden" id="usid" value="<?php echo $_SESSION['bb_userid'] ?>">
        <input type="hidden" name="iid" value="<?php echo $cotid ?>">

        <!-- Tab panes -->
        <div class="tab-content tab-content-bb">
            <div role="tabpanel" class="tab-pane fade in active" id="trip">
                <?php include 'info-trip.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="people">
                <?php include 'info-people.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="extras">
                <?php include 'info-extras.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="homes">
                <?php include 'info-home.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="summary">
                <?php include 'info-summary.php' ?>
            </div>
        </div>
    </form>
</section>

<script src="trips/create-trip.js"></script>