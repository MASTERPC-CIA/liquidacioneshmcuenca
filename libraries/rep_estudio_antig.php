<?php

/**
 * Description of rep_estudio_antig
 *
 * @author MARIUXI
 */
class rep_estudio_antig {

    private $ci;
    private $pago_credito = 2;
    private $comp_serv_cxc = 59;
    private $consulta_ext = 1;
    private $grupo_odontologia = 231;

    public function __construct() {
        $this->ci = & get_instance();
    }

    public function get_estudio_antig_x_cliente($fecha_desde, $fecha_hasta, $estado, $tipo_paciente, $id_aseg) {

        $clientes_plan = $this->get_val_planillas_por_cliente($fecha_desde, $fecha_hasta, $estado, $tipo_paciente, $id_aseg);
        $clientes_comp = $this->get_val_comprob_serv_por_cliente($fecha_desde, $fecha_hasta, $id_aseg);
        $val_aseg_cred = 0;
        $send['lista_clientes'] = array();
        if ($clientes_plan) {
            foreach ($clientes_plan as $value) {
                $val_aseg_cred+=$value->pla_valor_aseguradora;
            }
            array_push($send['lista_clientes'], $clientes_plan);
        }
        if ($clientes_comp) {
            foreach ($clientes_comp as $value) {
                $val_aseg_cred+=$value->totalCompra;
            }
            array_push($send['lista_clientes'], $clientes_comp);
        }

        $send['total_aseg'] = $val_aseg_cred;
        return $send;
    }

    public function get_val_planillas_por_cliente($fecha_desde, $fecha_hasta, $estado, $tipo_paciente, $id_aseg) {
        $fields = 'pl.id, pl.pla_aseguradora pla_valor_aseguradora, pl.pla_paciente pla_valor_paciente,pl.pla_total pla_valor_total, CONCAT_WS(" ",bc.nombres," ",bc.apellidos) nombres, bc.PersonaComercio_cedulaRuc ci';

        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'pl.pla_id_ase' => $id_aseg);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
        );

        $clientes = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $clientes;
    }

    public function get_val_comprob_serv_por_cliente($fecha_desde, $fecha_hasta, $id_aseg) {

        $fields = 'fv.codigofactventa, CONCAT_WS(" ",nombres," ",apellidos) nombres, PersonaComercio_cedulaRuc ci,fv.totalCompra';
        $where_data = array('fv.tipo_pago' => $this->pago_credito, 'fv.servicio_hmc' => $this->consulta_ext,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $this->comp_serv_cxc, 'bc.aseguradora_id' => $id_aseg);
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
        );

        $clientes = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $clientes;
    }

    public function get_estudio_antig_x_servicio($fecha_desde, $fecha_hasta, $estado, $tipo_paciente, $id_aseg) {

        $send['serv_cons_ext'] = $this->get_val_comprob_por_serv_consulta_ext($fecha_desde, $fecha_hasta, $id_aseg, 0); // Se le envia 0 ya que se requiere un reporte solo de consulta externa
        $send['serv_odont'] = $this->get_val_comprob_por_serv_consulta_ext($fecha_desde, $fecha_hasta, $id_aseg, 1); // Se le envia 1 ya que se requiere un reporte solo de odontologia
        $send['serv_hospit'] = $this->get_val_planillas_por_serv($fecha_desde, $fecha_hasta, $estado, $tipo_paciente, $id_aseg, 2); //para reporte de hospitalizacion
        $send['serv_emerg'] = $this->get_val_planillas_por_serv($fecha_desde, $fecha_hasta, $estado, $tipo_paciente, $id_aseg, 3); //para reporte de emergencia

        return $send;
    }

    public function get_val_planillas_por_serv($fecha_desde, $fecha_hasta, $estado, $tipo_paciente, $id_aseg, $id_serv) {

        $fields = 'sum(pl.pla_aseguradora) tot_val_aseg';

        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'pl.pla_id_ase' => $id_aseg, 'pl.pla_tipo' => $id_serv);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
        );

        $tot_aseg_plan = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $tot_aseg_plan;
    }

    //Permite obtener todos los datos de las facturas por el servicio de consulta externa y odontologia

    public function get_val_comprob_por_serv_consulta_ext($fecha_desde, $fecha_hasta, $id_aseg, $es_odont) {

        $fields = 'sum(fvd.itemxcantidadprecioiva) tot_comp_serv';
        if ($es_odont == 1) {
            $where_data = array('fv.tipo_pago' => $this->pago_credito, 'fv.servicio_hmc' => $this->consulta_ext,
                'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
                'fv.puntoventaempleado_tiposcomprobante_cod' => $this->comp_serv_cxc, 'bc.aseguradora_id' => $id_aseg, 'bp.productogrupo_codigo' => $this->grupo_odontologia);
        } else {
            $where_data = array('fv.tipo_pago' => $this->pago_credito, 'fv.servicio_hmc' => $this->consulta_ext,
                'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
                'fv.puntoventaempleado_tiposcomprobante_cod' => $this->comp_serv_cxc, 'bc.aseguradora_id' => $id_aseg, 'bp.productogrupo_codigo !=' => $this->grupo_odontologia);
        }


        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
            '1' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '2' => array('table' => 'billing_producto bp', 'condition' => 'bp.codigo = fvd.Producto_codigo')
        );

        $tot_aseg_comp = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $tot_aseg_comp;
    }

}
