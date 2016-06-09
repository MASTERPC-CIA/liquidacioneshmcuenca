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
        $val = 29825;
        $where_data = array();
        $join_clause = array();
        $where_data['emp.tipo_medic ='] = 'M-A-001';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data['at.fecha_turno >= '] = $fecha_desde;
            $where_data['at.fecha_turno <= '] = $fecha_hasta;
        }
        $join_clause[] = array('table' => 'bill_agenda_turnos at', 'condition' => 'at.id_doctor = emp.id');
        $join_clause[] = array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'at.cod_fact = fvd.facturaventa_codigofactventa');
        $join_clause[] = array('table' => 'billing_producto p', 'condition' => 'p.codigo = fvd.Producto_codigo');
        $join_clause[] = array('table' => 'billing_productogrupo pg', 'condition' => 'p.productogrupo_codigo = pg.codigo');
        $join_clause[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields = 'emp.PersonaComercio_cedulaRuc,emp.nombres nom,emp.apellidos ape,ce.nombreCargo especialidad,SUM(`p`.`costopromediokardex`*pg.prodgp_factor_conv) total';

        $json_res = $this->generic_model->get_join('billing_empleado emp', $where_data, $join_clause, $fields, '', '', $group_by = "emp.PersonaComercio_cedulaRuc");
        $res['data1'] = $json_res;


        //DATA 3
        $where_data3 = array();
        $join_clause3 = array();

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data3['po.pt_ope_fecha >= '] = $fecha_desde;
            $where_data3['po.pt_ope_fecha <= '] = $fecha_hasta;
        }
        $join_clause3[] = array('table' => 'hmc_personal_parteOpe pp', 'condition' => 'pp.per_ope_id = po.pt_ope_idPersonal');
        $join_clause3[] = array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'po.id_fact = fvd.facturaventa_codigofactventa');
        $join_clause3[] = array('table' => 'billing_producto p', 'condition' => 'p.codigo = fvd.Producto_codigo');
        $join_clause3[] = array('table' => 'billing_productogrupo pg', 'condition' => 'p.productogrupo_codigo = pg.codigo');
        $join_clause3[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = pp.per_ope_idCirujano');
        $join_clause3[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields3 = 'emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape,ce.nombreCargo especialidad, SUM(`p`.`costopromediokardex`*pg.prodgp_factor_conv) total';

        $json_res3 = $this->generic_model->get_join('hmc_parte_operatorio po', $where_data3, $join_clause3, $fields3, '', '', $group_by = "emp.PersonaComercio_cedulaRuc");
        $res['data3'] = $json_res3;

        //DATA 2 CARDIOLOGO - NEUROLOGO - DERMATOLOGO
        $where_data2 = array();
        $join_clause2 = array();
        $where_data2['emp.tipo_medic ='] = 'M-A-001';
        //$where_data2[] = ' ';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data2['c.fecha_ingreso >= '] = $fecha_desde;
            $where_data2['c.fecha_ingreso <= '] = $fecha_hasta;
        }
        $join_clause2[] = array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'c.id_fact = fvd.facturaventa_codigofactventa');
        $join_clause2[] = array('table' => 'billing_producto p', 'condition' => 'p.codigo = fvd.Producto_codigo');
        $join_clause2[] = array('table' => 'billing_productogrupo pg', 'condition' => 'p.productogrupo_codigo = pg.codigo');
        $join_clause2[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = c.id_doctor AND (emp.cargosempleado_id = 82 OR emp.cargosempleado_id = 80 OR emp.cargosempleado_id = 59)');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields2 = ' emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape, emp.cargosempleado_id, ce.nombreCargo especialidad,SUM(`p`.`costopromediokardex`*pg.prodgp_factor_conv) total';

        $json_res2 = $this->generic_model->get_join('hmc_consulta c', $where_data2, $join_clause2, $fields2, '', '', $group_by = "emp.PersonaComercio_cedulaRuc");
        $res['data2'] = $json_res2;

        //DATA 4 RADIOLOGO
        $where_data4 = array();
        $join_clause4 = array();
        $where_data4['emp.tipo_medic ='] = 'M-A-001';
        //$where_data2[] = ' ';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data4['sol.sol_fechaCreacion >= '] = $fecha_desde;
            $where_data4['sol.sol_fechaCreacion <= '] = $fecha_hasta;
        }
        $join_clause4[] = array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'sol.id_fact = fvd.facturaventa_codigofactventa');
        $join_clause4[] = array('table' => 'billing_producto p', 'condition' => 'p.codigo = fvd.Producto_codigo');
        $join_clause4[] = array('table' => 'billing_productogrupo pg', 'condition' => 'p.productogrupo_codigo = pg.codigo');

        $join_clause4[] = array('table' => 'informe inf', 'condition' => 'sol.sol_id = inf.inf_solicitudId');
        $join_clause4[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = inf.inf_empleadoId AND (emp.cargosempleado_id = 115 )'); //cargosempleado_id para radiologia
        $join_clause4[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields4 = ' emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape, emp.cargosempleado_id, ce.nombreCargo especialidad,SUM(`p`.`costopromediokardex`*pg.prodgp_factor_conv) total';

        $json_res4 = $this->generic_model->get_join('solicitud sol', $where_data4, $join_clause4, $fields4, '', '', $group_by = "emp.PersonaComercio_cedulaRuc");
        $res['data4'] = $json_res4;

        /* Campos para enviar a exportacion excel */
        $res['fecha_emision_desde'] = $fecha_desde;
        $res['fecha_emision_hasta'] = $fecha_hasta;

        return $res;
    }

}
