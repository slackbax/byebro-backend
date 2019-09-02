<?php

include '../../class/classMyDBC.php';
include '../../class/classExtra.php';
include '../../src/fn.php';
session_start();

$e = new Extra();

// DB table to use
$table = 'bb_adicional';

// Table's primary key
$primaryKey = 'adi_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'adi_id', 'dt' => 0, 'field' => 'adi_id'),
    array('db' => 'adi_nombre', 'dt' => 1, 'field' => 'adi_nombre'),
    array('db' => 'adi_grupal', 'dt' => 2, 'field' => 'adi_grupal',
        'formatter' => function ($d) {
            return ($d) ? 'GRUPAL' : 'INDIVIDUAL';
    }),
    array('db' => 'adi_descripcion', 'dt' => 3, 'field' => 'adi_descripcion'),
    array('db' => 'adi_id', 'dt' => 4, 'field' => 'adi_id',
        'formatter' => function ($d, $row) use ($e) {
            $ct = $e->get($d);

            $string = '<a class="foodEdit btn btn-xs btn-info" href="index.php?section=tripdetails&sbs=editextra&id=' . $d . '" data-tooltip="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>';

            if ($ct->adi_activo)
                $string .= ' <button class="extraDeactivate btn btn-xs btn-danger" id="id_' . $d . '" data-tooltip="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-remove"></i></button>';
            else
                $string .= ' <button class="extraActivate btn btn-xs btn-success" id="id_' . $d . '" data-tooltip="tooltip" data-placement="top" title="Activar"><i class="fa fa-check"></i></button>';

            return $string;
        }
    )
);

$joinQuery = "";
$extraWhere = "";
$groupBy = "";
$having = "";

// SQL server connection information
$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASSWORD,
    'db' => DB_DATABASE,
    'host' => DB_HOST
);

require '../../src/ssp2.class.php';

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
