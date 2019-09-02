<?php

include '../class/classMyDBC.php';
include '../class/classCotizacion.php';
include '../src/fn.php';
session_start();
$_admin = false;

if (isset($_SESSION['bb_useradmin']) and $_SESSION['bb_useradmin']): $_admin = true; endif;
$quot = new Cotizacion();

// DB table to use
$table = 'bb_cotizacion';

// Table's primary key
$primaryKey = 'cot_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'c.cot_id', 'dt' => 0, 'field' => 'cot_id'),
    array('db' => 'cot_codigo', 'dt' => 1, 'field' => 'cot_codigo'),
    array('db' => 'cio_nombre', 'dt' => 2, 'field' => 'cio_nombre'),
    array('db' => 'cid_nombre', 'dt' => 3, 'field' => 'cid_nombre'),
    array('db' => 'cot_fecha_ini', 'dt' => 4, 'field' => 'cot_fecha_ini',
        'formatter' => function ($d, $row) {
            return getDateBD($d);
        }
    ),
    array('db' => 'cot_fecha_ter', 'dt' => 5, 'field' => 'cot_fecha_ter',
        'formatter' => function ($d, $row) {
            return getDateBD($d);
        }
    ),
    array('db' => 'CONCAT(co_nombres, " ", co_ap, " ", co_am) AS full_name', 'dt' => 6, 'field' => 'full_name',
        'formatter' => function ($d, $row) {
            return $d;
        }
    ),
    array('db' => 'cot_valor', 'dt' => 7, 'field' => 'cot_valor',
        'formatter' => function ($d, $row) {
            return (empty($d)) ? '<i>No ingresado</i>' : number_format($d, 0, '', '.');
        }
    ),
    array('db' => 'cot_link', 'dt' => 8, 'field' => 'cot_link',
        'formatter' => function ($d, $row) {
            return '<a href="" target="_blank">' . $d . '</a>';
        }
    ),
    array('db' => 'ces.ces_id', 'dt' => 9, 'field' => 'ces_id',
        'formatter' => function ($d, $row) use ($quot) {
            $ces = $quot->getStateByID($d);
            $str = '';
            switch ($ces->ces_id):
                case 1:
                    $str = '<span class="label bg-info">' . $ces->ces_descripcion . '</span>';
                    break;
                case 2:
                    $str = '<span class="label bg-success">' . $ces->ces_descripcion . '</span>';
                    break;
                case 3:
                    $str = '<span class="label bg-danger">' . $ces->ces_descripcion . '</span>';
                    break;
                case 4:
                    $str = '<span class="label bg-warning">' . $ces->ces_descripcion . '</span>';
                    break;
            endswitch;

            return $str;
        }
    ),
    array('db' => 'c.cot_id', 'dt' => 10, 'field' => 'cot_id',
        'formatter' => function ($d, $row) use ($quot, $_admin) {
            $c = $quot->get($d);
            $ces = $quot->getState($d);
            $string = '';

            $string .= '<button id="det_' . $d . '" class="quotDetails btn btn-xs btn-info" data-toggle="modal" data-target="#modal-details" data-tooltip="tooltip" data-placement="top" title="Ver detalles"><i class="fa fa-search-plus"></i></button>';
            $string .= ' <button id="cert_' . $d . '" class="quotCert btn btn-xs btn-info" data-tooltip="tooltip" data-placement="top" title="Descargar comprobante"><i class="fa fa-cloud-download"></i></button>';

            if ($_admin):
                if ($ces->ces_id != 2 and $ces->ces_id != 4)
                    $string .= ' <button id="value_' . $d . '" class="quotValue btn btn-xs btn-success" data-tooltip="tooltip" data-placement="top" title="Asignar valor"><i class="fa fa-usd"></i></button>';

                if ($ces->ces_id == 1 and $c->cot_valor != ''):
                    $string .= ' <button id="part_' . $d . '" class="quotTrip btn btn-xs btn-success" data-tooltip="tooltip" data-placement="top" title="Aceptar y asignar viaje"><i class="fa fa-plane"></i></button>';
                    $string .= ' <button id="rej_' . $d . '" class="quotReject btn btn-xs btn-danger" data-tooltip="tooltip" data-placement="top" title="Rechazar"><i class="fa fa-remove"></i></button>';
                endif;
            endif;

            return $string;
        }
    )
);

$joinQuery = "FROM bb_cotizacion c";
$joinQuery .= " JOIN bb_ciudad_origen co ON c.cio_id = co.cio_id";
$joinQuery .= " JOIN bb_ciudad_destino cd ON c.cid_id = cd.cid_id";
$joinQuery .= " JOIN bb_cotizante cot ON c.co_id = cot.co_id";
$joinQuery .= " JOIN bb_cotizacion_cotestado coes ON c.cot_id = coes.cot_id";
$joinQuery .= " JOIN bb_cotizacion_estado ces ON coes.ces_id = ces.ces_id";
$extraWhere = " coes.cote_ultimo IS TRUE";
$groupBy = "";
$having = "";

// SQL server connection information
$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASSWORD,
    'db' => DB_DATABASE,
    'host' => DB_HOST
);

require '../src/ssp2.class.php';

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
