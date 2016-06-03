<?php

/**
 * Description of informe_model
 *
 * @author PJ
 */
class Reporte_models extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_turnos($fecha_desde, $fecha_hasta) {

        //DATA 1
        $where_data = array();
        $join_clause = array();
        $where_data['emp.tipo_medic ='] = 'M-A-001';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data['fv.fechaCreacion >= '] = $fecha_desde;
            $where_data['fv.fechaCreacion <= '] = $fecha_hasta;
        }
        $join_clause[] = array('table' => 'bill_agenda_turnos at', 'condition' => 'at.id_doctor = emp.id');
        $join_clause[] = array('table' => 'billing_facturaventa fv', 'condition' => 'at.cod_fact = fv.codigofactventa');
        $join_clause[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields = 'emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape,ce.nombreCargo especialidad, SUM(fv.totalCompra) total';

        $json_res = $this->generic_model->get_join('billing_empleado emp', $where_data, $join_clause, $fields, '');
        $res['data1'] = $json_res;


        //DATA 3
        $where_data3 = array();
        $join_clause3 = array();

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data3['fv.fechaCreacion >= '] = $fecha_desde;
            $where_data3['fv.fechaCreacion <= '] = $fecha_hasta;
        }
        $join_clause3[] = array('table' => 'hmc_personal_parteope pp', 'condition' => 'pp.per_ope_id = po.pt_ope_idPersonal');
        $join_clause3[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = pp.per_ope_idCirujano');
        $join_clause3[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $join_clause3[] = array('table' => 'billing_facturaventa fv', 'condition' => 'po.id_fact = fv.codigofactventa');
        $fields3 = 'emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape,ce.nombreCargo especialidad, SUM(fv.totalCompra) total';

        $json_res3 = $this->generic_model->get_join('hmc_parte_operatorio po', $where_data3, $join_clause3, $fields3, '');
        $res['data3'] = $json_res3;

        //DATA 4 dermatologia
        $where_data4 = array();
        $join_clause4 = array();
        $where_data4['emp.tipo_medic ='] = 'M-A-001';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data4['fv.fechaCreacion >= '] = $fecha_desde;
            $where_data4['fv.fechaCreacion <= '] = $fecha_hasta;
        }
        $join_clause4[] = array('table' => 'hmc_procedimiento_consulta pc', 'condition' => 'pc.id_consulta = c.id');
        //$join_clause4[] = array('table' => 'billing_facturaventa fv', 'condition' => 'at.cod_fact = fv.codigofactventa');
        //$join_clause4[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields4 = 'emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape,ce.nombreCargo especialidad, SUM(fv.totalCompra) total';

        $json_res4 = $this->generic_model->get_join('hmc_consulta c', $where_data4, $join_clause4, $fields4, '');
        $res['data4'] = $json_res4;

        /* Campos para enviar a exportacion excel */
        $res['fecha_emision_desde'] = $fecha_desde;
        $res['fecha_emision_hasta'] = $fecha_hasta;

        return $res;
    }

}
