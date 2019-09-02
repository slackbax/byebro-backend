<section class="content-header">
    <h1>Participantes
        <small><i class="fa fa-angle-right"></i> Participantes registrados</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i>Inicio</a></li>
        <li class="active">Participantes registrados</li>
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
            <h3 class="box-title" id="filter-title">Participantes registrados en cotizaciones</h3>
        </div>

        <div class="box-body table-responsive">
            <table id="tquotations" class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Cotización</th>
                    <th>RUT/DNI</th>
                    <th>Nombres</th>
                    <th>Apellido paterno</th>
                    <th>Apellido materno</th>
                    <th>Edad</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </div>
</section>

<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form role="form" id="formEditPart">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Detalles de participante</h4>
                </div>
                <div class="modal-body">
                    <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6" id="grutpart">
                            <label class="control-label" for="iNrutpart">RUT/DNI</label>
                            <input type="text" class="form-control inrut" id="iNrutpart" name="irutpart" disabled>
                            <input type="hidden" id="iNid" name="iid">
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
                    <button type="submit" class="btn btn-primary" id="btnsubmit"><i class="fa fa-check"></i> Editar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="participants/manage-participant.js"></script>