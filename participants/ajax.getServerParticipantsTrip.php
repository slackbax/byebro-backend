<?php

include '../class/classMyDBC.php';
include '../class/classCotizacion.php';
include '../class/classParticipante.php';
include '../src/fn.php';
session_start();

$quot = new Cotizacion();
$par = new Participante();

// DB table to use
$table = 'bb_participante';

// Table's primary key
$primaryKey = 'par_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'par_id', 'dt' => 0, 'field' => 'par_id'),
    array('db' => 'cot_codigo', 'dt' => 1, 'field' => 'cot_codigo'),
    array('db' => 'par_rut', 'dt' => 2, 'field' => 'par_rut'),
    array('db' => 'par_nombres', 'dt' => 3, 'field' => 'par_nombres'),
    array('db' => 'par_ap', 'dt' => 4, 'field' => 'par_ap'),
    array('db' => 'par_am', 'dt' => 5, 'field' => 'par_am'),
    array('db' => 'par_edad', 'dt' => 6, 'field' => 'par_edad'),
    array('db' => 'par_email', 'dt' => 7, 'field' => 'par_email'),
    array('db' => 'par_telefono', 'dt' => 8, 'field' => 'par_telefono'),
    array('db' => 'par_id', 'dt' => 9, 'field' => 'par_id',
        'formatter' => function ($d, $row) {
            $string = '';
            $string .= '<button id="edit_' . $d . '" class="quotEdit btn btn-xs btn-success" data-toggle="modal" data-target="#modal-trip-edit" data-tooltip="tooltip" data-placement="top" title="Editar datos"><i class="fa fa-pencil"></i></button>';

            return $string;
        }
    )
);

$joinQuery = " FROM bb_participante p";
$joinQuery .= " JOIN bb_cotizacion c ON p.cot_id = c.cot_id";
$joinQuery .= " WHERE par_viaja IS TRUE";
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
