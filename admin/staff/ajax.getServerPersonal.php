<?php

include '../../class/classMyDBC.php';
include '../../class/classPersonal.php';
include '../../src/fn.php';
session_start();

$p = new Personal();

// DB table to use
$table = 'bb_personal';

// Table's primary key
$primaryKey = 'per_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'p.per_id', 'dt' => 0, 'field' => 'per_id'),
    array('db' => 'per_nombres', 'dt' => 1, 'field' => 'per_nombres'),
    array('db' => 'per_ap', 'dt' => 2, 'field' => 'per_ap'),
    array('db' => 'per_am', 'dt' => 3, 'field' => 'per_am'),
    array('db' => 'per_telefono', 'dt' => 4, 'field' => 'per_telefono'),
    array('db' => 'per_email', 'dt' => 5, 'field' => 'per_email'),
    array('db' => 'us_username', 'dt' => 6, 'field' => 'us_username'),
    array('db' => 'p.per_id', 'dt' => 7, 'field' => 'per_id',
        'formatter' => function ($d, $row) use ($p) {
            $u = $p->get($d);

            $string = '<a class="personalEdit btn btn-xs btn-info" href="index.php?section=personal&sbs=editpersonal&id=' . $d . '" data-tooltip="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>';
            if ($u->per_activo):
                $string .= ' <button id="del_' . $d . '" class="personalDelete btn btn-xs btn-danger" data-tooltip="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-remove"></i></button>';
            else:
                $string .= ' <button id="act_' . $d . '" class="personalActivate btn btn-xs btn-success" data-tooltip="tooltip" data-placement="top" title="Reactivar"><i class="fa fa-check"></i></button>';
            endif;

            return $string;
        }
    )
);

$joinQuery = "FROM bb_personal p";
$joinQuery .= " LEFT JOIN bb_personal_usuario pu ON p.per_id = pu.per_id";
$joinQuery .= " LEFT JOIN bb_usuario u ON pu.us_id = u.us_id";
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
