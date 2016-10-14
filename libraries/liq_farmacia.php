<?php

/**
 * Description of liq_farmacia
 *
 * @author MARIUXI
 */
class liq_farmacia {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
    }

    //Permite obtener los reportes de farmacia por grupo de productos
    public function get_prods_farmacia_by_group($bodega_id, $grupo_id) {
        $where = array('sb.bodega_id' => $bodega_id);
        $join_cluase = array(
            '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo and p.productogrupo_codigo=' . $grupo_id),
        );
        $productos = $this->ci->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(p.codigo) codigo');
        return $productos;
    }

    public function get_tot_inv_inicial($fecha_inicial, $bodega_id, $producto_id) {
        if ($fecha_inicial < '2016-09-06') { /* Se toma como referencia esta fecha porque es la fecha en la que se migro el inventario de farmacia */
            $fecha_inicial = '2016-09-06';
        }
        $where = array('kb.fecha <=' => $fecha_inicial, 'kb.bodega_id' => $bodega_id, 'kb.producto_id' => $producto_id, 'kb.estado > ' => 0);
        $fields = 'kb.kardex_total cant_ini, kb.costo_prom costo_prom_ini, (kb.costo_prom * kb.kardex_total) subtotal_ini';
        $prod = $this->ci->generic_model->get('bill_kardex kb', $where, $fields, array('kb.id' => 'DESC'), 1);

        return $prod;
    }

    public function get_tot_ventas($f_desde, $f_hasta, $bodega_id, $prod_id) {
        $fields = 'fvd.itempreciobruto costo_vent, SUM(fvd.itemcantidad) cant_vent';
        $where = array('fv.fechaarchivada >=' => $f_desde, 'fv.fechaarchivada <=' => $f_hasta, 'fvd.bodega_id' => $bodega_id, 'fv.estado' => 2, 'fvd.Producto_codigo' => $prod_id, 'fv.puntoventaempleado_tiposcomprobante_cod <>' => '59');
        $join = array(
            '0' => array('table' => 'billing_facturaventa fv', 'condition' => 'fv.codigofactventa=fvd.facturaventa_codigofactventa')
        );
        $res = $this->ci->generic_model->get_join('billing_facturaventadetalle fvd', $where, $join, $fields);
        if ($res[0]->costo_vent == '') {
            $res[0]->costo_vent = 0;
        }
        if ($res[0]->cant_vent == '') {
            $res[0]->cant_vent = 0;
        }
        return $res;
    }

    public function get_tot_aj_entrada($f_desde, $f_hasta, $bodega_id, $prod_id) {
        $fecha_inv_inicial = '2016-09-06'; //Se crea esta variable para que no se tome en cuenta el ajuste inicial de inventario, ya que los de farmacia cargan productos como si fuesen ajustes inciales y solo se deben mostrar esos más no el ajuste inicial 
        $fields = 'aed.itemcosto costo_ing_ent, SUM(aed.itemcantidad) cant_ing_ent';

        $where = array('ae.fecha >=' => $f_desde, 'ae.fecha <=' => $f_hasta, 'ae.fecha <>' => $fecha_inv_inicial, 'ae.bodega_id' => $bodega_id, 'aed.Producto_codigo' => $prod_id);
        $join = array(
            '0' => array('table' => 'bill_ajustentrada ae', 'condition' => 'ae.id=aed.ajustentrada_id')
        );
        $res = $this->ci->generic_model->get_join('bill_ajustentradadet aed', $where, $join, $fields);


        if ($res[0]->costo_ing_ent == '') {
            $res[0]->costo_ing_ent = 0;
        }
        if ($res[0]->cant_ing_ent == '') {
            $res[0]->cant_ing_ent = 0;
        }
        return $res;
    }

    public function get_tot_compras($f_desde, $f_hasta, $bodega_id, $prod_id) {
        $fields = 'fcd.itemcostobruto costo_comp, SUM(fcd.itemcantidad) cant_comp';
        $where = array('fc.fechaarchivada >=' => $f_desde, 'fc.fechaarchivada <=' => $f_hasta, 'fc.bodega_id' => $bodega_id, 'fc.estado' => 2, 'fcd.Producto_codigo' => $prod_id);
        $join = array(
            '0' => array('table' => 'billing_facturacompra fc', 'condition' => 'fc.codigoFacturaCompra=fcd.FacturaCompra_codigo')
        );
        $res = $this->ci->generic_model->get_join('billing_detallefacturacompra fcd', $where, $join, $fields);
        if ($res[0]->costo_comp == '') {
            $res[0]->costo_comp = 0;
        }
        if ($res[0]->cant_comp == '') {
            $res[0]->cant_comp = 0;
        }
        return $res;
    }

    public function get_tot_aj_salida($f_desde, $f_hasta, $bodega_id, $prod_id) {

        $fields = 'ajsd.itemcosto costo_eg_sal, SUM(ajsd.itemcantidad) cant_eg_sal';

        $where = array('ajs.fecha >=' => $f_desde, 'ajs.fecha <=' => $f_hasta, 'ajs.bodega_id' => $bodega_id, 'ajsd.Producto_codigo' => $prod_id);
        $join = array(
            '0' => array('table' => 'bill_ajustesalida ajs', 'condition' => 'ajs.id=ajsd.ajustesalida_id')
        );
        $res = $this->ci->generic_model->get_join('bill_ajustesalidadet ajsd', $where, $join, $fields);


        if ($res[0]->costo_eg_sal == '') {
            $res[0]->costo_eg_sal = 0;
        }
        if ($res[0]->cant_eg_sal == '') {
            $res[0]->cant_eg_sal = 0;
        }
        return $res;
    }

    public function get_tot_inv_final($fecha_final, $bodega_id, $producto_id) {

        $where = array('kb.fecha <=' => $fecha_final, 'kb.bodega_id' => $bodega_id, 'kb.producto_id' => $producto_id, 'kb.estado > ' => 0);
        $fields = 'kb.kardex_total cant_fin, kb.costo_prom costo_prom_fin, (kb.costo_prom * kb.kardex_total) subtotal_fin';
        $prod = $this->ci->generic_model->get('bill_kardex kb', $where, $fields, array('kb.id' => 'DESC'), 1);

        return $prod;
    }

}
