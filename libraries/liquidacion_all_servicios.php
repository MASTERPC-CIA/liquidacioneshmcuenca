<?php

/**
 * Description of liquidacion_all_servicios
 *
 * @author MARIUXI
 */
class Liquidacion_all_servicios {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
    }

    public function get_grupos_producto($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante) {
        $fields = 'DISTINCT(g.codigo) id, g.nombre';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante);
        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '1' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '2' => array('table' => 'billing_productogrupo g', 'condition' => 'g.codigo=p.productogrupo_codigo'),
        );

        $grupos = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $grupos;
    }

    public function get_marcas_producto_por_grupo($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $id_grupo) {
        $fields = 'DISTINCT(m.id) id, m.nombre';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante, 'p.productogrupo_codigo' => $id_grupo);
        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '1' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '2' => array('table' => 'billing_marca m', 'condition' => 'm.id=p.marca_id'),
        );

        $marcas = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $marcas;
    }

    public function get_productos_por_marca($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $id_marca) {
        $fields = 'fvd.itemprecioxcantidadneto, fvd.ivaporcent, fvd.ivavalitemprecioneto, it.tarporcent, itemxcantidadprecioiva, ivavalprecioxcantidadneto';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante, 'p.marca_id' => $id_marca);
        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '1' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '2'=>array('table'=>'bill_productoimpuestotarifa pit','condition'=>'pit.producto_id = p.codigo'),
            '3'=>array('table'=>'bill_impuestotarifa it','condition'=>'it.id = pit.impuestotarifa_id')
        );
        $productos = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $productos;
    }

    public function get_aseguradoras_por_factura($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante) {
        $fields = 'DISTINCT(ag.id) id, ag.ase_nombre';

        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
            '1' => array('table' => 'aseguradoras ag', 'condition' => 'ag.id=bc.aseguradora_id'),
        );

        $aseguradoras = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $aseguradoras;
    }

    public function get_grupos_por_aseguradora($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $id_aseg) {
        $fields = 'DISTINCT(g.codigo) id, g.nombre';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante, 'ag.id' => $id_aseg);
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
            '1' => array('table' => 'aseguradoras ag', 'condition' => 'ag.id=bc.aseguradora_id'),
            '2' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '3' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '4' => array('table' => 'billing_productogrupo g', 'condition' => 'g.codigo=p.productogrupo_codigo'),
        );

        $grupos = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $grupos;
    }

    public function get_valores_liquid_por_servicio($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante) {
        
        $grupos = $this->get_grupos_producto($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante);

        $total_grupos = 0;
        $tot_grupos_iva_0 = 0;
        $tot_grupos_otro_iva = 0;
        $tot_grupos_iva = 0;
        $list_grupos = array();

        if ($grupos) {
            $cont_grupos = 0;
            foreach ($grupos as $index1 => $grupo) {
                $total_marcas = 0;
                $tot_marcas_iva_0 = 0;
                $tot_marcas_otro_iva = 0;
                $tot_marcas_iva = 0;
                $list_marcas = array();
                $marcas = $this->get_marcas_producto_por_grupo($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $grupo->id);
                if ($marcas) {
                    $cont_marcas = 0;
                    foreach ($marcas as $index2 => $marca) {
                        $productos = $this->get_productos_por_marca($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $marca->id);
                        $sum_valor_prod = 0;
                        $prod_iva_0=0; 
                        $sum_iva=0;
                        $prod_otro_iva=0;
                        if ($productos) {
                            foreach ($productos as $value) {
                                if($value->ivaporcent ==0){
                                    $prod_iva_0+=$value->itemprecioxcantidadneto;
                                }else{
                                    $prod_otro_iva+=$value->itemprecioxcantidadneto;
                                    $sum_iva+=$value->ivavalprecioxcantidadneto;
                                }
                                $sum_valor_prod+=$value->itemxcantidadprecioiva;
                            }
                            $list_marcas[$cont_marcas] = (Object) array('marca' => $marca, 'valor_total' => $sum_valor_prod, 'subtotal_0'=>$prod_iva_0, 'subtotal_iva'=>$prod_otro_iva, 'iva'=>$sum_iva);
                            $cont_marcas++;
                            
                            $total_marcas+=$sum_valor_prod;
                            $tot_marcas_iva_0+=$prod_iva_0;
                            $tot_marcas_otro_iva=$prod_otro_iva;
                            $tot_marcas_iva+=$sum_iva;
                        }
                    }
                    $list_grupos[$cont_grupos] = (Object) array('grupo' => $grupo, 'lista_marcas'=>$list_marcas,'valor_grupo' => $total_marcas, 'val_iva_0'=>$tot_marcas_iva_0, 'val_otro_iva'=>$tot_marcas_otro_iva, 'val_iva'=>$sum_iva);
                    $cont_grupos++;
                    $total_grupos+=$total_marcas;
                    $tot_grupos_iva_0+=$tot_marcas_iva_0;
                    $tot_grupos_otro_iva+=$tot_marcas_otro_iva;
                    $tot_grupos_iva+=$tot_marcas_iva;
                }
            }
        } 
        $send['list'] = $list_grupos;
        $send['total_servicio'] = $total_grupos;
        $send['tot_serv_iva_0'] = $tot_grupos_iva_0;
        $send['tot_serv_otro_iva'] = $tot_grupos_otro_iva;
        $send['tot_serv_iva'] = $tot_grupos_iva;
        $send['nombre_servicio'] = $this->get_name_servicio($tipo_servicio);
        return $send;
    }

    public function get_valores_liquid_por_aseguradora($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante) {

        $aseguradoras = $this->get_aseguradoras_por_factura($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante);

        $list_aseg = array();
        $total_aseg = 0;

        if ($aseguradoras) {
            $cont_aseg = 0;
            foreach ($aseguradoras as $key => $aseg) {
                $grupos = $this->get_grupos_por_aseguradora($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $aseg->id);

                $total_grupos = 0;
                $list_grupos = array();

                if ($grupos) {
                    $cont_grupos = 0;
                    foreach ($grupos as $index1 => $grupo) {
                        $total_marcas = 0;
                        $list_marcas = array();
                        $marcas = $this->get_marcas_por_grupo_y_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $grupo->id, $aseg->id);
                        if ($marcas) {
                            $cont_marcas = 0;
                            foreach ($marcas as $index2 => $marca) {
                                $productos = $this->get_prod_por_marca_y_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $marca->id, $grupo->id, $aseg->id);
                                $sum_valor_prod = 0;
                                if ($productos) {
                                    foreach ($productos as $value) {
                                        $sum_valor_prod+=$value->itemxcantidadprecioiva;
                                    }
                                    $list_marcas[$cont_marcas] = (Object) array('marca' => $marca, 'valor_total' => $sum_valor_prod);
                                    $cont_marcas++;
                                    $total_marcas+=$sum_valor_prod;
                                }
                            }
                            $list_grupos[$cont_grupos] = (Object) array('grupo' => $grupo, 'lista_marcas'=>$list_marcas, 'valor_grupo' => $total_marcas);
                            $cont_grupos++;
                            $total_grupos+=$total_marcas;
                        }
                    }
                } 
                $list_aseg[$cont_aseg] = (Object) array('aseg' => $aseg, 'lista_grupos'=>$list_grupos, 'valor_aseg' => $total_grupos);
                $cont_aseg++;
                $total_aseg+=$total_grupos;
            }
        } 
    
        $send['list_aseg'] = $list_aseg;
        $send['total_serv_credito'] = $total_aseg;
        $send['nombre_serv_credito'] = $this->get_name_servicio($tipo_servicio);
        return $send;
    }

    //Para obtener el nombre del servicio del cual se esta extrayendo los valores para la liquidación
    public function get_name_servicio($tipo_servicio) {
        return $this->ci->generic_model->get_val_where('bill_sttiposervicio', array('id' => $tipo_servicio), 'tipo');
    }
    
    //Para obtener clientes por facturas 
    
    public function get_clientes_por_facturas($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $tipo_paciente){
        $fields = 'fv.codigofactventa, CONCAT_WS(" ",nombres," ",apellidos) '
                . 'nombres, fv.totalCompra';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante, 'bc.clientetipo_idclientetipo'=>$tipo_paciente);
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
     
        );
   
        $clientes = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $clientes;
        
    }
    //para obtener los totales de las facturas de las planillas
     public function get_totales_facturas_pago($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $tipo_paciente){
        
        $clientes= $this->get_clientes_por_facturas($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $tipo_paciente);
        $valor_total_servicio = 0;
        
        if($clientes){
            foreach ($clientes as $value) {
                $valor_total_servicio+=$value->totalCompra;
            }
        }
        
        $res['clientes']=$clientes;
        $res['total_servicio'] = $valor_total_servicio;
        $res['nombre_servicio'] = $this->get_name_servicio($tipo_servicio);
        
        return $res;   
    }
     public function get_marcas_por_grupo_y_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $id_grupo, $id_aseg) {
        $fields = 'DISTINCT(m.id) id, m.nombre';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante, 'p.productogrupo_codigo' => $id_grupo, 'bc.aseguradora_id' => $id_aseg);
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
            '1' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '3' => array('table' => 'billing_marca m', 'condition' => 'm.id=p.marca_id'),
            
        );

        $marcas = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $marcas;
       
    }
     public function get_prod_por_marca_y_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $id_marca, $id_grupo, $id_aseg) {
        $fields = 'fvd.itemprecioxcantidadneto, fvd.ivaporcent, fvd.ivavalitemprecioneto, it.tarporcent, itemxcantidadprecioiva, ivavalprecioxcantidadneto';
        $where_data = array('fv.tipo_pago' => $tipo_pago, 'fv.servicio_hmc' => $tipo_servicio,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $tipo_comprobante, 'p.marca_id' => $id_marca, 'p.productogrupo_codigo'=>$id_grupo,'bc.aseguradora_id' => $id_aseg);
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
            '1' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '3' => array('table' => 'bill_productoimpuestotarifa pit','condition'=>'pit.producto_id = p.codigo'),
            '4' => array('table' => 'bill_impuestotarifa it','condition'=>'it.id = pit.impuestotarifa_id')
        );
        $productos = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $productos;
    }
}
