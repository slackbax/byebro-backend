<?php

class Group {

	public function __construct() {
	}

	/**
	 * @param $id
	 * @return stdClass
	 */
	public function get($id) {
		$db = new myDBC();
		$stmt = $db->Prepare("SELECT * FROM sgdoc_grupo g
                            JOIN sgdoc_perfil p ON g.per_id = p.per_id
                            WHERE g.gr_id = ?");

		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = new stdClass();

		$row = $result->fetch_assoc();
		$obj->gr_id = $row['gr_id'];
		$obj->gr_nombre = utf8_encode($row['gr_nombre']);
		$obj->gr_pid = $row['per_id'];
		$obj->gr_pnombre = utf8_encode($row['per_nombre']);
		$obj->gr_fecha = $row['gr_fecha'];

		unset($db);
		return $obj;
	}

	/**
	 * @return array
	 */
	public function getAll() {
		$db = new myDBC();
		$stmt = $db->Prepare("SELECT gr_id FROM sgdoc_grupo WHERE gr_existe = TRUE ORDER BY gr_id ASC");

		$stmt->execute();
		$result = $stmt->get_result();
		$lista = [];

		while ($row = $result->fetch_assoc()):
			$lista[] = $this->get($row['gr_id']);
		endwhile;

		unset($db);
		return $lista;
	}

	/**
	 * @param $id
	 * @return array|bool|mysqli_result
	 */
	public function getIsEmpty($id) {
		$db = new myDBC();

		try {
			$stmt = $db->Prepare("SELECT COUNT(ug.us_id) AS n FROM sgdoc_usuario_grupo ug
                                    JOIN sgdoc_usuario u ON ug.us_id = u.us_id
                                    WHERE ug.gr_id = ? AND u.us_existe = TRUE");

			if (!$stmt):
				throw new Exception("La consulta del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La consulta del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La consulta del grupo falló en su ejecución.");
			endif;

			$result = $stmt->get_result();
			$tnum = $result->fetch_assoc();

			if ($tnum['n'] > 0):
				$result = array('estado' => true, 'msg' => 'El grupo seleccionado tiene usuarios asociados y debe eliminarlos antes de eliminar el grupo.');
			else:
				$result = array('estado' => true, 'msg' => 'OK');
			endif;

			$stmt->close();
			return $result;
		} catch (Exception $e) {
			$result = array('estado' => false, 'msg' => $e->getMessage());
			return $result;
		}
	}

	/**
	 * @param $name
	 * @param $profile
	 * @param null $db
	 * @return array
	 */
	public function set($name, $profile, $db = null) {
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("INSERT INTO sgdoc_grupo (gr_nombre, per_id, gr_fecha, gr_existe) VALUES (?, ?, CURRENT_DATE, TRUE)");

			if (!$stmt):
				throw new Exception("La inserción del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("si", utf8_decode($db->clearText($name)), $profile);
			if (!$bind):
				throw new Exception("La inserción del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La inserción del grupo falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => $stmt->insert_id);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			$result = array('estado' => false, 'msg' => $e->getMessage());
			return $result;
		}
	}

	/**
	 * @param $id
	 * @param null $db
	 * @return array
	 */
	public function del($id, $db = null) {
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE sgdoc_grupo SET gr_existe = FALSE WHERE gr_id = ?");

			if (!$stmt):
				throw new Exception("La eliminación del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("i", $id);
			if (!$bind):
				throw new Exception("La eliminación del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La eliminación del grupo falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => 'OK');
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			$result = array('estado' => false, 'msg' => $e->getMessage());
			return $result;
		}
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $pro_id
	 * @param null $db
	 * @return array
	 */
	public function mod($id, $name, $pro_id, $db = null) {
		if (is_null($db)):
			$db = new myDBC();
		endif;

		try {
			$stmt = $db->Prepare("UPDATE sgdoc_grupo SET gr_nombre = ?, per_id = ? WHERE gr_id = ?");

			if (!$stmt):
				throw new Exception("La modificación del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("sii", utf8_decode($db->clearText($name)), $pro_id, $id);
			if (!$bind):
				throw new Exception("La modificación del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La modificación del grupo falló en su ejecución.");
			endif;

			$result = array('estado' => true, 'msg' => true);
			$stmt->close();
			return $result;
		} catch (Exception $e) {
			$result = array('estado' => false, 'msg' => $e->getMessage());
			return $result;
		}
	}

	/**
	 * @param $group
	 * @return array
	 */
	public function existsGroup($group) {
		$db = new myDBC();

		try {
			$stmt = $db->Prepare("SELECT COUNT(gr_id) AS n FROM sgdoc_grupo WHERE gr_nombre = ?");

			if (!$stmt):
				throw new Exception("La búsqueda del grupo falló en su preparación.");
			endif;

			$bind = $stmt->bind_param("s", utf8_decode($db->clearText($group)));
			if (!$bind):
				throw new Exception("La búsqueda del grupo falló en su binding.");
			endif;

			if (!$stmt->execute()):
				throw new Exception("La búsqueda del grupo falló en su ejecución.");
			endif;

			$result = $stmt->get_result();
			$tnum = $result->fetch_assoc();

			if ($tnum['n'] > 0):
				$result = array('estado' => true, 'msg' => true);
			else:
				$result = array('estado' => true, 'msg' => false);
			endif;

			$stmt->close();
			return $result;
		} catch (Exception $e) {
			$result = array('estado' => false, 'msg' => $e->getMessage());
			return $result;
		}
	}
}