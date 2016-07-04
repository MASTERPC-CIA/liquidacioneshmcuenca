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
    }

    public function load_resumen_view() {
        $data['bodega'] = $this->generic_model->get_data('billing_bodega', array('deleted' => 0), 'id,nombre');
        $this->load->view('search_resumen_exist', $data);
    }

    public function get_compras_por_grupo($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'billing_facturacompra fc', 'condition' => 'fc.codigoFacturaCompra=dfc.FacturaCompra_codigo'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'dfc.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm)
        );

        $where = array('dfc.bodega_id' => $bodega_id, 'fc.fechaCreacion >=' => $desde, 'fc.fechaCreacion <=' => $hasta, 'fc.estado > ' => 0);
        $fields = 'sum(dfc.itemcostoxcantidadbruto) tot_sin_iva, sum(dfc.totivaval) tot_iva, sum(dfc.itemcostoiva) tot_con_iva';
        $compra = $this->generic_model->get_join('billing_detallefacturacompra dfc', $where, $join_cluase, $fields, 1, null);
        if (empty($compra->tot_bruto) && empty($compra->tot_neto) && empty($compra->tot_con_iva)) {
            $compra->tot_sin_iva = 0;
            $compra->tot_iva = 0;
            $compra->tot_con_iva = 0;
        }
        return $compra;
    }

    public function get_ventas_por_grupo($desde, $hasta, $bodega_id, $grupo_farm) {

        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventa fv', 'condition' => 'fv.codigofactventa=fvd.facturaventa_codigofactventa'),
            '1' => array('table' => 'billing_producto p ', 'condition' => 'fvd.Producto_codigo=p.codigo and p.productogrupo_codigo = ' . $grupo_farm)
        );
//        $where = array('fvd.bodega_id' => $bodega_id, 'fv.puntoventaempleado_tiposcomprobante_cod >=' => $this->id_recetario_int, 'fv.puntoventaempleado_tiposcomprobante_cod <=' => $this->id_descargo_mat, 'fv.estado >' => 0, 'fv.fechaCreacion >= ' => $desde, 'fv.fechaCreacion <= ' => $hasta);
        $where = array('fvd.bodega_id' => $bodega_id, 'fv.fechaCreacion >= ' => $desde, 'fv.fechaCreacion <= ' => $hasta, 'fv.estado =' => 2);
        $fields = 'sum(fvd.itemprecioxcantidadbruto) tot_bruto, sum(fvd.itemprecioxcantidadneto) tot_neto, sum(fvd.totalpriceiva) tot_con_iva';
        $venta = $this->generic_model->get_join('billing_facturaventadetalle fvd', $where, $join_cluase, $fields, 1, null);
        if (empty($venta->tot_bruto) && empty($venta->tot_neto) && empty($venta->tot_con_iva)) {
            $venta->tot_bruto = 0;
            $venta->tot_neto = 0;
            $venta->tot_con_iva = 0;
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

        if (count($productos)) {
            foreach ($productos as $key => $producto) {
                $where = array('kb.fecha <=' => $fecha, 'kb.bodega_id' => $bodega_id, 'kb.producto_id' => $producto->codigo);

                $fields = '(kb.costo_prom * kb.kardex_total) subtotal';
                $prod = $this->generic_model->get('bill_kardex kb', $where, $fields, array('kb.id' => 'DESC'), $rows_num = 1);
                if (count($prod)) {
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

                    foreach ($grupos_farm as $index => $gp) {
                        $gp->tot_exist_anterior = $this->get_resumen_grupo($bodega_id, $fecha_ini, $gp->id);
                        $gp->tot_exist_actual = $this->get_resumen_grupo($bodega_id, $fecha_fin, $gp->id);
                        $data_compras = $this->get_compras_por_grupo($fecha_ini, $fecha_fin, $bodega_id, $gp->id);
                        $data_ventas = $this->get_ventas_por_grupo($fecha_ini, $fecha_fin, $bodega_id, $gp->id);
                        $gp->tot_comp_sin_iva = $data_compras->tot_sin_iva;
//                    $gp->tot_comp_iva = $data_compras->tot_iva;
                        $gp->tot_comp_con_iva = $data_compras->tot_con_iva;
                        $gp->tot_vent_sin_iva = $data_ventas->tot_bruto;
                        $gp->tot_vent_con_iva = $data_ventas->tot_con_iva;
                        $gp->tot_vent_utilidad = $data_ventas->tot_neto;

                        $sum_ext_anterior+=$gp->tot_exist_anterior;
                        $sum_ext_actual+=$gp->tot_exist_actual;
                        $sum_comp_sin_iva+=$gp->tot_comp_sin_iva;
                        $sum_comp_con_iva+=$gp->tot_comp_con_iva;
                        $sum_vent_sin_iva+=$gp->tot_vent_sin_iva;
                        $sum_vent_con_iva+=$gp->tot_vent_con_iva;
                        $sum_vent_utilidad+=$gp->tot_vent_utilidad;


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
                    $send['desde'] = $fecha_ini;
                    $send['hasta'] = $fecha_fin;
                    $this->load->model('common/empleadocapacidad_model');
                    $send['auxiliar_cont'] = $this->empleadocapacidad_model->get('aux_contabilidad');
                    $this->load->view('resumen_existencias_view', $send);
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

}
