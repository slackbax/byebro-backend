<?php

include '../../class/classMyDBC.php';
include '../../class/classUser.php';
include '../../src/fn.php';
session_start();

$us = new User();

// DB table to use
$table = 'bb_usuario';

// Table's primary key
$primaryKey = 'us_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array('db' => 'us_nombres', 'dt' => 0, 'field' => 'us_nombres'),
	array('db' => 'us_ap', 'dt' => 1, 'field' => 'us_ap'),
	array('db' => 'us_am', 'dt' => 2, 'field' => 'us_am'),
	array('db' => 'us_username', 'dt' => 3, 'field' => 'us_username'),
	array('db' => 'us_registro', 'dt' => 4, 'field' => 'us_registro',
        'formatter' => function ($d, $row) {
            return getDateHourToForm($d);
        }
    ),
    array('db' => 'ses_time', 'dt' => 5, 'field' => 'ses_time',
		'formatter' => function ($d, $row) {
            return (!empty($d)) ? getDateHourToForm($d) : 'No registra sesiones';
		}
    ),
	array('db' => 'u.us_id', 'dt' => 6, 'field' => 'us_id',
		'formatter' => function ($d, $row) use ($us) {
	        $u = $us->get($d);

			$string = '<a class="userEdit btn btn-xs btn-info" href="index.php?section=users&sbs=edituser&id=' . $d . '" data-tooltip="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a>';
			if ($u->us_activo):
			    $string .= ' <button id="del_' . $d . '" class="userDelete btn btn-xs btn-danger" data-tooltip="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-remove"></i></button>';
			else:
                $string .= ' <button id="act_' . $d . '" class="userActivate btn btn-xs btn-success" data-tooltip="tooltip" data-placement="top" title="Reactivar"><i class="fa fa-check"></i></button>';
			endif;

			return $string;
		}
	)
);

$joinQuery = "FROM bb_usuario u";
$joinQuery .= " JOIN bb_perfil pf ON u.perf_id = pf.perf_id";
$joinQuery .= " LEFT JOIN bb_sesion s ON u.us_id = s.us_id";
$extraWhere = " s.ses_ultima IS TRUE OR s.ses_ultima IS NULL";
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
