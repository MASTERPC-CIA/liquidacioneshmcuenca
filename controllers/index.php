<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MX_Controller {

    protected $cod_fact_hosp = 54;
//    protected $cod_grupo_med=236; //Código del grupo de Medicina en Farmacia
//    protected $cod_grupo_quir=242; //Código del grupo de Material quirúrgico en Farmacia
//    protected $cod_grupo_insum=244; //Código del grupo de Insumos Médicos de Farmacia
//    protected $cod_grupo_misc=245; //Código de Miscélaneos de Farmacia
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
        $query = $this->generic_model->get_all('billing_bodega');
        echo json_encode($query);
    }

    public function load_list_price_x_group() {
        $this->load->view('list_price_x_group');
    }

    public function find_bodega_x_id() {
        $data = json_decode(file_get_contents("php://input"));
        $query = $this->generic_model->get('billing_bodega', array('id' => $data->bodega), 'nombre')[0];
        echo json_encode($query);
    }

    public function list_product_x_group() {
        $data = json_decode(file_get_contents("php://input"));

        $where = array('sb.bodega_id' => $data->bodega);
        $join_cluase = array(
            '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo'),
            '1' => array('table' => 'billing_marca m', 'condition' => 'm.id=p.marca_id')
        );
        $marcas = $this->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(m.id) id, m.nombre');

        $sum_tot = 0;
        $sum_tot_iva0 = 0;
        $sum_tot_iva12 = 0;
        $list = null;
        $cont = 0;
        $rta = 0;
        if (!empty($data->bodega)) {
            foreach ($marcas as $index => $marca) {
                $where = array('sb.bodega_id' => $data->bodega);
                $join_cluase = array(
                    '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo and p.marca_id=' . $marca->id),
                    '1' => array('table' => 'billing_marca m', 'condition' => 'm.id=p.marca_id')
                );
                $productos = $this->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(p.codigo) codigo');
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
        }
        $iva = $sum_tot_iva12 * (get_settings('IVA') / 100);
        $send['list'] = $list;
        $send['total_iva0'] = $sum_tot_iva0;
        $send['total_iva12'] = $sum_tot_iva12;
        $send['subtotal'] = $sum_tot;
        $send['iva'] = $iva;
        $send['total'] = $sum_tot + $iva;

        echo json_encode($send);
    }

    public function load_facturas_hospitalizacion() {
        $this->load->view('facturas_hospitalizacion');
    }

    public function consulta_facturas_hospitalizacion() {
        $data = json_decode(file_get_contents("php://input"));
        $where_data = array(
            'fv.puntoventaempleado_tiposcomprobante_cod' => $this->cod_fact_hosp,
            'fv.estado' => 2,
            'fv.fechaarchivada >=' => $data->fecha_ini,
            'fv.fechaarchivada <=' => $data->fecha_fin
        );
        $fields = 'fv.codigofactventa, fv.secuenciafactventa,fv.fechaCreacion ,CONCAT(c.nombres," ",c.apellidos) cliente,c.razonsocial';
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente c', 'condition' => 'c.PersonaComercio_cedulaRuc = fv.cliente_cedulaRuc')
        );
        $facturas = $this->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        $index = 0;
        $iva_doce = 0;
        $iva_cero = 0;
        $iva = 0;
        $recargo = 0;
        $valor_total = 0;
        if (count($facturas)) {
            foreach ($facturas as $key => $factura) {
                $where_data = array('fvd.facturaventa_codigofactventa' => $factura->codigofactventa, 'fvd.bodega_id' => $data->bodega);
                $productos = $this->generic_model->get('billing_facturaventadetalle fvd', $where_data);
                if (count($productos)) {
                    $tarifadoceneto = 0;
                    $tarifaceroneto = 0;
                    $ivaval = 0;
                    $recargovalor = 0;
                    $totalCompra = 0;
                    foreach ($productos as $key => $producto) {
                        $join_cluase = array(
                            '0' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
                            '1' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
                        );
                        $fields = '*';
                        $impuesto = $this->generic_model->get_join('billing_producto p', array('p.codigo' => $producto->Producto_codigo), $join_cluase, $fields);
                        if (count($impuesto) > 0) {
                            if ($impuesto[0]->tarporcent == 12) {
                                $tarifadoceneto += $producto->itemprecioxcantidadneto;
                            }
                            if ($impuesto[0]->tarporcent == 0) {
                                $tarifaceroneto += $producto->itemprecioxcantidadneto;
                            }
                            $ivaval += $producto->totivaval;
                            $recargovalor = $producto->recargofactvalor;
                            $totalCompra += $producto->itemprecioiva;
                        }
                    }
                    if (empty($factura->cliente)) {
                        $query[$index] = (Object) array(
                                    'codigofactventa' => $factura->codigofactventa,
                                    'cliente' => $factura->razonsocial,
                                    'fechaCreacion' => $factura->fechaCreacion,
                                    'secuenciafactventa' => $factura->secuenciafactventa,
                                    'tarifadoceneto' => $tarifadoceneto,
                                    'tarifaceroneto' => $tarifaceroneto,
                                    'ivaval' => $ivaval,
                                    'recargovalor' => $recargovalor,
                                    'totalCompra' => $totalCompra
                        );
                    } else {
                        $query[$index] = (Object) array(
                                    'codigofactventa' => $factura->codigofactventa,
                                    'cliente' => $factura->cliente,
                                    'fechaCreacion' => $factura->fechaCreacion,
                                    'secuenciafactventa' => $factura->secuenciafactventa,
                                    'tarifadoceneto' => $tarifadoceneto,
                                    'tarifaceroneto' => $tarifaceroneto,
                                    'ivaval' => $ivaval,
                                    'recargovalor' => $recargovalor,
                                    'totalCompra' => $totalCompra
                        );
                    }
                    $index++;
                }
            }
            foreach ($query as $key => $value) {
                $iva_doce += $value->tarifadoceneto;
                $iva_cero += $value->tarifaceroneto;
                $iva += $value->ivaval;
                $recargo += $value->recargovalor;
                $valor_total += $value->totalCompra;
            }
            $total[0] = (Object) array(
                        'iva_doce' => $iva_doce,
                        'iva_cero' => $iva_cero,
                        'iva' => $iva,
                        'recargo' => $recargo,
                        'valor_total' => $valor_total
            );
        } else {
            $query = 0;
            $total = 0;
        }
        $send['query'] = $query;
        $send['total'] = $total;

        echo json_encode($send);
    }

    public function load_devolucion() {
        $this->load->view('devoluciones_hmc');
    }

    public function load_ingreso() {

        $this->load->view('ingresos_hmc');
    }

    public function consulta_devoluciones() {
        $data = json_decode(file_get_contents("php://input"));
        if ((!empty($data->fecha_ini))and ( !empty($data->fecha_fin))) {
            $where_data['ajs.fecha >='] = $data->fecha_ini;
            $where_data['ajs.fecha <='] = $data->fecha_fin;
        }
        if (empty($data->fecha_ini)) {
            $where_data['ajs.fecha'] = $data->fecha_ini;
        }
        if (empty($data->fecha_fin)) {
            $where_data['ajs.fecha'] = $data->fecha_fin;
        }
        if (!empty($data->bodega)) {
            $where_data['ajs.bodega_id'] = $data->bodega;
        }
        $where_data['ajs.estado'] = 1;

        $join_cluase = null;

        $fields = 'ajs.fecha,ROUND(ajs.total,2) subtotal,
                    ROUND((ajs.total - (ajs.subtbienes + ajs.subtservicios)),2) iva,
                    ROUND((ajs.subtbienes + ajs.subtservicios),2) total, ajs.observaciones, ajs.id';

        $query = $this->generic_model->get('bill_ajustesalida ajs', $where_data, $fields);

        $fields_suma = 'ROUND(SUM(ajs.total),2) subtotal,
                        ROUND(SUM(ajs.total - (ajs.subtbienes + ajs.subtservicios)),2) iva,
                        ROUND(SUM((ajs.subtbienes + ajs.subtservicios)),2) total';

        $suma = $this->generic_model->get('bill_ajustesalida ajs', $where_data, $fields_suma)[0];

        $this->load->model('common/empleadocapacidad_model');

        $firma['admin_farmacia'] = $this->empleadocapacidad_model->get('admin_farmacia')[0]->empleado;
        $firma['contador'] = $this->empleadocapacidad_model->get('contador')[0]->empleado;
        $firma['jefe_farmacia'] = $this->empleadocapacidad_model->get('jefe_farmacia')[0]->empleado;
        $firma['jefe_financiero'] = $this->empleadocapacidad_model->get('jefe_financiero')[0]->empleado;
        $firma['jefe_logistica'] = $this->empleadocapacidad_model->get('jefe_logistica')[0]->empleado;
        $firma['director'] = $this->empleadocapacidad_model->get('director')[0]->empleado;
        $firma['aux_conta'] = $this->empleadocapacidad_model->get('aux_contabilidad')[0]->empleado;


        $send['query'] = $query;
        $send['suma'] = $suma;
        $send['firma'] = $firma;

        echo json_encode($send);
    }

    public function consulta_ingresos() {
        $data = json_decode(file_get_contents("php://input"));
        if ((!empty($data->fecha_ini))and ( !empty($data->fecha_fin))) {
            $where_data['ajs.fecha >='] = $data->fecha_ini;
            $where_data['ajs.fecha <='] = $data->fecha_fin;
        }
        if (empty($data->fecha_ini)) {
            $where_data['ajs.fecha'] = $data->fecha_ini;
        }
        if (empty($data->fecha_fin)) {
            $where_data['ajs.fecha'] = $data->fecha_fin;
        }
        if (!empty($data->bodega)) {
            $where_data['ajs.bodega_id'] = $data->bodega;
        }
        $where_data['ajs.estado'] = 2;
        $where_data['ajs.tipo'] = 2;

        $join_cluase = array(
            '0' => array('table' => 'billing_proveedor p', 'condition' => 'p.id = ajs.proveedor_id')
        );

        $fields = 'CONCAT(p.nombres," ",p.apellidos) proveedor,ajs.fecha,
                    ROUND(ajs.total,2) subtotal,
                    ROUND((ajs.total - (ajs.subtbienes + ajs.subtservicios)),2) iva,
                    ROUND((ajs.subtbienes + ajs.subtservicios),2) total, ajs.id, ajs.observaciones';

        $query = $this->generic_model->get_join('bill_ajustentrada ajs', $where_data, $join_cluase, $fields);

        $fields_suma = 'ROUND(SUM(ajs.total),2) subtotal,
                        ROUND(SUM(ajs.total - (ajs.subtbienes + ajs.subtservicios)),2) iva,
                        ROUND(SUM((ajs.subtbienes + ajs.subtservicios)),2) total, ajs.id, ajs.observaciones';

        $suma = $this->generic_model->get_join('bill_ajustentrada ajs', $where_data, $join_cluase, $fields_suma)[0];

        $this->load->model('common/empleadocapacidad_model');

        $firma['admin_farmacia'] = $this->empleadocapacidad_model->get('admin_farmacia')[0]->empleado;
        $firma['contador'] = $this->empleadocapacidad_model->get('contador')[0]->empleado;
        $firma['jefe_farmacia'] = $this->empleadocapacidad_model->get('jefe_farmacia')[0]->empleado;
        $firma['jefe_financiero'] = $this->empleadocapacidad_model->get('jefe_financiero')[0]->empleado;
        $firma['jefe_logistica'] = $this->empleadocapacidad_model->get('jefe_logistica')[0]->empleado;
        $firma['director'] = $this->empleadocapacidad_model->get('director')[0]->empleado;
        $firma['aux_conta'] = $this->empleadocapacidad_model->get('aux_contabilidad')[0]->empleado;

        $send['query'] = $query;
        $send['suma'] = $suma;
        $send['firma'] = $firma;

        echo json_encode($send);
    }

    public function load_inventario_anterior() {
        $data['bodega'] = $this->generic_model->get_data('billing_bodega', array('deleted' => 0), 'id,nombre');
        $this->load->view('inventario_anterior', $data, FALSE);
    }

    public function consulta_inventario_anterior() {
        $this->load->model('common/empleadocapacidad_model');
        $id_bodega = $this->input->post('bodega_id');
        $f_hasta = $this->input->post('f_hasta');
        if ($f_hasta) {
            $where = array('sb.bodega_id' => $id_bodega);
            $join_cluase = array(
                '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo'),
                '1' => array('table' => 'billing_marca m', 'condition' => 'm.id=p.marca_id')
            );
            $marcas = $this->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(m.id) id, m.nombre');

            $sum_tot_coniva = 0;
            $sum_tot_siniva = 0;
            $sum_tot = 0;
            $list = null;
            $products = null;
            $cont = 0;
            $rta = 0;
            if ($id_bodega != -1) {
                foreach ($marcas as $index => $marca) {
                    $where = array('sb.bodega_id' => $id_bodega);
                    $join_cluase = array(
                        '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=sb.producto_codigo and p.marca_id=' . $marca->id),
                        '1' => array('table' => 'billing_marca m', 'condition' => 'm.id=p.marca_id')
                    );
                    $productos = $this->generic_model->get_join('billing_stockbodega sb', $where, $join_cluase, $fields = 'DISTINCT(p.codigo) codigo');
                    $products = null;
                    $total_coniva = 0;
                    $total_siniva = 0;
                    $total = 0;
                    if (count($productos)) {
                        foreach ($productos as $key => $producto) {
                            $where = array('kb.fecha <=' => $f_hasta, 'kb.bodega_id' => $id_bodega);
                            $join_cluase = array(
                                '0' => array('table' => 'billing_producto p', 'condition' => 'p.codigo = kb.producto_id and p.codigo = ' . $producto->codigo),
                                '1' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
                                '2' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
                            );
                            $fields = 'p.codigo,p.nombreUnico,it.tarporcent, kb.kardex_total,(kb.costo_prom * kb.kardex_total) subtotal,kb.costo_prom costopromediokardex';
                            $prod = $this->generic_model->get_join('bill_kardex kb', $where, $join_cluase, $fields, $rows_num = 1, $order_by = array('kb.id' => 'DESC'));
                            if (count($prod)) {
                                $products[$key] = (Object) $prod;
                                if ($prod->tarporcent == 0) {
                                    $total_siniva += $prod->subtotal;
                                } elseif ($prod->tarporcent == 12) {
                                    $total_coniva += $prod->subtotal;
                                }
                                //$total += $prod->subtotal;
                            }
                            //$impuesto = $this->generic_model->get_val_where('bill_productoimpuestotarifa', array('producto_id'=>$producto->codigo), 'impuestotarifa_id');
                        }
                        if (count($products)) {
                            $list[$cont] = (Object) array('marca' => $marca, 'products' => $products, 'total' => $total);
                            $cont++;
                        }
                        $sum_tot_coniva += $total_coniva;
                        $sum_tot_siniva += $total_siniva;
                        //$sum_tot += $total;
                    }
                }
                $inventario['nombre_bodega'] = $this->generic_model->get_val('billing_bodega', $id_bodega, 'nombre');
            } else {
                $inventario['nombre_bodega'] = 'Total Inventario Anterior';
            }
            //$inventario['datatotal'] = $sum_tot;
            $inventario['datatotal_siva'] = $sum_tot_siniva;
            $inventario['datatotal_civa'] = $sum_tot_coniva;
            $inventario['hasta'] = $f_hasta;
            $inventario['admin_farmacia'] = $this->empleadocapacidad_model->get('admin_farmacia');
            $inventario['contador'] = $this->empleadocapacidad_model->get('contador');
            $inventario['jefe_farmacia'] = $this->empleadocapacidad_model->get('jefe_farmacia');
            $inventario['jefe_financiero'] = $this->empleadocapacidad_model->get('jefe_financiero');
            $inventario['jefe_logistica'] = $this->empleadocapacidad_model->get('jefe_logistica');
            $inventario['director'] = $this->empleadocapacidad_model->get('director');
            $this->load->view('result_inventario_anterior', $inventario);
        } else {
            echo info_msg('Escoja una fecha de corte!!!');
        }
    }

    public function load_prod_x_group_hmc() {
        $this->load->view('list_prod_x_group_hmc');
    }

    //Permite cargar los grupos de prroductos de farmacia
    public function load_grupos_farmacia() {
        $where = array('codigo >= ' => 236, 'codigo <= ' => 245);
//        $query = $this->generic_model->get('billing_productogrupo', null, 'codigo, nombre', null, 0,null, null,null,  array('codigo'=>$this->cod_grupo_insum, 'codigo'=>$this->cod_grupo_med, 'codigo'=>$this->cod_grupo_misc, 'codigo'=>$this->cod_grupo_quir));
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
                            $prod->pvp = costopromediokardex;
                        }
                        if ($prod->tarporcent == 12) {
                            $prod->pvp = ($prod->costopromediokardex * 0.12);
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
        $send['tot_devol'] = $tot_devol;
        $send['tot_ventas'] = $tot_ventas;
        $send['tot_pvp'] = $tot_pvp;
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

}
