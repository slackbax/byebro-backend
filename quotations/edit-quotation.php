<?php include 'class/classCity.php' ?>
<?php include 'class/classCotizante.php' ?>
<?php include 'class/classCotizacion.php' ?>
<?php include 'class/classParticipante.php' ?>
<?php include 'class/classExtra.php' ?>
<?php $c = new City() ?>
<?php $co = new Cotizante() ?>
<?php $cot = new Cotizacion() ?>
<?php $par = new Participante() ?>
<?php $ex = new Extra() ?>
<?php if (isset($id)): ?>
    <?php $c_d = $cot->get($id) ?>
    <?php $c_p = $par->getByCotizacion($id) ?>
    <?php $c_e = $ex->getByCotizacion($id) ?>
<?php endif ?>

<section class="content-header">
    <h1>Cotizaciones
        <small>Edición de cotización</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="index.php?section=quotations&sbs=managequotations">Cotizaciones registradas</a></li>
        <li class="active">Edición de cotización</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formEditQuota">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <ul class="nav nav-tabs nav-tabs-bb" id="sectionTabs" role="tablist">
            <li role="presentation" class="active"><a href="#trip" aria-controls="trip" role="tab">Paso 1: Detalles de viaje</a></li>
            <li role="presentation"><a href="#people" aria-controls="people" role="tab">Paso 2: Participantes</a></li>
            <li role="presentation"><a href="#extras" aria-controls="extras" role="tab">Paso 3: Adicionales</a></li>
            <li role="presentation"><a href="#summary" aria-controls="summary" role="tab">Resumen</a></li>
        </ul>

        <input type="hidden" id="usid" value="<?php echo $_SESSION['bb_userid'] ?>">
        <input type="hidden" name="iid" value="<?php echo $id ?>">

        <!-- Tab panes -->
        <div class="tab-content tab-content-bb">
            <div role="tabpanel" class="tab-pane fade in active" id="trip">
                <?php include 'edit-trip.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="people">
                <?php include 'edit-people.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="extras">
                <?php include 'edit-extras.php' ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="summary">
                <?php include 'edit-summary.php' ?>
            </div>
        </div>
    </form>
</section>

<script src="quotations/edit-quotation.js"></script>
