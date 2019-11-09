<section class="content-header">
    <h1>Participantes
        <small>Creación de participante</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active">Creación de participante</li>
    </ol>
</section>

<section class="content container-fluid">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Filtro de búsqueda</h3>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label>
                        <input type="radio" name="ioption" id="iNcot" class="minimal" checked> Cotizaciones
                    </label>
                </div>

                <div class="form-group col-md-4">
                    <label>
                        <input type="radio" name="ioption" id="iNvia" class="minimal"> Viajes
                    </label>
                </div>
            </div>
        </div>

        <div class="box-header with-border">
            <h3 class="box-title" id="filter-title">Cotizaciones registradas</h3>
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
                    <th>Nº Participantes</th>
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
                <h4 class="modal-title" id="modal-title"></h4>
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

<div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" id="formNewPartOnQuot">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detalles de participante</h4>
                </div>
                <div class="modal-body">
                    <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6 has-feedback" id="grutpart">
                            <label class="control-label" for="iNrutpart">RUT/DNI *</label>
                            <input type="text" class="form-control inrut" id="iNrutpart" name="irutpart" placeholder="Ingresa el documento de identificación del participante" required>
                            <input type="hidden" id="iNqid" name="iidq">
                            <input type="hidden" id="iNtid" name="iidt">
                            <i class="fa form-control-feedback" id="iconrutpart"></i>
                            <p class="help-block">Si es RUT, ingresa con formato completo (Ej. 12.345.678-9)</p>
                        </div>

                        <div class="form-group col-md-6 col-lg-6 has-feedback" id="gedadpart">
                            <label class="control-label" for="iNedadpart">Edad *</label>
                            <input type="text" class="form-control inedad" id="iNedadpart" name="iedadpart" placeholder="Ingresa la edad del participante" required>
                            <i class="fa form-control-feedback" id="iconedadpart"></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6 has-feedback" id="gnamepart">
                            <label class="control-label" for="iNnamepart">Nombres *</label>
                            <input type="text" class="form-control inname" id="iNnamepart" name="inamepart" placeholder="Ingresa los nombres del participante" required>
                            <i class="fa form-control-feedback" id="iconnamepart"></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6 has-feedback" id="glnppart">
                            <label for="iNlnppart">Apellido Paterno *</label>
                            <input type="text" class="form-control inap" id="iNlnppart" name="ilnppart" placeholder="Ingresa el apellido paterno" required>
                            <i class="fa form-control-feedback" id="iconlnppart"></i>
                        </div>

                        <div class="form-group col-md-6 col-lg-6 has-feedback" id="glnmpart">
                            <label class="control-label" for="iNlnmpart">Apellido Materno *</label>
                            <input type="text" class="form-control inam" id="iNlnmpart" name="ilnmpart" placeholder="Ingresa el apellido materno" required>
                            <i class="fa form-control-feedback" id="iconlnmpart"></i>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6 has-feedback" id="gemailpart">
                            <label class="control-label" for="iNemailpart">Correo Electrónico *</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <input type="text" class="form-control inemail" id="iNemailpart" name="iemailpart" placeholder="Ingresa e-mail de contacto" required>
                            </div>
                            <i class="fa form-control-feedback" id="iconemailpart"></i>
                        </div>

                        <div class="form-group col-md-2 col-lg-2">
                            <label class="control-label" for="iNcodpart">País</label>
                            <select class="form-control" id="iNcodpart" name="icodpart">
                                <option value="+56">Chile (56)</option>
                                <option value="+54">Argentina (54)</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 col-lg-4 has-feedback" id="gphonepart">
                            <label class="control-label" for="iNphonepart">Teléfono *</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <input type="text" class="form-control inphone" id="iNphonepart" name="iphonepart" placeholder="Ingresa teléfono de contacto" required>
                            </div>
                            <i class="fa form-control-feedback" id="iconphonepart"></i>
                            <p class="help-block">Teléfono de nueve dígitos (Ej. 912345678)</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="ajaxLoader" id="submitLoader"></span>
                    <button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="participants/create-participant.js"></script>