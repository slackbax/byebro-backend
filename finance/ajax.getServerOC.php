<?php

include '../class/classMyDBC.php';
include '../class/classOrdenCompra.php';
include '../src/fn.php';
session_start();

$oc = new OrdenCompra();
// DB table to use
$table = 'bb_oc';

// Table's primary key
$primaryKey = 'oc_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'oc_id', 'dt' => 0, 'field' => 'oc_id'),
    array('db' => 'oc_flow', 'dt' => 1, 'field' => 'oc_flow'),
    array('db' => 'oc_medio', 'dt' => 2, 'field' => 'oc_medio'),
    array('db' => 'oc_fecha', 'dt' => 3, 'field' => 'oc_fecha',
        'formatter' => function ($d, $row) {
            return getDateHourBD($d);
        }
    ),
    array('db' => 'oc_fecha_pago', 'dt' => 4, 'field' => 'oc_fecha_pago',
        'formatter' => function ($d, $row) {
            return ($d != '' and $d != '0000-00-00 00:00:00') ? getDateHourBD($d) : 'N/A';
        }
    ),
    array('db' => 'oc_monto', 'dt' => 5, 'field' => 'oc_monto',
        'formatter' => function ($d, $row) {
            return number_format($d, 0, '', '.');
        }
    ),
    array('db' => 'oc_fee', 'dt' => 6, 'field' => 'oc_fee',
        'formatter' => function ($d, $row) {
            return number_format($d, 0, '', '.');
        }
    ),
    array('db' => 'oc_balance', 'dt' => 7, 'field' => 'oc_balance',
        'formatter' => function ($d, $row) {
            return number_format($d, 0, '', '.');
        }
    ),
    array('db' => 'oc_transfer', 'dt' => 8, 'field' => 'oc_transfer',
        'formatter' => function ($d, $row) {
            return ($d != '' and $d != '0000-00-00') ? getDateBD($d) : 'N/A';
        }
    ),
    array('db' => 'oc_estado', 'dt' => 9, 'field' => 'oc_estado',
        'formatter' => function ($d, $row) {
            switch ($d):
                case 1:
                    $str = '<span class="label bg-warning">PENDIENTE</span>';
                    break;
                case 2:
                    $str = '<span class="label bg-success">PAGADA</span>';
                    break;
                case 3:
                case 4:
                    $str = '<span class="label bg-danger">ANULADA</span>';
                    break;
            endswitch;

            return $str;
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

require '../src/ssp2.class.php';

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having)
);
