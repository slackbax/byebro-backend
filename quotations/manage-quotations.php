<section class="content-header">
    <h1>Cotizaciones
        <small>Cotizaciones registradas</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active">Cotizaciones registradas</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Cotizaciones registradas</h3>
        </div>

        <div class="box-body table-responsive">
            <table id="tquotations" class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Salida</th>
                    <th>Llegada</th>
                    <th>Cotizante</th>
                    <th>Valor</th>
                    <th>Enlace</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </div>
</section>

<div class="modal fade" id="modal-details">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detalles de cotización</h4>
            </div>
            <div class="modal-body">
                <h4>Detalles del viaje</h4>
                <dl class="dl-horizontal">
                    <div class="row">
                        <div class="col-md-6">
                            <dt>Fecha de salida</dt>
                            <dd id="date-ini"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Fecha de llegada</dt>
                            <dd id="date-end"></dd>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <dt>Ciudad de origen</dt>
                            <dd id="city-o"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Ciudad de destino</dt>
                            <dd id="city-d"></dd>
                        </div>
                    </div>
                </dl>

                <h4>Cotizante</h4>
                <dl class="dl-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <dt>Nombre completo</dt>
                            <dd id="cot-data"></dd>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <dt>Correo electrónico</dt>
                            <dd id="cot-email"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Teléfono</dt>
                            <dd id="cot-phone"></dd>
                        </div>
                    </div>
                </dl>

                <h4>Participantes</h4>
                <dl class="dl-horizontal">
                    <div class="row">
                        <div class="col-md-12">
                            <dt>Nº de participantes</dt>
                            <dd id="num-part"></dd>
                        </div>
                    </div>
                </dl>

                <h4>Adicionales</h4>
                <div class="row" id="summary-extras">
                    <div class="col-md-12">
                        <table class="table table-striped" id="table-extras">
                            <thead>
                            <tr>
                                <th width="80%">Nombre</th>
                                <th class="text-center">Cantidad</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="summary-no-extras">
                    <dl class="dl-horizontal">
                        <dd><i>No hay adicionales agregados.</i></dd>
                    </dl>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="quotations/manage-quotations.js"></script>