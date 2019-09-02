<div id="summaryInfo">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title text-primary"><i class="fa fa-search"></i> Tu resumen de cotización</h3>
        </div>

        <div class="box-body">
            <p class="lead">Mensaje de introducción</p>
        </div>

        <div class="box-body">
            <h4>Detalles del viaje</h4>
            <dl class="dl-horizontal">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <dt>Fecha de salida</dt>
                        <dd id="date-ini"></dd>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <dt>Fecha de llegada</dt>
                        <dd id="date-end"></dd>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <dt>Ciudad de origen</dt>
                        <dd id="city-o"></dd>
                    </div>
                    <div class="col-md-6 col-lg-4">
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
                    <div class="col-md-6 col-lg-4">
                        <dt>Correo electrónico</dt>
                        <dd id="cot-email"></dd>
                    </div>
                    <div class="col-md-6 col-lg-4">
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
                        <dd>
                            <span id="num-part"></span>
                            <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal-details"><i class="fa fa-search"></i> Ver detalles</button>
                        </dd>
                    </div>
                </div>
            </dl>

            <h4>Adicionales</h4>
            <div class="table-responsive col-md-10 col-lg-8" id="summary-extras" style="display: none">
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
            <div id="summary-no-extras" style="display: none">
                <dl class="dl-horizontal">
                    <dd><i>No hay adicionales agregados.</i></dd>
                </dl>
            </div>

            <div class="clearfix"></div>

            <h4>Alojamiento</h4>
            <dl class="dl-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <dt>Nombre</dt>
                        <dd id="alo-nombre"></dd>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <dt>Dirección</dt>
                        <dd id="alo-direccion"></dd>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <dt>Descripción</dt>
                        <dd id="alo-descripcion"></dd>
                    </div>
                </div>
            </dl>
        </div>

        <div class="box-footer">
            <div class="row">
                <div class="col-xs-6">
                    <button type="button" class="btn btn-default" id="btn-back-homes"><i class="fa fa-chevron-circle-left"></i> Volver al paso 4</button>
                </div>
                <div class="col-xs-6 text-right">
                    <button type="submit" class="btn btn-lg btn-primary" id="btn-submit"><i class="fa fa-check"></i> Guardar viaje</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-details">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Participantes</h4>
            </div>
            <div class="modal-body">
                <h5>Encargado de grupo</h5>
                <dl class="dl-horizontal">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <dt>Nombre completo</dt>
                            <dd id="par1-data"></dd>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <dt>RUT/DNI</dt>
                            <dd id="par1-rut"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Edad</dt>
                            <dd id="par1-edad"></dd>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <dt>Correo electrónico</dt>
                            <dd id="par1-email"></dd>
                        </div>
                        <div class="col-md-6">
                            <dt>Teléfono</dt>
                            <dd id="par1-phone"></dd>
                        </div>
                    </div>
                </dl>
                <div id="participantes-data"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>