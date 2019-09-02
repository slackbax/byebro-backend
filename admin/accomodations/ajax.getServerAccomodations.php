<?php

include '../../class/classMyDBC.php';
include '../../class/classAccomodation.php';
include '../../class/classCity.php';
include '../../src/fn.php';
session_start();

$a = new Accomodation();
$c = new City();

// DB table to use
$table = 'bb_alojamiento';

// Table's primary key
$primaryKey = 'alo_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'alo_id', 'dt' => 0, 'field' => 'alo_id'),
    array('db' => 'alo_pic', 'dt' => 1, 'field' => 'alo_pic',
        'formatter' => function ($d, $row) {
            return '<a href="' . $d . '" target="_blank"><img src="' . $d . '" height="60px"></a>';
        }
    ),
    array('db' => 'alo_nombre', 'dt' => 2, 'field' => 'alo_nombre'),
    array('db' => 'alo_descripcion', 'dt' => 3, 'field' => 'alo_descripcion'),
    array('db' => 'alo_rooms', 'dt' => 4, 'field' => 'alo_rooms'),
    array('db' => 'alo_baths', 'dt' => 5, 'field' => 'alo_baths'),
    array('db' => 'cid_id', 'dt' => 6, 'field' => 'cid_id',
        'formatter' => function ($d, $row) use ($c) {
            $ct = $c->get($d, 'd');
            return $ct->cid_nombre . ' (' . $ct->cid_pais . ')';
        }
    ),
    array('db' => 'alo_id', 'dt' => 7, 'field' => 'alo_id',
        'formatter' => function ($d, $row) use ($a) {
            $ac = $a->get($d);
            $string = '';

            $string .= '<a class="userEdit btn btn-xs btn-info" href="index.php?section=tripdetails&sbs=editaccomodation&id=' . $d . '" data-tooltip="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>';
            if ($ac->alo_activo)
                $string .= ' <button class="accomDeactivate btn btn-xs btn-danger" id="id_' . $d . '" data-tooltip="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-remove"></i></button>';
            else
                $string .= ' <button class="accomActivate btn btn-xs btn-success" id="id_' . $d . '" data-tooltip="tooltip" data-placement="top" title="Activar"><i class="fa fa-check"></i></button>';

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
