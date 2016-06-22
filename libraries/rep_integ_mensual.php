<?php

/**
 * Description of reporte_integrado_mensual
 *
 * @author MARIUXI
 */
class rep_integ_mensual {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->library('liquidacion_all_servicios');
        $this->ci->load->library('liquidacion_planillas');
    }

    public function get_tot_grupo_servicio_factura($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_comprobante, $tipo_pago, $lista_grupos, $id_aseg=-1) {

        $list_tot_x_grupo = array();
        $sum_total=0;
        if ($lista_grupos) {
            $cont_gp = 0;
            foreach ($lista_grupos as $grupo) {
                $productos = $this->get_prod_x_grupo_factura($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $grupo->id);
                $sum_valor_prod = 0;
                if ($productos) {
                    foreach ($productos as $value) {
                        $sum_valor_prod+=$value->itemxcantidadprecioiva;
                    }
                }
                if($tipo_pago==1){
                    $val_efectivo=$sum_valor_prod;
                    $val_credito = 0;
                }else{
                    $val_efectivo=0;
                    $val_credito = $sum_valor_prod;
                }
                $list_tot_x_grupo[$cont_gp] = (Object) array('grupo_id' => $grupo->id, 'val_tot_grupo' => $sum_valor_prod, 'id_aseguradora'=>$id_aseg, 'id_servicio'=>$tipo_servicio, 'efectivo'=>$val_efectivo, 'credito'=>$val_credito);
                $cont_gp++;
                $sum_total+=$sum_valor_prod;
            }
        }
        $send['list_tot_x_grupo']=$list_tot_x_grupo;
        $send['total']=$sum_total;
        return $send;
    }

    public function get_prod_x_grupo_factura($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $id_grupo) {
        $fields = 'fvd.itemxcantidadprecioiva';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante, 'p.productogrupo_codigo' => $id_grupo);
        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '1' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
        );
        $productos = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $productos;
    }

    public function get_tot_grupo_servicio_planilla($fecha_desde, $fecha_hasta, $tipo_paciente, $estado_pla, $lista_grupos, $id_aseg, $tipo_servicio) {

        $list_tot_x_grupo = array();
        $sum_total=0;
        if ($lista_grupos) {
            $cont_gp = 0;
            foreach ($lista_grupos as $grupo) {
                $productos = $this->get_prod_x_grupo_planilla($fecha_desde, $fecha_hasta, $tipo_paciente, $estado_pla, $grupo->id, $id_aseg, $tipo_servicio);
                $sum_valor_prod = 0;
                if ($productos) {
                    foreach ($productos as $value) {
                        $sum_valor_prod+=$value->itemxcantidadprecioiva;
                    }
                }

                $val_efectivo=0;
                $val_credito = $sum_valor_prod;

                $list_tot_x_grupo[$cont_gp] = (Object) array('grupo_id' => $grupo->id, 'val_tot_grupo' => $sum_valor_prod, 'id_aseguradora'=>$id_aseg, 'id_servicio'=>$tipo_servicio, 'efectivo'=>$val_efectivo, 'credito'=>$val_credito);
                $cont_gp++;
                $sum_total+=$sum_valor_prod;
            }
        }
        $send['list_tot_x_grupo']=$list_tot_x_grupo;
        $send['total']=$sum_total;
        return $send;
    }

    public function get_prod_x_grupo_planilla($fecha_desde, $fecha_hasta, $tipo_paciente, $estado, $id_grupo, $id_aseg, $tipo_planilla) {
        $fields = 'pld.pdet_total itemxcantidadprecioiva';

        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'p.productogrupo_codigo' => $id_grupo, 'pl.pla_id_ase' => $id_aseg, 'pl.pla_tipo'=>$tipo_planilla);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
        );
        $productos = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $productos;
    }
    
    public function get_tot_grupo_servicio_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_comprobante, $tipo_pago, $lista_grupos, $id_aseg) {

        $list_tot_x_grupo = array();
        $sum_total=0;
        if ($lista_grupos) {
            $cont_gp = 0;
            foreach ($lista_grupos as $grupo) {
                $productos = $this->get_prod_x_grupo_factura($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $grupo->id, $id_aseg);
                $sum_valor_prod = 0;
                if ($productos) {
                    foreach ($productos as $value) {
                        $sum_valor_prod+=$value->itemxcantidadprecioiva;
                    }
                }

                if($tipo_pago==1){
                    $val_efectivo=$sum_valor_prod;
                    $val_credito = 0;
                }else{
                    $val_efectivo=0;
                    $val_credito = $sum_valor_prod;
                }
                $list_tot_x_grupo[$cont_gp] = (Object) array('grupo_id' => $grupo->id, 'val_tot_grupo' => $sum_valor_prod, 'id_aseguradora'=>$id_aseg, 'id_servicio'=>$tipo_servicio, 'efectivo'=>$val_efectivo, 'credito'=>$val_credito);
                $cont_gp++;
                $sum_total+=$sum_valor_prod;
            }
        }
        $send['list_tot_x_grupo']=$list_tot_x_grupo;
        $send['total']=$sum_total;
        return $send;
    }

    public function get_prod_x_grupo_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $id_grupo, $id_aseg) {
        $fields = 'fvd.itemxcantidadprecioiva';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante, 'p.productogrupo_codigo' => $id_grupo, 'bc.aseguradora_id'=>$id_aseg);
        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '1' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '2' => array('table' => 'billing_cliente bc', 'condition' =>'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc')
        );
        $productos = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $productos;
    }


}
