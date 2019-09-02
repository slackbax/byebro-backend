<?php

include '../../class/classMyDBC.php';
include '../../src/fn.php';
session_start();

// DB table to use
$table = 'bb_cargo';

// Table's primary key
$primaryKey = 'car_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'car_id', 'dt' => 0, 'field' => 'car_id'),
    array('db' => 'car_nombre', 'dt' => 1, 'field' => 'car_nombre'),
    array('db' => 'car_descripcion', 'dt' => 2, 'field' => 'car_descripcion'),
    array('db' => 'car_id', 'dt' => 3, 'field' => 'car_id',
        'formatter' => function ($d, $row) use ($us) {
            $string = '<a class="positionEdit btn btn-xs btn-info" href="index.php?section=personal&sbs=editposition&id=' . $d . '" data-tooltip="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>';

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
