<?php

/**
 * Description of liquidacion_general
 *
 * @author MARIUXI
 */
class Liquidacion_general extends MX_Controller {

    protected $total_ingresos; //Guarda el total de ingresos liquidados 
    protected $total_egresos; //Guarda el total de egresos liquidados
    protected $fact_cons_ext; //Guarda las facturas de consulta externa
    protected $fact_hospitaliz; //Guarda las facturas de Hospitalización
    protected $fact_emergenc; // Guarda las facturas de Emergenecia
    protected $consulta_id = 1;

    public function __construct() {
        parent::__construct();
    }

    public function inicializar_arrays() {
        $this->fact_cons_ext = array();
        $this->fact_hospitaliz = array();
        $this->fact_emergenc = array();
    }

    public function get_tipos_cliente() {
        $tipos_cliente = $this->generic_model->get('billing_clientetipo', array('deleted' => '0'), 'id, tipo');
        return $tipos_cliente;
    }

    public function get_departamentos() {
        $res = $this->generic_model->get('billing_departamento', array('eliminado' => '0'), 'idDepartamento, nombre');
        return $res;
    }

    public function load_view_search_inv_prod() {
        $res['departamentos'] = $this->get_departamentos();
        $this->load->view('liq_general_views/search_liquid_inv_prod', $res);
    }

    public function load_view_search_liquid_servicio() {
        $res['departamentos'] = $this->get_departamentos();
        $this->load->view('liq_general_views/search_liquid_servicio', $res);
    }

    public function load_view_search_honor_medicos() {
        $res['departamentos'] = $this->get_departamentos();
        $this->load->view('liq_general_views/search_honor_medicos', $res);
    }

    public function get_data_liquidacion() {
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        $id_grupo_dep = $this->get_dep_liquidacion($this->input->post('depart_id'));
        if ($id_grupo_dep > 0) {
//            $this->get_det_fact_por_servicio($fecha_desde, $fecha_hasta, $id_grupo_dep);
            $this->load->view('liq_general_views/result_liquid_servicio');
        } else {
            echo info_msg('No existe un grupo de producto para el departamento seleccionado. ', '18px');
        }
    }
    public function get_det_fact_por_servicio($fecha_desde, $fecha_hasta, $id_grupo_dep) {
        $list = null;
        $cont = 0;
        $tot_ingresos = 0;

        $where = array('fv.fechaCreacion >= ' => $fecha_desde, 'fv.fechaCreacion <= ' => $fecha_hasta, 'fv.estado > ' => 0, 'p.productogrupo_codigo' => $id_grupo_dep);
        $join[0] = array('table' => 'billing_facturaventa fv', 'condition' => 'fvd.facturaventa_codigofactventa = fv.codigofactventa');
        $join[1] = array('table' => 'billing_producto p', 'condition' => 'fvd.Producto_codigo = p.codigo');
        $join[2] = array('table' => 'bill_sttiposervicio ts', 'condition' => 'fv.servicio_hmc = ts.id');

        $servicios = $this->generic_model->get_join('billing_facturaventadetalle fvd', $where, $join, 'DISTINCT (ts.id) id, ts.tipo');
        if ($servicios) {
            foreach ($servicios as $index => $serv) {
                $list_det_fact = null;
                $sum_serv = 0;
                $where['fv.servicio_hmc'] = $serv->id;
                $join[3] = array('table' => 'billing_cliente bc', 'condition' => 'fv.cliente_cedulaRuc = bc.PersonaComercio_cedulaRuc');
                $join[4] = array('table' => 'aseguradoras a', 'condition' => 'bc.aseguradora_id = a.id');
                $aseguradoras = $this->generic_model->get_join('billing_facturaventadetalle fvd', $where, $join, 'DISTINCT (a.id) id_aseg, a.ase_nombre');
                foreach ($aseguradoras as $key => $aseg) {
                    $join[5] = array('table' => 'billing_clientetipo ct', 'condition' => 'bc.clientetipo_idclientetipo = ct.idclientetipo');
                    $fields = 'fvd.itemprecioxcantidadbruto, fvd.itemprecioxcantidadneto';
                    $fact_detalle = $this->generic_model->get_join('billing_facturaventadetalle fvd', $where, $join, $fields);
                    $data_aseg_serv = null;
                    $sum_aseg = 0;
                    foreach ($fact_detalle as $val) {
                        $sum_aseg+=$val->itemprecioxcantidadneto;
                    }
                    if ($aseg->id_aseg >= 1 && $aseg->id_aseg <= 5) {
                        $nombre = ' PACIENTES ' . $aseg->ase_nombre;
                    } elseif ($aseg->id_aseg == 15) {
                        $nombre = 'PACIENTES CPTOS';
                    } else {
                        $nombre = ' PACIENTES CIVILES ';
                    }

                    $data_aseg_serv['total_aseg'] = $sum_aseg;
                    $data_aseg_serv ['nombre_aseg'] = $nombre;
                    $data_aseg_serv ['cod_aseg'] = $aseg->id_aseg;
                    $list_det_fact[$key] = $data_aseg_serv;
                    $sum_serv+=$sum_aseg;
                }
                $tot_ingresos += $sum_serv;

                $list[$cont] = (Object) array('servicio' => $serv, 'aseguradora' => $list_det_fact);
                $cont++;
            }
            $res['list'] = $list;
            $res['tot_ingresos'] = $tot_ingresos;
        } else {
            echo info_msg('No existen registros de facturas para este departamento', '18px');
        }
        print_r($res);
    }

    public function get_dep_liquidacion($id_dep) {
        $res = $this->generic_model->get_val_where('hmc_departamento_grupo_prod', array('dep_gp_id_departamento' => $id_dep), 'dep_gp_id_grupo', null, -1);
        return $res;
    }

    public function get_cliente_por_servicio($det_fact_servicio) {
        $clientes = $this->get_tipos_cliente();
        $sum_tot = 0;

        foreach ($servicios as $index => $serv) {

            $fields = 'DISTINCT(c.clientetipo_idclientetipo) tipo_cliente';
            $where = array('fv.fechaCreacion >= ' . $fecha_desde, 'fv.fechaCreacion <= ' . $fecha_hasta, 'fv.servicio_hmc' => $serv->id);
            $join_cluase = array(
//                            '0'=>array('table'=>'billing_facturaventa fv','condition'=>'fv.codigofactventa=fvd.codigofactventa'),
                '0' => array('table' => 'billing_cliente c', 'condition' => 'fv.cliente_cedulaRuc=c.PersonaComercio_cedulaRuc'),
                '1' => array('table' => 'billing_clientetipo ct', 'ct.idclientetipo=c.clientetipo_idclientetipo')
            );
            $facturas = $this->generic_model->get_join('billing_facturaventa fv', $where, $join_cluase);
            $products_iva0 = null;
            $products_iva12 = null;
            $total = 0;
            $tot_iva0 = 0;
            $tot_iva12 = 0;
            if (count($productos)) {
                foreach ($productos as $key => $producto) {
                    $where = array('kb.fecha <=' => $data->fecha_ini, 'kb.bodega_id' => $data->bodega);
                    $join_cluase = array(
                        '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo = kb.producto_id and p.codigo = ' . $producto->codigo),
                        '1' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
                        '2' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
                    );
                    $fields = 'p.codigo, it.tarporcent, p.nombreUnico, kb.kardex_total, (kb.costo_prom * kb.kardex_total) subtotal, kb.costo_prom costopromediokardex';
                    $prod = $this->generic_model->get_join('bill_kardex kb', $where, $join_cluase, $fields, $rows_num = 1, $order_by = array('kb.id' => 'DESC'));
                    if (count($prod)) {
                        if ($prod->tarporcent == 0) {
                            $products_iva0[$key] = (Object) $prod;
                            $tot_iva0 += $prod->subtotal;
                        }
                        if ($prod->tarporcent == 12) {
                            $products_iva12[$key] = (Object) $prod;
                            $tot_iva12 += $prod->subtotal;
                        }
                        $total = $tot_iva0 + $tot_iva12;
                    }
                }
                $sum_tot += $total;
                $sum_tot_iva0 += $tot_iva0;
                $sum_tot_iva12 += $tot_iva12;
                if ((count($products_iva0)) || (count($products_iva12))) {
                    $list[$cont] = (Object) array('marca' => $marca, 'products_iva0' => $products_iva0, 'products_iva12' => $products_iva12, 'tot_iva0' => $tot_iva0, 'tot_iva12' => $tot_iva12, 'total' => $total);
                    $cont++;
                }
            }
        }

        $send['list'] = $list;
        $send['total'] = $sum_tot;
        $send['total_iva0'] = $sum_tot_iva0;
        $send['total_iva12'] = $sum_tot_iva12;
        echo json_encode($send);
    }

    
    //Para obtener la liquidacion de las existencias
    
    public function get_inv_productos(){
        $id_grupo_dep = $this->get_dep_liquidacion($this->input->post('depart_id'));
        if ($id_grupo_dep > 0) {
            $this->load->view('liq_general_views/result_liquid_inv_prod');
        } else {
            echo info_msg('No existe un grupo de producto para el departamento seleccionado. ', '18px');
        }
       
    }
    
    public function get_honor_medicos(){
        $id_grupo_dep = $this->get_dep_liquidacion($this->input->post('depart_id'));
        if ($id_grupo_dep > 0) {
            $this->load->view('liq_general_views/result_honor_medicos');
        } else {
            echo info_msg('No existe un grupo de producto para el departamento seleccionado. '.$this->input->post('depart_id'), '18px');
        }
       
    }
    //Funciones traidas desde recaudación
     public function load_view_search_facts_serv() {
        $res['departamentos'] = $this->generic_model->get('billing_departamento', array('eliminado' => '0'), 'idDepartamento, nombre');
        $this->load->view('liq_recaudacion_views/search_fact_liquid_serv', $res);
    }

    public function get_facts_por_servicio() {
        $id_grupo_dep = $this->get_dep_liquidacion($this->input->post('depart_id'));
        if ($id_grupo_dep > 0) {
            $this->load->view('liq_recaudacion_views/result_facts_servicio');
        } else {
            echo info_msg('No existe un grupo de producto para el servicio seleccionado. ' . $this->input->post('depart_id'), '18px');
        }
    }
     

//    public function get_dep_liquidacion($id_dep) {
//        $res = $this->generic_model->get_val_where('hmc_departamento_grupo_prod', array('dep_gp_id_departamento' => $id_dep), 'dep_gp_id_grupo', null, -1);
//        return $res;
//    }
      public function load_view_search_cons_ing_diario() {
        $this->load->view('liq_recaudacion_views/search_cons_ing_diario');
    }

      public function get_cons_ing_diario() {
        $this->load->view('liq_recaudacion_views/result_cons_ing_diarios');
    }
}
