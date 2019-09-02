<?php

include '../../class/classMyDBC.php';
include '../../class/classCity.php';
include '../../src/fn.php';
session_start();

$c = new City();

// DB table to use
$table = 'bb_ciudad_destino';

// Table's primary key
$primaryKey = 'cid_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'cid_id', 'dt' => 0, 'field' => 'cid_id'),
    array('db' => 'cid_nombre', 'dt' => 1, 'field' => 'cid_nombre'),
    array('db' => 'cid_codigo', 'dt' => 2, 'field' => 'cid_codigo'),
    array('db' => 'cid_pais', 'dt' => 3, 'field' => 'cid_pais',
        'formatter' => function ($d, $row) {
            $code = $d;
            include 'rest.getCountry.php';
            return '(' . $data['alpha3Code'] . ') ' . $data['name'];
        }
    ),
    array('db' => 'cid_id', 'dt' => 4, 'field' => 'cid_id',
        'formatter' => function ($d, $row) use ($c) {
            $ct = $c->get($d, 'd');

            if ($ct->cid_activo)
                $string = '<button class="cityDeactivate btn btn-xs btn-danger" id="id_' . $d . '" data-tooltip="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-remove"></i></button>';
            else
                $string = '<button class="cityActivate btn btn-xs btn-success" id="id_' . $d . '" data-tooltip="tooltip" data-placement="top" title="Activar"><i class="fa fa-check"></i></button>';

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
