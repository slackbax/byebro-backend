<?php

include '../../class/classMyDBC.php';
include '../../class/classFood.php';
include '../../src/fn.php';
session_start();

$f = new Food();

// DB table to use
$table = 'bb_comida';

// Table's primary key
$primaryKey = 'com_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'com_id', 'dt' => 0, 'field' => 'com_id'),
    array('db' => 'com_nombre', 'dt' => 1, 'field' => 'com_nombre'),
    array('db' => 'com_descripcion', 'dt' => 2, 'field' => 'com_descripcion'),
    array('db' => 'com_id', 'dt' => 3, 'field' => 'com_id',
        'formatter' => function ($d, $row) use ($f) {
            $ct = $f->get($d);

            $string = '<a class="foodEdit btn btn-xs btn-info" href="index.php?section=tripdetails&sbs=editfood&id=' . $d . '" data-tooltip="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>';

            if ($ct->com_activo)
                $string .= ' <button class="foodDeactivate btn btn-xs btn-danger" id="id_' . $d . '" data-tooltip="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-remove"></i></button>';
            else
                $string .= ' <button class="foodActivate btn btn-xs btn-success" id="id_' . $d . '" data-tooltip="tooltip" data-placement="top" title="Activar"><i class="fa fa-check"></i></button>';

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
