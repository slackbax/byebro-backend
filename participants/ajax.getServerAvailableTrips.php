<?php

include '../class/classMyDBC.php';
include '../class/classViaje.php';
include '../class/classParticipante.php';
include '../src/fn.php';
session_start();

$via = new Viaje();
$par = new Participante();

// DB table to use
$table = 'bb_viaje';

// Table's primary key
$primaryKey = 'vi_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'v.vi_id', 'dt' => 0, 'field' => 'vi_id'),
    array('db' => 'vi_codigo', 'dt' => 1, 'field' => 'vi_codigo'),
    array('db' => 'cio_nombre', 'dt' => 2, 'field' => 'cio_nombre'),
    array('db' => 'cid_nombre', 'dt' => 3, 'field' => 'cid_nombre'),
    array('db' => 'vi_fecha_ini', 'dt' => 4, 'field' => 'vi_fecha_ini',
        'formatter' => function ($d, $row) {
            return getDateBD($d);
        }
    ),
    array('db' => 'vi_fecha_ter', 'dt' => 5, 'field' => 'vi_fecha_ter',
        'formatter' => function ($d, $row) {
            return getDateBD($d);
        }
    ),
    array('db' => 'v.vi_id', 'dt' => 6, 'field' => 'vi_id',
        'formatter' => function ($d, $row) use ($par) {
            $n = $par->getByViaje($d);
            return count($n);
        }
    ),
    array('db' => 'ves.ves_id', 'dt' => 7, 'field' => 'ves_id',
        'formatter' => function ($d, $row) use ($via) {
            $ves = $via->getStateByID($d);
            $str = '';
            switch ($ves->ves_id):
                case 1:
                    $str = '<span class="label bg-info">' . $ves->ves_descripcion . '</span>';
                    break;
                case 2:
                    $str = '<span class="label bg-success">' . $ves->ves_descripcion . '</span>';
                    break;
                case 3:
                    $str = '<span class="label bg-warning">' . $ves->ves_descripcion . '</span>';
                    break;
            endswitch;

            return $str;
        }
    ),
    array('db' => 'v.vi_id', 'dt' => 8, 'field' => 'vi_id',
        'formatter' => function ($d, $row) use ($via) {
            $u = $via->get($d);
            $string = '';
            $string .= '<button id="det_' . $d . '" class="tripDetails btn btn-xs btn-info" data-toggle="modal" data-target="#modal-details" data-tooltip="tooltip" data-placement="top" title="Ver detalles"><i class="fa fa-search-plus"></i></button>';
            $string .= ' <button id="add_' . $d . '" class="tripAdd btn btn-xs btn-success" data-toggle="modal" data-target="#modal-add" data-tooltip="tooltip" data-placement="top" title="Agregar participante"><i class="fa fa-plus"></i></button>';

            return $string;
        }
    )
);

$joinQuery = "FROM bb_viaje v";
$joinQuery .= " JOIN bb_cotizacion c ON v.cot_id = c.cot_id";
$joinQuery .= " JOIN bb_ciudad_origen co ON v.cio_id = co.cio_id";
$joinQuery .= " JOIN bb_ciudad_destino cd ON v.cid_id = cd.cid_id";
$joinQuery .= " JOIN bb_cotizante cot ON c.co_id = cot.co_id";
$joinQuery .= " JOIN bb_viaje_viajestado vies ON v.vi_id = vies.vi_id";
$joinQuery .= " JOIN bb_viaje_estado ves ON vies.ves_id = ves.ves_id";
$extraWhere = " vies.vie_ultimo IS TRUE AND vies.ves_id = 1";
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
