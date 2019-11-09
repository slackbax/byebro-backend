<?php

class Oc
{
    public function __construct()
    {
    }

    /**
     * @param $id
     * @param null $db
     * @return stdClass
     */
    public function get($id, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT * FROM bb_oc u WHERE oc_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $obj = new stdClass();
        $obj->oc_id = $row['oc_id'];
        $obj->par_id = $row['par_id'];
        $obj->oc_monto = $row['oc_monto'];
        $obj->oc_procesada = $row['oc_descripcion'];
        $obj->oc_aceptada = $row['oc_aceptada'];

        unset($db);
        return $obj;
    }

    /**
     * @param null $db
     * @return array
     */
    public function getAll($db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        $stmt = $db->Prepare("SELECT oc_id FROM bb_oc");
        $stmt->execute();
        $result = $stmt->get_result();
        $lista = [];

        while ($row = $result->fetch_assoc()):
            $lista[] = $this->get($row['oc_id'], $db);
        endwhile;

        unset($db);
        return $lista;
    }

    /**
     * @param $par
     * @param null $db
     * @return array
     */
    public function set($par, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("INSERT INTO bb_oc (par_id) VALUES (?)");

            if (!$stmt):
                throw new Exception("La inserción de la OC falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("i", $db->clearText($par));

            if (!$bind):
                throw new Exception("La inserción de la OC falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La inserción de la OC falló en su ejecución.");
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
     * @param $oc
     * @param $flow
     * @param $medio
     * @param $monto
     * @param $fee
     * @param $balance
     * @param $transfer
     * @param $pago
     * @param $estado
     * @param null $db
     * @return array
     */
    public function mod($oc, $flow, $medio, $monto, $fee, $balance, $transfer, $pago, $estado, $db = null)
    {
        if (is_null($db)):
            $db = new myDBC();
        endif;

        try {
            $stmt = $db->Prepare("UPDATE bb_oc SET oc_flow = ?, oc_medio = ?, oc_monto = ?, oc_fee = ?, oc_balance = ?, oc_transfer = ?, oc_fecha_pago = ?, oc_estado = ? WHERE oc_id = ?");

            if (!$stmt):
                throw new Exception("La modificación de la OC falló en su preparación.");
            endif;

            $bind = $stmt->bind_param("isiiissii", $db->clearText($flow), utf8_decode($db->clearText($medio)),
                $db->clearText($monto), $db->clearText($fee), $db->clearText($balance), $db->clearText($transfer), $db->clearText($pago), $db->clearText($estado), $oc);

            if (!$bind):
                throw new Exception("La modificación de la OC falló en su binding.");
            endif;

            if (!$stmt->execute()):
                throw new Exception("La modificación de la OC falló en su ejecución.");
            endif;

            $result = array('estado' => true, 'msg' => 'OK');
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            $result = array('estado' => false, 'msg' => $e->getMessage());
            return $result;
        }
    }
}