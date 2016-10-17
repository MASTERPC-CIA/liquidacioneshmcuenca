<?php

/**
 * Description of facturas_hospit
 *
 * @author MARIUXI
 */
class Facturas_hospit_hmc extends MX_Controller {

    public function load_facturas_hospit() {
        $data['bodega'] = $this->generic_model->get_data('billing_bodega', array('deleted' => 0, 'vistaweb' => 1), 'id,nombre');
        $this->load->view('search_fact_hospit_hmc', $data);
    }

    public function get_facturas_hospit() {
        $bodega_id = $this->input->post('bodega_id');
        $fecha_ini = $this->input->post('fecha_desde');
        $fecha_fin = $this->input->post('fecha_hasta');
        $send = null;
        if (!empty($fecha_ini) && !empty($fecha_fin)) {
            if ($fecha_ini <= $fecha_fin) {
                if ($bodega_id != -1) {
                    $res['nombre_bodega'] = $this->generic_model->get_val('billing_bodega', $bodega_id, 'nombre');
                    $res['lista_tipos'] = $this->get_egresos_farm_por_planilla($fecha_ini, $fecha_fin);
                    $res['fecha_desde'] = $fecha_ini;
                    $res['fecha_hasta'] = $fecha_fin;
                    $this->load->model('common/empleadocapacidad_model');
                    $res['auxiliar_cont'] = $this->empleadocapacidad_model->get('aux_contab_farmacia');
                    $this->load->view('result_fact_hospit_hmc', $res);
                } else {
                    echo info_msg('Debe seleccionar una bodega para buscar!!!');
                }
            } else {
                echo info_msg('La fecha de incio debe ser menor o igual a la fecha final de busqueda!!!');
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas para buscar!!!');
        }
    }

    public function get_ingresos_farmacia($fecha_desde, $fecha_hasta, $bodega_id) {
        $fields = 'DISTINCT(g.codigo) id, g.nombre';
        $where_data = array('fc.fechaarchivada >= ' => $fecha_desde, 'fc.fechaarchivada <= ' => $fecha_hasta, 'fc.estado' => 2,
            'fc.bodega_id' => $bodega_id);
        $join_cluase = array(
            '0' => array('table' => 'billing_detallefacturacompra fcd', 'condition' => 'fcd.FacturaCompra_codigo=fc.codigoFacturaCompra'),
            '1' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fcd.Producto_codigo'),
            '2' => array('table' => 'billing_productogrupo g', 'condition' => 'g.codigo=p.productogrupo_codigo'),
        );

        $grupos = $this->generic_model->get_join('billing_facturacompra fc', $where_data, $join_cluase, $fields);
        $list_grupos = array();
        if ($grupos) {
            $cont_grupos = 0;
            foreach ($grupos as $index1 => $grupo) {

                $productos = $this->get_productos_por_grupo($fecha_desde, $fecha_hasta, $bodega_id, $grupo->id);
                $sum_valor_prod = 0;
                $prod_iva_0 = 0;
                $sum_iva = 0;
                $prod_otro_iva = 0;
                if ($productos) {
                    foreach ($productos as $value) {
                        if ($value->ivaporcent == 0) {
                            $prod_iva_0+=$value->itemprecioxcantidadneto;
                        } else {
                            $prod_otro_iva+=$value->itemprecioxcantidadneto;
                            $sum_iva+=$value->ivavalprecioxcantidadneto;
                        }
                        $sum_valor_prod+=$value->itemxcantidadprecioiva;
                    }
                }
                $list_grupos[$cont_grupos] = (Object) array('grupo' => $grupo, 'valor_grupo' => $sum_valor_prod, 'val_iva_0' => $prod_iva_0, 'val_otro_iva' => $prod_otro_iva, 'val_iva' => $sum_iva);
                $cont_grupos++;
            }
        }
        return $list_grupos;
    }

    public function get_egresos_farm_por_planilla($fecha_desde, $fecha_hasta) {
        $tipos_cliente = $this->get_tipos_cliente_planillas($fecha_desde, $fecha_hasta);
        $res = array();
        $total_tipo = 0;
        $tot_tipo_iva_0 = 0;
        $tot_tipo_otro_iva = 0;
        $tot_tipo_iva = 0;
        $list = array();
        if ($tipos_cliente) {
            $cont_tipos = 0;

            foreach ($tipos_cliente as $index2 => $tipo_cliente) {
                $productos = $this->get_prod_por_tipo_paciente($fecha_desde, $fecha_hasta, $tipo_cliente->id_tipo_cliente);
                if ($productos) {
                    $sum_valor_prod = 0;
                    $prod_iva_0 = 0;
                    $sum_iva = 0;
                    $prod_otro_iva = 0;
                    foreach ($productos as $value) {
                        if ($value->tarporcent == 0) {
                            $prod_iva_0+=$value->itemprecioxcantidadneto;
                        } else {
                            $prod_otro_iva+=$value->itemprecioxcantidadneto;
                            $sum_iva+=$value->itemprecioxcantidadneto;
                        }
                        $sum_valor_prod+=$value->itemprecioxcantidadneto;
                    }
                    $iva = $sum_iva * (get_settings('IVA') / 100);
                    $total = $sum_valor_prod + $iva;
                }
                $list[$cont_tipos] = (Object) array('tipoC' => $tipo_cliente, 'valor_total' => $total, 'subtotal_0' => $prod_iva_0, 'subtotal_iva' => $prod_otro_iva, 'iva' => $iva);
                $cont_tipos++;
                $total_tipo+=$total;
                $tot_tipo_iva+=$iva;
                $tot_tipo_iva_0+=$prod_iva_0;
                $tot_tipo_otro_iva+=$prod_otro_iva;
            }
            $res['lista'] = $list;
            $res['total'] = $total;
            $res['total_iva_0'] = $tot_tipo_iva_0;
            $res['total_otro_iva'] = $tot_tipo_otro_iva;
            $res['total_iva'] = $tot_tipo_iva;
        }
        return $res;
    }

    public function get_prod_por_tipo_paciente($fecha_desde, $fecha_hasta, $tipo_paciente) {
        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => 3,
            'bc.clientetipo_idclientetipo' => $tipo_paciente, 'p.productogrupo_codigo >= ' => 344, 'p.productogrupo_codigo <= ' => 347, 'p.productotipo_id' => 1);
        $fields = 'pld.pdet_total itemprecioxcantidadneto, it.tarporcent';

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
            '3' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
            '4' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
        );
        $productos = $this->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $productos;
    }

    public function get_aseg_planillas($fecha_desde, $fecha_hasta, $tipo_paciente) {
        $fields = 'DISTINCT (ag.id) id_aseg, ag.ase_nombre';

        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => 2,
            'bc.clientetipo_idclientetipo' => $tipo_paciente);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'aseguradoras ag', 'condition' => 'ag.id=pl.pla_id_ase'),
        );

        $aseguradoras = $this->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $aseguradoras;
    }

    public function get_tipos_cliente_planillas($fecha_desde, $fecha_hasta) {
        $fields = 'DISTINCT (bct.idclientetipo) id_tipo_cliente, bct.tipo';
        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => 3);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'billing_clientetipo bct', 'condition' => 'bct.idclientetipo=bc.clientetipo_idclientetipo'),
        );
        $tipos_cliente = $this->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $tipos_cliente;
    }

    public function get_productos_por_grupo($fecha_desde, $fecha_hasta, $bodega_id, $id_grupo) {
        $fields = 'fcd.itemcostoxcantidadbruto, fcd.ivaporcent, fcd.itemcostoxcantidadneto, it.tarporcent, fcd.ivaval, fcd.totivaval';
        $where_data = array('fc.fechaarchivada >= ' => $fecha_desde, 'fc.fechaarchivada <= ' => $fecha_hasta, 'fc.estado' => 2,
            'fc.bodega_id' => $bodega_id, 'p.productogrupo_codigo' => $id_grupo);
        $join_cluase = array(
            '0' => array('table' => 'billing_detallefacturacompra fcd', 'condition' => 'fcd.FacturaCompra_codigo=fc.codigoFacturaCompra'),
            '1' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fcd.Producto_codigo'),
            '2' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
            '3' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
        );
        $productos = $this->generic_model->get_join('billing_facturacompra fc', $where_data, $join_cluase, $fields);
        return $productos;
    }

}
