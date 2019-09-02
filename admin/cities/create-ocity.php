<section class="content-header">
    <h1>Administración
        <small><i class="fa fa-angle-right"></i> Creación de ciudades de origen</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="index.php?section=home"><i class="fa fa-home"></i> Inicio</a></li>
        <li class="active">Creación de ciudades de origen</li>
    </ol>
</section>

<section class="content container-fluid">
    <form role="form" id="formNewOCity">
        <p class="bg-class bg-danger">Los campos marcados con (*) son obligatorios</p>

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Información de la ciudad de origen</h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gname">
                        <label class="control-label" for="iname">Nombre *</label>
                        <input type="text" class="form-control" id="iNname" name="iname" placeholder="Ingresa nombre de la ciudad" required>
                        <input type="hidden" name="itype" value="o">
                        <i class="fa form-control-feedback" id="iconname"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 col-lg-3 has-feedback" id="gcode">
                        <label for="icode">Código *</label>
                        <input type="text" class="form-control" id="iNcode" name="icode" placeholder="Ingresa código de la ciudad" required>
                        <i class="fa form-control-feedback" id="iconcode"></i>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 col-lg-4 has-feedback" id="gcountry">
                        <label class="control-label" for="icountry">País *</label>
                        <select class="form-control" id="iNcountry" name="icountry" required>
                            <option>Cargando países...</option>
                        </select>
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

<script src="admin/cities/create-ocity.js"></script>