<?php

/**
 * Description of resumen_comp_vent
 *
 * @author MARIUXI
 */
class Resumen_comp_vent extends MX_Controller {

    protected $id_descargo_mat = '56';
    protected $id_recetario_int = '55';
    protected $id_nota_venta = '02';
    protected $id_factura = '01';

    public function __construct() {
        parent::__construct();
        $this->load->library('liq_farmacia');
    }

    public function load_resumen_view() {
        $data['bodega'] = $this->generic_model->get_data('billing_bodega', array('deleted' => 0, 'vistaweb' => 1), 'id,nombre');
        $this->load->view('search_resumen_exist', $data);
    }

    public function get_compras_por_grupo_iva_cero($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'billing_facturacompra fc', 'condition' => 'fc.codigoFacturaCompra=dfc.FacturaCompra_codigo'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'dfc.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm)
        );

        $where = array('dfc.bodega_id' => $bodega_id, 'fc.fechaarchivada >=' => $desde, 'fc.fechaarchivada <=' => $hasta, 'fc.estado' => 2, 'dfc.ivaporcent' => 0);
        $fields = 'sum(dfc.itemcostoxcantidadbruto) sum_iva_cero, sum(dfc.totivaval) tot_iva_cero, sum(dfc.itemcostoiva) tot_mas_iva_cero';
        $compra = $this->generic_model->get_join('billing_detallefacturacompra dfc', $where, $join_cluase, $fields, 1, null);
        if (empty($compra->sum_iva_cero) && empty($compra->tot_iva_cero) && empty($compra->tot_mas_iva_cero)) {
            $compra->sum_iva_cero = 0;
            $compra->tot_iva_cero = 0;
            $compra->tot_mas_iva_cero = 0;
        }
        return $compra;
    }

    public function get_compras_por_grupo_otro_iva($desde, $hasta, $bodega_id, $grupo_farm) {
        $join_cluase = array(
            '0' => array('table' => 'billing_facturacompra fc', 'condition' => 'fc.codigoFacturaCompra=dfc.FacturaCompra_codigo'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'dfc.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm)
        );

        $where = array('dfc.bodega_id' => $bodega_id, 'fc.fechaarchivada >=' => $desde, 'fc.fechaarchivada <=' => $hasta, 'fc.estado' => 2, 'dfc.ivaporcent <>' => 0);
        $fields = 'sum(dfc.itemcostoxcantidadbruto) sum_otro_iva, sum(dfc.totivaval) tot_otro_iva, sum(dfc.itemcostoiva) tot_mas_otro_iva';
        $compra = $this->generic_model->get_join('billing_detallefacturacompra dfc', $where, $join_cluase, $fields, 1, null);
        if (empty($compra->sum_otro_iva) && empty($compra->tot_otro_iva) && empty($compra->tot_mas_otro_iva)) {
            $compra->sum_otro_iva = 0;
            $compra->tot_otro_iva = 0;
            $compra->tot_mas_otro_iva = 0;
        }
        return $compra;
    }

    public function get_ventas_por_grupo_iva_cero($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventa fv', 'condition' => 'fv.codigofactventa=fvd.facturaventa_codigofactventa'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'fvd.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm)
        );

        $where = array('fvd.bodega_id' => $bodega_id, 'fv.fechaarchivada >= ' => $desde, 'fv.fechaarchivada <= ' => $hasta, 'fv.estado =' => 2, 'fvd.ivaporcent' => 0);
        $fields = 'sum(fvd.itempreciobruto*fvd.itemcantidad) val_bruto_iva_cero, sum(fvd.itemprecioxcantidadneto) val_neto_iva_cero';
        $venta = $this->generic_model->get_join('billing_facturaventadetalle fvd', $where, $join_cluase, $fields, 1, null);
        if (empty($venta->val_bruto_iva_cero) && empty($venta->val_neto_iva_cero)) {
            $venta->val_bruto_iva_cero = 0;
            $venta->val_neto_iva_cero = 0;
        }
        return $venta;
    }

    public function get_ventas_por_grupo_otro_iva($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventa fv', 'condition' => 'fv.codigofactventa=fvd.facturaventa_codigofactventa'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'fvd.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm)
        );

        $where = array('fvd.bodega_id' => $bodega_id, 'fv.fechaCreacion >= ' => $desde, 'fv.fechaCreacion <= ' => $hasta, 'fv.estado =' => 2, 'fvd.ivaporcent <>' => 0);
        $fields = 'sum(fvd.itempreciobruto*fvd.itemcantidad) val_bruto_otro_iva, sum(fvd.itemprecioxcantidadneto) val_neto_otro_iva, sum(fvd.ivavalitemprecioneto) tot_val_otro_iva, sum(fvd.itemprecioiva) val_inc_otro_iva';
        $venta = $this->generic_model->get_join('billing_facturaventadetalle fvd', $where, $join_cluase, $fields, 1, null);
        if (empty($venta->val_bruto_otro_iva) && empty($venta->val_neto_otro_iva) && empty($venta->tot_val_otro_iva) && empty($venta->val_inc_otro_iva)) {
            $venta->val_bruto_otro_iva = 0;
            $venta->val_neto_otro_iva = 0;
            $venta->tot_val_otro_iva = 0;
            $venta->val_inc_otro_iva = 0;
        }
        return $venta;
    }

    public function get_ventas_dependencias($desde, $hasta, $bodega_id) {

        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventa fv', 'condition' => 'fv.codigofactventa=fvd.facturaventa_codigofactventa'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'fvd.Producto_codigo=p.codigo')
        );
        $where = array('fvd.bodega_id' => $bodega_id, 'fv.puntoventaempleado_tiposcomprobante_cod >=' => $this->id_nota_venta, 'fv.estado > ' => 0, 'fv.fechaCreacion >= ' => $desde, 'fv.fechaCreacion <= ' => $hasta);
        $fields = 'sum(fvd.itemprecioxcantidadbruto) tot_bruto, sum(fvd.itemprecioxcantidadneto) tot_neto, sum(fvd.totalpriceiva) tot_con_iva';
        $nota_venta = $this->generic_model->get_join('billing_facturaventadetalle fvd', $where, $join_cluase, $fields, 1, null);
        if (empty($nota_venta->tot_bruto) && empty($nota_venta->tot_neto) && empty($nota_venta->tot_con_iva)) {
            $nota_venta->tot_bruto = 0;
            $nota_venta->tot_neto = 0;
            $nota_venta->tot_con_iva = 0;
        }
        return $nota_venta;
    }

    public function get_resumen_grupo($bodega_id, $fecha, $grupo_id) {
        $where = array('sb.bodega_id' => $bodega_id);
        $join_cluase = array(
            '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo and p.productogrupo_codigo=' . $grupo_id),
            '1' => array('table' => 'billing_productogrupo g', 'condition' => 'g.codigo=p.productogrupo_codigo')
        );
        $productos = $this->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(p.codigo) codigo');
        $total = 0;
        if ($fecha < '2016-09-06') { /* Se toma como referencia esta fecha porque es la fecha en la que se migro el inventario de farmacia */
            $fecha = '2016-09-06';
        }
        if ($productos) {
            foreach ($productos as $key => $producto) {
                $where = array('kb.fecha <=' => $fecha, 'kb.bodega_id' => $bodega_id, 'kb.producto_id' => $producto->codigo, 'kb.estado > ' => 0);
                $fields = '(kb.costo_prom * kb.kardex_total) subtotal';
                $prod = $this->generic_model->get('bill_kardex kb', $where, $fields, array('kb.id' => 'DESC'), $rows_num = 1);
                if ($prod) {
                    $total += $prod->subtotal;
                }
            }
        }
        return $total;
    }

    public function get_resumen_existencias() {
        $bodega_id = $this->input->post('bodega_id');
        $fecha_ini = $this->input->post('f_desde');
        $fecha_fin = $this->input->post('f_hasta');
        $send = null;
        if (!empty($fecha_ini) and ! empty($fecha_fin)) {
            if ($fecha_ini <= $fecha_fin) {
                if ($bodega_id != -1) {
                    $send['nombre_bodega'] = $this->generic_model->get_val('billing_bodega', $bodega_id, 'nombre');

                    $where = array('sb.bodega_id' => $bodega_id);
                    $join_cluase = array(
                        '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo'),
                        '1' => array('table' => 'billing_productogrupo g', 'condition' => 'g.codigo=p.productogrupo_codigo')
                    );
                    $grupos_farm = $this->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(g.codigo) id, g.nombre');

                    $list = null;
                    $cont = 0;

                    $sum_ext_anterior = 0;
                    $sum_ext_actual = 0;
                    $sum_comp_sin_iva = 0;
                    $sum_comp_con_iva = 0;
                    $sum_vent_con_iva = 0;
                    $sum_vent_sin_iva = 0;
                    $sum_vent_utilidad = 0;
                    $sum_ent_iva_cero=0;
                    $sum_ent_otro_iva=0;
                    $sum_sal_iva_cero=0;
                    $sum_sal_otro_iva=0;
                    

                    foreach ($grupos_farm as $index => $gp) {

                        $gp->tot_exist_anterior = $this->get_resumen_grupo($bodega_id, $fecha_ini, $gp->id);
                        $gp->tot_exist_actual = $this->get_resumen_grupo($bodega_id, $fecha_fin, $gp->id);

                        $compras_iva_cero = $this->get_compras_por_grupo_iva_cero($fecha_ini, $fecha_fin, $bodega_id, $gp->id);
                        $compras_otro_iva = $this->get_compras_por_grupo_otro_iva($fecha_ini, $fecha_fin, $bodega_id, $gp->id);

                        $gp->tot_comp_sin_iva = $compras_iva_cero->sum_iva_cero;
                        $gp->tot_comp_con_iva = $compras_otro_iva->tot_mas_otro_iva;

                        $ajs_entrada_iva_cero = $this->get_aj_ent_por_grupo_iva_cero($fecha_ini, $fecha_fin, $bodega_id, $gp->id);
                        $ajs_entrada_otro_iva = $this->get_aj_ent_por_grupo_otro_iva($fecha_ini, $fecha_fin, $bodega_id, $gp->id);

                        $gp->tot_aj_ent_iva_cero = $ajs_entrada_iva_cero->sum_iva_cero;
                        $gp->tot_aj_ent_otro_iva = $ajs_entrada_otro_iva->sum_otro_iva;

                        $ventas_iva_cero = $this->get_ventas_por_grupo_iva_cero($fecha_ini, $fecha_fin, $bodega_id, $gp->id);
                        $ventas_otro_iva = $this->get_ventas_por_grupo_otro_iva($fecha_ini, $fecha_fin, $bodega_id, $gp->id);

                        $gp->tot_vent_sin_iva = $ventas_iva_cero->val_bruto_iva_cero;
                        $gp->tot_vent_con_iva = $ventas_otro_iva->val_bruto_otro_iva;

                        $ajs_salida_iva_cero = $this->get_aj_sal_por_grupo_iva_cero($fecha_ini, $fecha_fin, $bodega_id, $gp->id);
                        $ajs_salida_otro_iva = $this->get_aj_sal_por_grupo_otro_iva($fecha_ini, $fecha_fin, $bodega_id, $gp->id);

                        $gp->tot_aj_sal_iva_cero = $ajs_salida_iva_cero->sum_iva_cero;
                        $gp->tot_aj_sal_otro_iva = $ajs_salida_otro_iva->sum_otro_iva;

                        $util_ventas_iva_cero = $ventas_iva_cero->val_neto_iva_cero - $ventas_iva_cero->val_bruto_iva_cero;
                        $util_ventas_otro_iva = $ventas_otro_iva->val_neto_otro_iva - $ventas_otro_iva->val_bruto_otro_iva;

                        $gp->tot_vent_utilidad = $util_ventas_iva_cero + $util_ventas_otro_iva;

                        $sum_ext_anterior+=$gp->tot_exist_anterior;
                        $sum_ext_actual+=$gp->tot_exist_actual;
                        $sum_comp_sin_iva+=$gp->tot_comp_sin_iva;
                        $sum_comp_con_iva+=$gp->tot_comp_con_iva;
                        $sum_vent_sin_iva+=$gp->tot_vent_sin_iva;
                        $sum_vent_con_iva+=$gp->tot_vent_con_iva;
                        $sum_vent_utilidad+=$gp->tot_vent_utilidad;
                        $sum_ent_iva_cero+=$gp->tot_aj_ent_iva_cero;
                        $sum_ent_otro_iva+=$gp->tot_aj_ent_otro_iva;
                        $sum_sal_iva_cero += $gp->tot_aj_sal_iva_cero;
                        $sum_sal_otro_iva += $gp->tot_aj_sal_otro_iva;


                        $list[$cont] = (Object) array('grupo' => $gp);
                        $cont++;
                    }

                    $data_dep = $this->get_ventas_dependencias($fecha_ini, $fecha_fin, $bodega_id);

                    $send['list'] = $list;
                    $send['sum_ext_anterior'] = $sum_ext_anterior;
                    $send['sum_ext_actual'] = $sum_ext_actual;
                    $send['sum_comp_sin_iva'] = $sum_comp_sin_iva;
                    $send['sum_comp_con_iva'] = $sum_comp_con_iva;
                    $send['sum_vent_con_iva'] = $sum_vent_con_iva;
                    $send['sum_vent_sin_iva'] = $sum_vent_sin_iva;
                    $send['sum_vent_utilidad'] = $sum_vent_utilidad;
                    $send['sum_dep_sin_iva'] = $data_dep->tot_bruto;
                    $send['sum_dep_con_iva'] = $data_dep->tot_con_iva;
                    $send['sum_dep_utilidad'] = $data_dep->tot_neto;
                    $send['sum_ent_sin_iva'] = $sum_ent_iva_cero; 
                    $send['sum_ent_con_iva'] =  $sum_ent_otro_iva;
                    $send['sum_sal_sin_iva'] =  $sum_sal_iva_cero;
                    $send['sum_sal_con_iva'] =  $sum_sal_otro_iva;
                    $send['desde'] = $fecha_ini;
                    $send['hasta'] = $fecha_fin;
                    $this->load->model('common/empleadocapacidad_model');
                    $send['auxiliar_cont'] = $this->empleadocapacidad_model->get('aux_contab_farmacia');
                    $this->load->view('resumen_existencias_view', $send);
                } else {
                    echo info_msg('Debe seleccionar una bodega para buscar!!!');
                }
            } else {
                echo info_msg('La fecha de inicio debe ser menor o igual a la fecha final de busqueda!!!');
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas para buscar!!!');
        }
    }

    public function get_aj_ent_por_grupo_iva_cero($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'bill_ajustentrada ae', 'condition' => 'ae.id=aed.ajustentrada_id'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'aed.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm),
            '2' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'p.codigo=pit.producto_id')
        );

        $where = array('aed.bodega_id' => $bodega_id, 'ae.fecha >=' => $desde, 'ae.fecha <=' => $hasta, 'ae.tipo <>'=>4,'pit.impuestotarifa_id' => 1); //1 corresponde a tarifa 0

        $fields = 'sum(aed.itemcostoxcantidad) sum_iva_cero';

        $ajuste_ent = $this->generic_model->get_join('bill_ajustentradadet aed', $where, $join_cluase, $fields, 1, null);

        if (empty($ajuste_ent->sum_iva_cero)) {
            $ajuste_ent->sum_iva_cero = 0;
        }
        return $ajuste_ent;
    }
    
    public function get_aj_ent_por_grupo_otro_iva($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'bill_ajustentrada ae', 'condition' => 'ae.id=aed.ajustentrada_id'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'aed.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm),
            '2' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'p.codigo=pit.producto_id')
        );

        $where = array('aed.bodega_id' => $bodega_id, 'ae.fecha >=' => $desde, 'ae.fecha <=' => $hasta, 'ae.tipo <>'=>4,'pit.impuestotarifa_id' => 2); //1 corresponde a tarifa 0

        $fields = 'sum(aed.itemcostoxcantidad) sum_otro_iva';

        $ajuste_ent = $this->generic_model->get_join('bill_ajustentradadet aed', $where, $join_cluase, $fields, 1, null);

        if (empty($ajuste_ent->sum_otro_iva)) {
            $ajuste_ent->sum_otro_iva = 0;
        }
        return $ajuste_ent;
    }
    
    public function get_aj_sal_por_grupo_iva_cero($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'bill_ajustesalida ajs', 'condition' => 'ajs.id=ajsd.ajustesalida_id'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'ajsd.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm),
            '2' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'p.codigo=pit.producto_id')
        );

        $where = array('ajsd.bodega_id' => $bodega_id, 'ajs.fecha >=' => $desde, 'ajs.fecha <=' => $hasta, 'pit.impuestotarifa_id' => 1); //1 corresponde a tarifa 0

        $fields = 'sum(ajsd.itemcostoxcantidad) sum_iva_cero';

        $ajuste_sal = $this->generic_model->get_join('bill_ajustesalidadet ajsd', $where, $join_cluase, $fields, 1, null);

        if (empty($ajuste_sal->sum_iva_cero)) {
            $ajuste_sal->sum_iva_cero = 0;
        }
        return $ajuste_sal;
    }
    
    public function get_aj_sal_por_grupo_otro_iva($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'bill_ajustesalida ajs', 'condition' => 'ajs.id=ajsd.ajustesalida_id'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'ajsd.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm),
            '2' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'p.codigo=pit.producto_id')
        );

        $where = array('ajsd.bodega_id' => $bodega_id, 'ajs.fecha >=' => $desde, 'ajs.fecha <=' => $hasta, 'pit.impuestotarifa_id' => 2); //2 corresponde a otro iva 14 o 12

        $fields = 'sum(ajsd.itemcostoxcantidad) sum_otro_iva';

        $ajuste_ent = $this->generic_model->get_join('bill_ajustesalidadet ajsd', $where, $join_cluase, $fields, 1, null);

        if (empty($ajuste_ent->sum_otro_iva)) {
            $ajuste_ent->sum_otro_iva = 0;
        }
        return $ajuste_ent;
    }

}
