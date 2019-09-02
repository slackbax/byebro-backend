<?php

session_start();
include '../../class/classMyDBC.php';
include '../../class/classUser.php';
include '../../src/fn.php';
include '../../src/sessionControl.ajax.php';

if (extract($_POST)):
	$db = new myDBC();
	$user = new User();
	$_islog = false;
	$_default = false;
	$idate = setDateBD($idate);

	if ($_SESSION['bb_userid'] == $id):
		$_islog = true;
	endif;

	try {
		$db->autoCommit(FALSE);
		$ins = $user->modProfile($id, $iname, $ilastnamep, $ilastnamem, $iemail, $db);

		if (!$ins['estado']):
			throw new Exception('Error al guardar los datos del usuario. ' . $ins['msg'], 0);
		endif;

		if ($_islog):
			$_SESSION['bb_userfname'] = $iname;
			$_SESSION['bb_userlnamep'] = $ilastnamep;
			$_SESSION['bb_userlnamem'] = $ilastnamem;
			$_SESSION['bb_useremail'] = $iemail;
		endif;

		if (!empty($_FILES)):
			$targetFolder = '/byebro/dist/img/users/';
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;

			$u = $user->get($id);

			if ($u->us_pic == 'users/no-photo.png'):
				$_default = true;
			endif;

			$img_old = $_SERVER['DOCUMENT_ROOT'] . '/byebro/dist/img/' . $u->us_pic;

			if (!is_readable($img_old)):
				throw new Exception('El archivo solicitado no existe.');
			endif;

			if (!$_default):
				if (!unlink($img_old)):
					throw new Exception('Error al eliminar la imagen antigua.', 0);
				endif;
			endif;

			foreach ($_FILES as $aux => $file):
				$tempFile = $file['tmp_name'][0];
				$targetFile = rtrim($targetPath, '/') . '/' . $id . '_' . $file['name'][0];
				move_uploaded_file($tempFile, $targetFile);
			endforeach;

			$pic_route = 'users/' . $id . '_' . $file['name'][0];

			$ins = $user->setPicture($id, $pic_route, $db);

			if (!$ins):
				throw new Exception('Error al guardar la imagen. ' . $ins['msg'], 0);
			endif;

			$_SESSION['bb_userpic'] = 'users/' . $id . '_' . $file['name'][0];
		endif;

		$db->Commit();
		$db->autoCommit(TRUE);
		$response = array('type' => true, 'msg' => 'OK');
		echo json_encode($response);
	} catch (Exception $e) {
		$db->Rollback();
		$db->autoCommit(TRUE);
		$response = array('type' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode());
		echo json_encode($response);
	}
endif;
