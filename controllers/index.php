<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MX_Controller {

    protected $cod_fact_hosp = 54;
    protected $cod_aj_entrada = '08';
    protected $cod_aj_salida = '13';
    protected $cod_compras = '02';
    protected $cod_ventas = '01';

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
    }

    public function index() {
        $send['app'] = 'appLiquidacionhmcuenca';
        $send['angularjs'] = $this->load_angularjs();
        $send['css_angular'] = '';
        $send_lte['slidebar_actions'] = $this->load->view('slidebar_hmc', '', TRUE);

        $send['slidebar'] = $this->load->view('common/templates/slidebar_lte', $send_lte, TRUE);
        $send['title'] = 'Liquidaciones';
        $this->load->view('common/templates/dashboard_lte_angularjs', $send);
    }

    public function load_angularjs() {
        return array(
            base_url('resources/js/angularjs/modulos/liquidacionhmcuenca/app.js'),
            base_url('resources/js/angularjs/modulos/liquidacionhmcuenca/data.js'),
            base_url('resources/js/angularjs/modulos/liquidacionhmcuenca/controller.js')
        );
    }

    public function load_bodegas() {
        $query = $this->generic_model->get('billing_bodega', array('deleted' => 0, 'vistaweb' => 1));
        echo json_encode($query);
    }

    public function find_bodega_x_id() {
        $data = json_decode(file_get_contents("php://input"));
        $query = $this->generic_model->get('billing_bodega', array('id' => $data->bodega), 'nombre')[0];
        echo json_encode($query);
    }

    public function load_prod_x_group_hmc() {
        $this->load->view('list_prod_x_group_hmc');
    }

    //Permite cargar los grupos de prroductos de farmacia
    public function load_grupos_farmacia() {
        $where = array('codigo >= ' => 344, 'codigo <= ' => 347);
        $query = $this->generic_model->get('billing_productogrupo', $where, 'codigo, nombre');
        echo json_encode($query);
    }

    public function find_grupo_x_id() {
        $data = json_decode(file_get_contents("php://input"));
        $query = $this->generic_model->get('billing_productogrupo', array('codigo' => $data->grupo_farm), 'nombre')[0];
        echo json_encode($query);
    }

    public function list_product_group_hmc() {

        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->bodega)) {

            $where = array('sb.bodega_id' => $data->bodega);
            $join_cluase = array(
                '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo and p.productogrupo_codigo=' . $data->grupo_farm),
            );
            $productos = $this->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(p.codigo) codigo');
            $productos_inv = null;

            $tot_ingresos = 0;
            $tot_devol = 0;
            $tot_ventas = 0;
            $tot_inicial = 0;
            $tot_final = 0;
            $tot_pvp = 0;

            if (count($productos)) {
                foreach ($productos as $key => $producto) {
                    $where = array('kb.fecha <=' => $data->fecha_fin, 'kb.bodega_id' => $data->bodega);
                    $join_cluase = array(
                        '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo = kb.producto_id and p.codigo = ' . $producto->codigo),
                        '1' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
                        '2' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
                    );
                    $fields = 'p.codigo, it.tarporcent, p.nombreUnico, kb.kardex_total, (kb.costo_prom * kb.kardex_total) subtotal, kb.costo_prom costopromediokardex, kb.transaccion_cod, kb.kardex';
                    $prod = $this->generic_model->get_join('bill_kardex kb', $where, $join_cluase, $fields, $rows_num = 1, array('kb.id' => 'DESC'));

                    $prod_ini = $this->get_tot_inv_inicial($data->fecha_ini, $data->bodega, $producto->codigo);
                    $prod_ajus_entrada = $this->get_tot_product($data->fecha_ini, $data->fecha_fin, $data->bodega, $producto->codigo, $this->cod_aj_entrada);
                    $prod_compras = $this->get_tot_product($data->fecha_ini, $data->fecha_fin, $data->bodega, $producto->codigo, $this->cod_compras);
                    $prod_ajus_salida = $this->get_tot_product($data->fecha_ini, $data->fecha_fin, $data->bodega, $producto->codigo, $this->cod_aj_salida);
                    $prod_ventas = $this->get_tot_product($data->fecha_ini, $data->fecha_fin, $data->bodega, $producto->codigo, $this->cod_ventas);

                    if (count($prod)) {
                        if ($prod->tarporcent == 0) {
                            $prod->pvp = $prod->costopromediokardex;
                        } else {
                            $prod->pvp = ($prod->costopromediokardex * (get_settings('IVA') / 100));
                        }

                        $prod->cant_inicial = $prod_ini->cant_ini;
                        $prod->tot_inicial = $prod_ini->subtotal_ini;

//                        $prod->ing_ajuste_cont=$prod_ajus_entrada->cant_prod;
//                        $prod->ing_ajuste_tot=$prod_ajus_entrada->total_prod;
//                        
//                        $prod->ing_compras_cont=$prod_compras->cant_prod;
//                        $prod->ing_compras_tot=$prod_compras->total_prod;
                        $prod->ingresos_cont = $prod_ajus_entrada->cant_prod + $prod_compras->cant_prod;
                        $prod->ingresos_tot = $prod_ajus_entrada->total_prod + $prod_compras->total_prod;

                        $prod->egre_ajuste_cont = $prod_ajus_salida->cant_prod;
                        $prod->egre_ajuste_tot = $prod_ajus_salida->total_prod;

                        $prod->egre_ventas_cont = $prod_ventas->cant_prod;
                        $prod->egre_ventas_tot = $prod_ventas->total_prod;

                        $productos_inv[$key] = (Object) $prod;
                    }
                    $tot_final+=$prod->subtotal;
                    $tot_inicial+=$prod->tot_inicial;
//                    $tot_ingresos+=($prod->ing_ajuste_tot+$prod->ing_compras_tot);
                    $tot_ingresos+=$prod->ingresos_tot;
                    $tot_devol+=$prod->egre_ajuste_tot;
                    $tot_ventas+=$prod->egre_ventas_tot;
                    $tot_pvp+=$prod->pvp;
                }
            }
        }
        $send['list'] = $productos_inv;
        $send['tot_final'] = $tot_final;
        $send['tot_inicial'] = $tot_inicial;
        $send['tot_ingresos'] = $tot_ingresos;
        $send['tot_devol'] = $tot_devol * -1; //Se multiplica por -1 para que cambie el signo 
        $send['tot_ventas'] = $tot_ventas * -1;
        $send['tot_pvp'] = $tot_pvp * -1;
        $send['nombre_grupo'] = $this->generic_model->get('billing_productogrupo', array('codigo' => $data->grupo_farm), 'nombre')[0];
        $send['nombre_bodega'] = $this->generic_model->get('billing_bodega', array('id' => $data->bodega), 'nombre')[0];
        echo json_encode($send);
    }

    public function get_tot_product($fecha_ini, $fecha_fin, $bodega_id, $producto_id, $tipo_transaccion) {
        $where = array('kb.fecha >=' => $fecha_ini, 'kb.fecha <=' => $fecha_fin, 'kb.bodega_id' => $bodega_id, 'kb.transaccion_cod' => $tipo_transaccion, 'kb.producto_id' => $producto_id);
        $fields = 'SUM(kb.kardex)cant_prod, (SUM(kb.kardex) * kb.costo_prom) total_prod';
        $prod = $this->generic_model->get('bill_kardex kb', $where, $fields, null, 1);
        if (empty($prod->cant_prod) && empty($prod->total_prod)) {
            $prod->cant_prod = 0;
            $prod->total_prod = 0;
        }
        return $prod;
    }

    public function get_tot_inv_inicial($fecha, $bodega_id, $producto_id) {
        $where = array('kb.fecha <' => $fecha, 'kb.bodega_id' => $bodega_id, 'kb.producto_id' => $producto_id);
        $fields = 'kb.kardex_total cant_ini, (kb.costo_prom * kb.kardex_total) subtotal_ini';
        $prod = $this->generic_model->get('bill_kardex kb', $where, $fields, array('kb.id' => 'DESC'), $rows_num = 1);
//         if(empty($prod->cant_ini) && empty($prod->subtotal_ini)){
//            $prod->cant_ini=0;
//            $prod->subtotal_ini=0;
//        }
        return $prod;
    }

    public function get_honorarios_medicos() {
        $this->load->view('head_honorarios_medicos');
    }

    public function get_list_honorarios() {
        $this->load->model('reporte_models');
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');

        $res = $this->reporte_models->get_turnos($fecha_desde, $fecha_hasta);
        //echo $res = $this->reporte_models->get_parte_ope($fecha_desde, $fecha_hasta);
        $this->load->view('listado_honorarios', $res);
    }

    public function load_prod_x_servicio_hmc() {
        $this->load->view('list_prod_x_servicio');
    }

    public function load_servicios_bodega() {
        $query = $this->generic_model->get_all('hmc_servicio_grupo_bodega');
        echo json_encode($query);
    }

    public function list_product_serv_hmc() {

        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->bodega)) {

            $where = array('sb.bodega_id' => $data->bodega);
            $join_cluase = array(
                '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo and p.productogrupo_codigo>=344 and p.productogrupo_codigo<=347'),
            );
            $productos = $this->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(p.codigo) codigo');
            $productos_inv = null;

            $tot_ingresos = 0;
            $tot_devol = 0;
            $tot_ventas = 0;
            $tot_inicial = 0;
            $tot_final = 0;
            $tot_pvp = 0;

            if (count($productos)) {
                foreach ($productos as $key => $producto) {
                    $where = array('kb.fecha <=' => $data->fecha_fin, 'kb.bodega_id' => $data->bodega);
                    $join_cluase = array(
                        '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo = kb.producto_id and p.codigo = ' . $producto->codigo),
                        '1' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
                        '2' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
                    );
                    $fields = 'p.codigo, it.tarporcent, p.nombreUnico, kb.kardex_total, (kb.costo_prom * kb.kardex_total) subtotal, kb.costo_prom costopromediokardex, kb.transaccion_cod, kb.kardex';
                    $prod = $this->generic_model->get_join('bill_kardex kb', $where, $join_cluase, $fields, $rows_num = 1, array('kb.id' => 'DESC'));

                    $prod_ini = $this->get_tot_inv_inicial($data->fecha_ini, $data->bodega, $producto->codigo);
                    $prod_ajus_entrada = $this->get_tot_product($data->fecha_ini, $data->fecha_fin, $data->bodega, $producto->codigo, $this->cod_aj_entrada);
                    $prod_compras = $this->get_tot_product($data->fecha_ini, $data->fecha_fin, $data->bodega, $producto->codigo, $this->cod_compras);
                    $prod_ajus_salida = $this->get_tot_product($data->fecha_ini, $data->fecha_fin, $data->bodega, $producto->codigo, $this->cod_aj_salida);
                    $prod_ventas = $this->get_tot_product($data->fecha_ini, $data->fecha_fin, $data->bodega, $producto->codigo, $this->cod_ventas);

                    if (count($prod)) {
                        if ($prod->tarporcent == 0) {
                            $prod->pvp = $prod->costopromediokardex;
                        } else {
                            $prod->pvp = ($prod->costopromediokardex * (get_settings('IVA') / 100));
                        }

                        $prod->cant_inicial = $prod_ini->cant_ini;
                        $prod->tot_inicial = $prod_ini->subtotal_ini;

                        $prod->ingresos_cont = $prod_ajus_entrada->cant_prod + $prod_compras->cant_prod;
                        $prod->ingresos_tot = $prod_ajus_entrada->total_prod + $prod_compras->total_prod;

                        $prod->egre_ajuste_cont = $prod_ajus_salida->cant_prod;
                        $prod->egre_ajuste_tot = $prod_ajus_salida->total_prod;

                        $prod->egre_ventas_cont = $prod_ventas->cant_prod;
                        $prod->egre_ventas_tot = $prod_ventas->total_prod;

                        $productos_inv[$key] = (Object) $prod;
                    }
                    $tot_final+=$prod->subtotal;
                    $tot_inicial+=$prod->tot_inicial;
//                    $tot_ingresos+=($prod->ing_ajuste_tot+$prod->ing_compras_tot);
                    $tot_ingresos+=$prod->ingresos_tot;
                    $tot_devol+=$prod->egre_ajuste_tot;
                    $tot_ventas+=$prod->egre_ventas_tot;
                    $tot_pvp+=$prod->pvp;
                }
            }
        }
        $send['list'] = $productos_inv;
        $send['tot_final'] = $tot_final;
        $send['tot_inicial'] = $tot_inicial;
        $send['tot_ingresos'] = $tot_ingresos;
        $send['tot_devol'] = $tot_devol * -1; //Se multiplica por -1 para que cambie el signo 
        $send['tot_ventas'] = $tot_ventas * -1;
        $send['tot_pvp'] = $tot_pvp * -1;
        $send['nombre_bodega'] = $this->generic_model->get('billing_bodega', array('id' => $data->bodega), 'nombre')[0];
        echo json_encode($send);
    }

}
