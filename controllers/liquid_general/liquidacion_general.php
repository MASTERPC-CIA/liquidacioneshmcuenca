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
    protected $fact_emergenc; // Guarda las facturas de Emergencia
    protected $consulta_id = 1;
    protected $grupo_imagen = 331;
    protected $comp_factura = '01';

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
        $res['servicios_bodega'] = $this->generic_model->get_all('hmc_servicio_grupo_bodega');
        $this->load->view('liq_general_views/search_liquid_servicio', $res);
    }

    public function load_view_search_honor_medicos() {
        $res['servicios_bodega'] = $this->generic_model->get_all('hmc_servicio_grupo_bodega');
        $this->load->view('liq_general_views/search_honor_medicos', $res);
    }

    public function get_data_liquidacion() {
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        $id_grupo_dep = $this->input->post('depart_id');
        if (!empty($fecha_desde) and ! empty($fecha_hasta)) {
            if ($fecha_desde <= $fecha_hasta) {
                if ($id_grupo_dep != -1) {
                    $tot_pla = $this->get_prod_pla_por_grupo($fecha_desde, $fecha_hasta, $id_grupo_dep)[0]->val_pla;
                    $tot_fact = $this->get_prod_fact_por_grupo($fecha_desde, $fecha_hasta, $id_grupo_dep)[0]->val_fact;
                    $nombreS = $this->generic_model->get_val_where('hmc_servicio_grupo_bodega', array('dep_gp_id_grupo' => $id_grupo_dep), 'dep_gp_descripcion');
                    $res['servicios'] = array(
                        '0' => array('servicio' => $nombreS, 'totalS' => $tot_fact + $tot_fact)
                    );
                    $res['fecha_desde'] = $fecha_desde;
                    $res['fecha_hasta'] = $fecha_hasta;
                    $res['nombreS'] = $nombreS;
                    $this->load->view('liq_general_views/result_liquid_servicio', $res);
                } else {
                    echo info_msg('Debe seleccionar un servicio para buscar!!!');
                }
            } else {
                echo info_msg('La fecha de inicio debe ser menor o igual a la fecha final de busqueda!!!');
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas para buscar!!!');
        }
    }

    public function get_dep_liquidacion($id_dep) {
        $res = $this->generic_model->get_val_where('hmc_departamento_grupo_prod', array('dep_gp_id_departamento' => $id_dep), 'dep_gp_id_grupo', null, -1);
        return $res;
    }

    //Para obtener la liquidacion de las existencias

    public function get_inv_productos() {
        $id_grupo_dep = $this->get_dep_liquidacion($this->input->post('depart_id'));
        if ($id_grupo_dep > 0) {
            $this->load->view('liq_general_views/result_liquid_inv_prod');
        } else {
            echo info_msg('No existe un grupo de producto para el departamento seleccionado. ', '18px');
        }
    }

    public function get_honor_medicos() {
        $id_grupo = $this->input->post('depart_id');
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        if (!empty($fecha_desde && !empty($fecha_hasta))) {
            if ($fecha_desde <= $fecha_hasta) {
                if ($id_grupo != -1) {
                    $res['fecha_desde'] = $fecha_desde;
                    $res['fecha_hasta'] = $fecha_hasta;
                    if ($id_grupo == $this->grupo_imagen) {
                        $res['porcent'] = get_settings('PORC_MED_RADIOLOGOS');
                    } else {
                        $res['porcent'] = 0; //Por defecto hasta preguntar como funciona para los demás servicios
                    }

                    $res['nombreS'] = $this->generic_model->get_val_where('hmc_servicio_grupo_bodega', array('dep_gp_id_grupo' => $id_grupo), 'dep_gp_descripcion');
                    $res['clientes_fact'] = $this->get_clientes_estudio_serv_fact($fecha_desde, $fecha_hasta, $id_grupo);
                    $res['clientes_plan'] = $this->get_clientes_estudio_serv_pla($fecha_desde, $fecha_hasta, $id_grupo);
                    $this->load->view('liq_general_views/result_honor_medicos', $res);
                } else {
                    echo info_msg('Debe seleccionar un servicio.', '18px');
                }
            } else {
                echo info_msg('la fecha incial debe ser menor a la fecha final de busqueda', '18px');
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas para buscar');
        }
    }

    public function get_clientes_estudio_serv_fact($fecha_desde, $fecha_hasta, $id_grupo) {
        $fields = 'fv.fechaarchivada, concat(bc.nombres, bc.apellidos) nombres, bst.tipo, bct.abreviatura, p.nombreUnico, fvd.itemxcantidadprecioiva valor';

        $where_data = array('fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'fv.puntoventaempleado_tiposcomprobante_cod' => '01', 'p.productogrupo_codigo' => $id_grupo);
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
            '1' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '3' => array('table' => 'billing_clientetipo bct', 'condition' => 'bct.idclientetipo=bc.clientetipo_idclientetipo'),
            '4' => array('table' => 'bill_sttiposervicio bst', 'condition' => 'bst.id=fv.servicio_hmc'),
        );
        $productos = $this->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $productos;
    }

    public function get_clientes_estudio_serv_pla($fecha_desde, $fecha_hasta, $id_grupo) {
        $fields = 'pl.pla_fecha_creacion, concat(bc.nombres, bc.apellidos) nombres, bst.tipo, bct.abreviatura, p.nombreUnico, pld.pdet_total';

        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado >=' => 2, //OJO, analizar el estado de la planilla, por lo de los honorarios medicos
            'p.productogrupo_codigo' => $id_grupo);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
            '3' => array('table' => 'billing_clientetipo bct', 'condition' => 'bct.idclientetipo=bc.clientetipo_idclientetipo'),
            '4' => array('table' => 'bill_sttiposervicio bst', 'condition' => 'bst.id=pl.pla_tipo'),
        );
        $productos = $this->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $productos;
    }

 
    
    //Funciones utiles para obtener la liquidacion por servicios 
    public function get_prod_fact_por_grupo($fecha_desde, $fecha_hasta, $id_grupo) {
        $servicios = $this->get_servicios_por_factura($fecha_desde, $fecha_hasta, $id_grupo);
        $list_val = array();
        $tot_val_fact =0;
        if($servicios){
            $cont=0;
            foreach ($servicios as $key => $serv) {
                $tipos_cliente = $this->get_tipos_cliente_por_servicio($fecha_desde, $fecha_hasta, $id_grupo, $serv->id_serv);
                if($tipos_cliente){
                    foreach ($tipos_cliente as $key => $tipoC) {
                        $tot_prod = $this->get_tot_prod_por_serv_y_tipo($fecha_desde, $fecha_hasta, $id_grupo, $serv->id_serv, $tipoC->id_cliente_tipo);
                        $descripcion = $tipoC->tipo_cliente.' '.$serv->tipo_serv;
                        $list_val[$cont]=(Object)array('descrip_ing'=>$descripcion, 'valor_ing'=>$tot_prod->val_total);
                        $tot_val_fact +=$tot_prod->val_total;
                        $cont++;
                    }
                }
                
            }
        }
        $send['list_ingresos']=$list_val;
        $send['tot_val'] = $tot_val_fact;
        return $send;
    }

    public function get_servicios_por_factura($fecha_desde, $fecha_hasta, $id_grupo) {
        $fields = 'DISTINCT(bst.id) id_serv, bst.tipo tipo_serv';
        $where_data = array('fv.tipo_pago' => '1', 'fv.puntoventaempleado_tiposcomprobante_cod' => $this->comp_factura,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2, 'p.productogrupo_codigo' => $id_grupo
        );
        $join_cluase = array(
            '0' => array('table' => 'bill_sttiposervicio bst', 'condition' => 'bst.id=fv.servicio_hmc'),
            '1' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
        );

        $servicios = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $servicios;
    }

    public function get_tipos_cliente_por_servicio($fecha_desde, $fecha_hasta, $id_grupo, $id_servicio) {
        $fields = 'DISTINCT(bct.idclientetipo) id_cliente_tipo, bct.tipo tipo_cliente';
        $where_data = array('fv.tipo_pago' => '1', 'fv.puntoventaempleado_tiposcomprobante_cod' => $this->comp_factura,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'p.productogrupo_codigo' => $id_grupo, 'fv.servicio_hmc' => $id_servicio
        );
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
            '1' => array('table' => 'billing_clientetipo bct', 'condition' => 'bct.idclientetipo=bc.clientetipo_idclientetipo'),
            '2' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '3' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
        );

        $tipos_client = $this->ci->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $tipos_client;
    }

    public function get_tot_prod_por_serv_y_tipo($fecha_desde, $fecha_hasta, $id_grupo, $id_servicio, $id_tipo) {
        $fields = 'sum(fvd.itemxcantidadprecioiva) val_total';
        $where_data = array('fv.tipo_pago' => '1', 'fv.puntoventaempleado_tiposcomprobante_cod' => $this->comp_factura,
            'fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.estado' => 2,
            'p.productogrupo_codigo' => $id_grupo, 'fv.servicio_hmc' => $id_servicio, 'bc.clientetipo_idclientetipo'=>$id_tipo
        );
        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=fv.cliente_cedulaRuc'),
            '1' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
        );
        $tot_prod = $this->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $tot_prod;
    }
    
    
    
       
    public function get_prod_pla_por_grupo($fecha_desde, $fecha_hasta, $id_grupo) {
//        $fields = 'pld.pdet_total itemprecioxcantidadneto, it.tarporcent';
        $fields = 'sum(pld.pdet_total) val_pla';

        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado >=' => 2, //OJO, analizar el estado de la planilla, por lo de los honorarios medicos
            'p.productogrupo_codigo' => $id_grupo);

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

}
