<?php include 'class/classCity.php' ?>
<?php include 'class/classCotizante.php' ?>
<?php $c = new City() ?>
<?php $co = new Cotizante() ?>

<section class="content-header">
    <h1>Cotizaciones
        <small>Creación de cotización</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Creación de cotización</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formNewQuota">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <ul class="nav nav-tabs nav-tabs-bb" id="sectionTabs" role="tablist">
            <li role="presentation" class="active"><a href="#trip" aria-controls="trip" role="tab">Paso 1: Detalles de viaje</a></li>
            <li role="presentation"><a href="#people" aria-controls="people" role="tab">Paso 2: Participantes</a></li>
            <li role="presentation"><a href="#summary" aria-controls="summary" role="tab">Resumen</a></li>
        </ul>

        <input type="hidden" id="usid" value="<?php echo $_SESSION['bb_userid'] ?>">

        <!-- Tab panes -->
        <div class="tab-content tab-content-bb">
            <div role="tabpanel" class="tab-pane fade in active" id="trip">
                <?php include 'info-trip.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="people">
                <?php include 'info-people.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="summary">
                <?php include 'info-summary.php' ?>
            </div>
        </div>
    </form>
</section>

<script src="quotations/create-quotation.js"></script>