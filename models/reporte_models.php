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

    public function get_consulta($fecha_desde, $fecha_hasta, $tipo) {
        $where_data['emp.tipo_medic ='] = $tipo;

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data['at.fecha_turno >= '] = $fecha_desde;
            $where_data['at.fecha_turno <= '] = $fecha_hasta;
        }

        $join_clause[] = array('table' => 'billing_cliente cli', 'condition' => 'at.id_paciente = cli.id');
        $join_clause[] = array('table' => 'billing_facturaventa fv', 'condition' => 'at.cod_fact = fv.codigofactventa');
        $join_clause[] = array('table' => 'billing_empleado emp', 'condition' => 'at.id_doctor = emp.id');
        $join_clause[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields = 'emp.tipo_medic, emp.PersonaComercio_cedulaRuc,emp.nombres nom,emp.apellidos ape,ce.nombreCargo especialidad,CASE cli.clientetipo_idclientetipo WHEN 3 THEN fv.totalCompra *2 WHEN 4 THEN fv.totalCompra *2 ELSE fv.totalCompra END as total,cli.PersonaComercio_cedulaRuc cedula_cli,cli.nombres nom_cli,cli.apellidos ape_cli';

        $json_res = $this->generic_model->get_join('bill_agenda_turnos at', $where_data, $join_clause, $fields, '', '');
        $res['data1'] = $json_res;

        return $res;
    }

    public function get_servicio($fecha_desde, $fecha_hasta) {
        $where_data2['emp.tipo_medic ='] = 'M-A-001';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data2['c.fecha_ingreso >= '] = $fecha_desde;
            $where_data2['c.fecha_ingreso <= '] = $fecha_hasta;
        }
        $join_clause2[] = array('table' => 'billing_cliente cli', 'condition' => 'c.id_cliente = cli.id');
        $join_clause2[] = array('table' => 'billing_facturaventa fv', 'condition' => 'c.id_fact = fv.codigofactventa'); //gastroenterologia = 83 - oftalmologia = 60  - traumatologia = 88
        $join_clause2[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = c.id_doctor AND (emp.cargosempleado_id = 83 OR emp.cargosempleado_id = 60 OR emp.cargosempleado_id = 88)');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields2 = 'emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape, emp.cargosempleado_id, ce.nombreCargo especialidad,CASE cli.clientetipo_idclientetipo WHEN 3 THEN fv.totalCompra *2 WHEN 4 THEN fv.totalCompra *2 ELSE fv.totalCompra END as total,cli.PersonaComercio_cedulaRuc cedula_cli,cli.nombres nom_cli,cli.apellidos ape_cli';

        $json_res2 = $this->generic_model->get_join('hmc_consulta c', $where_data2, $join_clause2, $fields2, '', '', '');
        $res['data1'] = $json_res2;

        return $res;
    }

    public function get_proced($fecha_desde, $fecha_hasta) {
        $where_data2['emp.tipo_medic ='] = 'M-A-001';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data2['c.fecha_ingreso >= '] = $fecha_desde;
            $where_data2['c.fecha_ingreso <= '] = $fecha_hasta;
        }
        $join_clause2[] = array('table' => 'billing_cliente cli', 'condition' => 'c.id_cliente = cli.id');
        $join_clause2[] = array('table' => 'billing_facturaventa fv', 'condition' => 'c.id_fact = fv.codigofactventa'); //cardiologo = 80 - neurologo = 59  - dermatologo = 82
        $join_clause2[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = c.id_doctor AND (emp.cargosempleado_id = 80 OR emp.cargosempleado_id = 59 OR emp.cargosempleado_id = 82)');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields2 = 'emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape, emp.cargosempleado_id, ce.nombreCargo especialidad,CASE cli.clientetipo_idclientetipo WHEN 3 THEN fv.totalCompra *2 WHEN 4 THEN fv.totalCompra *2 ELSE fv.totalCompra END as total,cli.PersonaComercio_cedulaRuc cedula_cli,cli.nombres nom_cli,cli.apellidos ape_cli';

        $json_res2 = $this->generic_model->get_join('hmc_consulta c', $where_data2, $join_clause2, $fields2, '', '', '');
        $res['data1'] = $json_res2;

        //DATA 2 RADIOLOGO
        $where_data4['emp.tipo_medic ='] = 'M-A-001';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data4['sol.sol_fechaCreacion >= '] = $fecha_desde;
            $where_data4['sol.sol_fechaCreacion <= '] = $fecha_hasta;
        }
        $join_clause4[] = array('table' => 'billing_cliente cli', 'condition' => 'sol.sol_paciente_id = cli.id');
        $join_clause4[] = array('table' => 'billing_facturaventa fv', 'condition' => 'sol.id_fact = fv.codigofactventa');
        $join_clause4[] = array('table' => 'informe inf', 'condition' => 'sol.sol_id = inf.inf_solicitudId');
        $join_clause4[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = inf.inf_empleadoId AND (emp.cargosempleado_id = 115 )'); //cargosempleado_id para radiologia
        $join_clause4[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields4 = ' emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape, emp.cargosempleado_id, ce.nombreCargo especialidad,CASE cli.clientetipo_idclientetipo WHEN 3 THEN fv.totalCompra *2 WHEN 4 THEN fv.totalCompra *2 ELSE fv.totalCompra END as total,cli.PersonaComercio_cedulaRuc cedula_cli,cli.nombres nom_cli,cli.apellidos ape_cli';

        $json_res4 = $this->generic_model->get_join('solicitud sol', $where_data4, $join_clause4, $fields4, '', '', '');
        $res['data2'] = $json_res4;

        return $res;
    }

    public function get_h_alta($fecha_desde, $fecha_hasta) {
        $where_data2['emp.tipo_medic ='] = 'M-A-001';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data2['ha.fecha_registro >= '] = $fecha_desde;
            $where_data2['ha.fecha_registro <= '] = $fecha_hasta;
        }
        $join_clause2[] = array('table' => 'billing_cliente cli', 'condition' => 'ha.id_cliente = cli.id');
        $join_clause2[] = array('table' => 'hmc_hoja_alta_detalle had', 'condition' => 'ha.id = had.det_hoja_id_hoja'); //gastroenterologia = 83 - oftalmologia = 60  - traumatologia = 88
        $join_clause2[] = array('table' => 'billing_producto p', 'condition' => 'p.codigo2 = had.det_hoja_cod_servicio');
        $join_clause2[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = had.det_id_empleado');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields2 = 'emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape, emp.cargosempleado_id, ce.nombreCargo especialidad,CASE cli.clientetipo_idclientetipo WHEN 3 THEN p.costopromediokardex *2 WHEN 4 THEN p.costopromediokardex *2 ELSE p.costopromediokardex END as total,cli.PersonaComercio_cedulaRuc cedula_cli,cli.nombres nom_cli,cli.apellidos ape_cli';

        $json_res2 = $this->generic_model->get_join('hmc_hoja_de_alta ha', $where_data2, $join_clause2, $fields2, '', '', '');
        $res['data1'] = $json_res2;

        return $res;
    }

    public function get_quirof($fecha_desde, $fecha_hasta) {
        $where_data2['emp.tipo_medic ='] = 'M-A-001';

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data2['ha.fecha_registro >= '] = $fecha_desde;
            $where_data2['ha.fecha_registro <= '] = $fecha_hasta;
        }
        $join_clause2[] = array('table' => 'billing_cliente cli', 'condition' => 'po.pt_ope_idCliente = cli.id');
        $join_clause2[] = array('table' => 'hmc_parte_det_producto pdp', 'condition' => 'po.pt_ope_id = pdp.parte_prod_id_parte');
        $join_clause2[] = array('table' => 'billing_producto p', 'condition' => 'p.codigo = pdp.parte_prod_id_prod');
        $join_clause2[] = array('table' => 'hmc_personal_parteope ppo', 'condition' => 'po.pt_ope_id = ppo.per_ope_id'); //gastroenterologia = 83 - oftalmologia = 60  - traumatologia = 88
        $join_clause2[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = ppo.per_ope_idCirujano');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $join_clause2[] = array('table' => 'billing_empleado emp1', 'condition' => 'emp1.id = ppo.per_ope_idAnastesi', 'type' => 'LEFT');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce1', 'condition' => 'emp1.cargosempleado_id = ce1.id', 'type' => 'LEFT');
        $join_clause2[] = array('table' => 'billing_empleado emp2', 'condition' => 'emp2.id = ppo.per_ope_idAyud1', 'type' => 'LEFT');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce2', 'condition' => 'emp2.cargosempleado_id = ce2.id', 'type' => 'LEFT');
        $join_clause2[] = array('table' => 'billing_empleado emp3', 'condition' => 'emp3.id = ppo.per_ope_idAyud2', 'type' => 'LEFT');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce3', 'condition' => 'emp3.cargosempleado_id = ce3.id', 'type' => 'LEFT');
        $fields2 = 'emp.PersonaComercio_cedulaRuc,emp.nombres nom,emp.apellidos ape,emp.cargosempleado_id,ce.nombreCargo especialidad,'
                . 'emp1.PersonaComercio_cedulaRuc ced1, emp1.nombres nom1, emp1.apellidos ape1, emp1.cargosempleado_id ce1 ,ce1.nombreCargo especialidad1,'
                . 'emp2.PersonaComercio_cedulaRuc ced2, emp2.nombres nom2, emp2.apellidos ape2, emp2.cargosempleado_id ce2 ,ce2.nombreCargo especialidad2,'
                . 'emp3.PersonaComercio_cedulaRuc ced3, emp3.nombres nom3, emp3.apellidos ape3, emp3.cargosempleado_id ce3 ,ce3.nombreCargo especialidad3,'
                . 'CASE cli.clientetipo_idclientetipo WHEN 3 THEN p.costopromediokardex *2 WHEN 4 THEN p.costopromediokardex *2 ELSE p.costopromediokardex END as total,cli.PersonaComercio_cedulaRuc cedula_cli,cli.nombres nom_cli,cli.apellidos ape_cli';

        $json_res2 = $this->generic_model->get_join('hmc_parte_operatorio po', $where_data2, $join_clause2, $fields2, '', '', '');
        $res['data1'] = $json_res2;

        return $res;
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

        /* $join_clause[] = array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'at.cod_fact = fvd.facturaventa_codigofactventa');
          $join_clause[] = array('table' => 'billing_producto p', 'condition' => 'p.codigo = fvd.Producto_codigo');
          $join_clause[] = array('table' => 'billing_productogrupo pg', 'condition' => 'p.productogrupo_codigo = pg.codigo');
         */
        $join_clause[] = array('table' => 'billing_cliente cli', 'condition' => 'at.id_paciente = cli.id');
        $join_clause[] = array('table' => 'billing_facturaventa fv', 'condition' => 'at.cod_fact = fv.codigofactventa');
        $join_clause[] = array('table' => 'billing_empleado emp', 'condition' => 'at.id_doctor = emp.id');
        $join_clause[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields = 'emp.PersonaComercio_cedulaRuc,emp.nombres nom,emp.apellidos ape,ce.nombreCargo especialidad,SUM(CASE cli.clientetipo_idclientetipo WHEN 3 THEN fv.totalCompra *2 WHEN 4 THEN fv.totalCompra *2 ELSE fv.totalCompra END) total';

        $json_res = $this->generic_model->get_join('bill_agenda_turnos at', $where_data, $join_clause, $fields, '', '', $group_by = "emp.PersonaComercio_cedulaRuc");
        $res['data1'] = $json_res;


        //DATA 3
        $where_data3 = array();
        $join_clause3 = array();

        if (!empty($fecha_desde) && !empty($fecha_hasta)) {
            $where_data3['po.pt_ope_fecha >= '] = $fecha_desde;
            $where_data3['po.pt_ope_fecha <= '] = $fecha_hasta;
        }
        $join_clause3[] = array('table' => 'billing_cliente cli', 'condition' => 'po.pt_ope_idCliente = cli.id');
        $join_clause3[] = array('table' => 'billing_facturaventa fv', 'condition' => 'po.id_fact = fv.codigofactventa');
        $join_clause3[] = array('table' => 'hmc_personal_parteOpe pp', 'condition' => 'pp.per_ope_id = po.pt_ope_idPersonal');
        $join_clause3[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = pp.per_ope_idCirujano');
        $join_clause3[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields3 = 'emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape,ce.nombreCargo especialidad, SUM(CASE cli.clientetipo_idclientetipo WHEN 3 THEN fv.totalCompra *2 WHEN 4 THEN fv.totalCompra *2 ELSE fv.totalCompra END) total';

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
        $join_clause2[] = array('table' => 'billing_cliente cli', 'condition' => 'c.id_cliente = cli.id');
        $join_clause2[] = array('table' => 'billing_facturaventa fv', 'condition' => 'c.id_fact = fv.codigofactventa');
        $join_clause2[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = c.id_doctor AND (emp.cargosempleado_id = 82 OR emp.cargosempleado_id = 80 OR emp.cargosempleado_id = 59)');
        $join_clause2[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields2 = ' emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape, emp.cargosempleado_id, ce.nombreCargo especialidad,SUM(CASE cli.clientetipo_idclientetipo WHEN 3 THEN fv.totalCompra *2 WHEN 4 THEN fv.totalCompra *2 ELSE fv.totalCompra END) total';

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
        $join_clause4[] = array('table' => 'billing_cliente cli', 'condition' => 'sol.sol_paciente_id = cli.id');
        $join_clause4[] = array('table' => 'billing_facturaventa fv', 'condition' => 'sol.id_fact = fv.codigofactventa');
        $join_clause4[] = array('table' => 'informe inf', 'condition' => 'sol.sol_id = inf.inf_solicitudId');
        $join_clause4[] = array('table' => 'billing_empleado emp', 'condition' => 'emp.id = inf.inf_empleadoId AND (emp.cargosempleado_id = 115 )'); //cargosempleado_id para radiologia
        $join_clause4[] = array('table' => 'billing_cargosempleado ce', 'condition' => 'emp.cargosempleado_id = ce.id');
        $fields4 = ' emp.PersonaComercio_cedulaRuc, emp.nombres nom, emp.apellidos ape, emp.cargosempleado_id, ce.nombreCargo especialidad,SUM(CASE cli.clientetipo_idclientetipo WHEN 3 THEN fv.totalCompra *2 WHEN 4 THEN fv.totalCompra *2 ELSE fv.totalCompra END) total';

        $json_res4 = $this->generic_model->get_join('solicitud sol', $where_data4, $join_clause4, $fields4, '', '', $group_by = "emp.PersonaComercio_cedulaRuc");
        $res['data4'] = $json_res4;

        /* Campos para enviar a exportacion excel */
        $res['fecha_emision_desde'] = $fecha_desde;
        $res['fecha_emision_hasta'] = $fecha_hasta;

        return $res;
    }

}
