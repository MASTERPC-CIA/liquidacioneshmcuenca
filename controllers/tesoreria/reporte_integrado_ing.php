<?php

/**
 * Description of reporte_integrado_ing
 *
 * @author MARIUXI
 */
class reporte_integrado_ing extends MX_Controller {

    private $pago_efectivo = 1; // Cuando las facturas se pagan en efectivo
    private $pago_credito = 2; //Cuando las facturas se pagan a crédito
    private $tipo_comprobante = '01'; //01 para facturas
    private $serv_default = -1; //-1 corresponde al código del servicio por defecto, es decir NINGUNO
    private $cons_externa = 1; //1 corresponde al código de consulta externa
    private $hospitaliz = 2; // Servicio Emergencia
    private $emergencia = 3; // Servicio Hospitalizacion
    private $cod_paciente_civil = 14; //Corresponde al id de un paciente civil
    private $estado_planilla = 3; //Corresponde al estado de una planilla, 3 para la planilla, 2 para la pre-planilla
    private $estado_factura = 2; //Para facturas archivadas
    private $lista_grupos;
    private $lista_asegs;
    
    private $seg_sucre=15; //Id aseguradora Sucre
    private $seg_issfa=1; //Id aseguradora ISSFA
    private $seg_iess=3; //Id aseguradora IESS, agregar otro para el seguro voluntario
    private $seg_campesino=5; //Id Seguro Campesino
    private $seg_msp=24; //Id seguro Ministerio de salud publica OJO cambiar por id del seguro
    private $seg_sppat=25; //Id seguro Soat OJO cambiar por id del seguro
    

    public function __construct() {
        parent::__construct();
        $this->load->library('rep_integ_mensual');
        $this->lista_grupos = array();
        $this->lista_asegs = array();
    }

    public function load_view_search_reporte_int() {
        $this->load->view('tesoreria/search_rep_integrado_ing');
    }

    public function get_reporte_integrado_ing() {
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        if ($fecha_desde && $fecha_hasta) {
            if ($fecha_desde > $fecha_hasta) {
                echo info_msg('La fecha inicial debe ser menor a la fecha final de la cual desea sacar el reporte', '18px');
            } else {
                $res['fecha_desde'] = $fecha_desde;
                $res['fecha_hasta'] = $fecha_hasta;

//                $grupos = $this->get_grupos_reporte($fecha_desde, $fecha_hasta);
                $grupos = $this->get_lista_grupos();

                $res['lista_grupos'] = $grupos;
                $res['ningunServ_efect'] = $this->rep_integ_mensual->get_tot_grupo_servicio_factura($fecha_desde, $fecha_hasta, $this->serv_default, $this->tipo_comprobante, $this->pago_efectivo, $grupos);
                $res['conExterna_efect'] = $this->rep_integ_mensual->get_tot_grupo_servicio_factura($fecha_desde, $fecha_hasta, $this->cons_externa, $this->tipo_comprobante, $this->pago_efectivo, $grupos);
                $res['emergencia_efect'] = $this->rep_integ_mensual->get_tot_grupo_servicio_factura($fecha_desde, $fecha_hasta, $this->emergencia, $this->tipo_comprobante, $this->pago_efectivo, $grupos);
                $res['hospitaliz_efect'] = $this->rep_integ_mensual->get_tot_grupo_servicio_factura($fecha_desde, $fecha_hasta, $this->hospitaliz, $this->tipo_comprobante, $this->pago_efectivo, $grupos);
                //Consulta Externa 
                //Crédito get_tot_grupo_servicio_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_comprobante, $tipo_pago, $lista_grupos, $id_aseg)
                $res['CE_SUCRE'] = $this->rep_integ_mensual->get_tot_grupo_servicio_aseg($fecha_desde, $fecha_hasta, $this->cons_externa, $this->tipo_comprobante, $this->pago_credito, $grupos, $this->seg_sucre);
                $res['CE_ISSFA'] = $this->rep_integ_mensual->get_tot_grupo_servicio_aseg($fecha_desde, $fecha_hasta, $this->cons_externa, $this->tipo_comprobante, $this->pago_credito, $grupos, $this->seg_issfa);
                $res['CE_IESS'] = $this->rep_integ_mensual->get_tot_grupo_servicio_aseg($fecha_desde, $fecha_hasta, $this->cons_externa, $this->tipo_comprobante, $this->pago_credito, $grupos, $this->seg_iess);
                $res['CE_CAMPESINO'] = $this->rep_integ_mensual->get_tot_grupo_servicio_aseg($fecha_desde, $fecha_hasta, $this->cons_externa, $this->tipo_comprobante, $this->pago_credito, $grupos, $this->seg_campesino);
                $res['CE_MSP'] = $this->rep_integ_mensual->get_tot_grupo_servicio_aseg($fecha_desde, $fecha_hasta, $this->cons_externa, $this->tipo_comprobante, $this->pago_credito, $grupos, $this->seg_msp);
                $res['CE_SPPAT'] = $this->rep_integ_mensual->get_tot_grupo_servicio_aseg($fecha_desde, $fecha_hasta, $this->cons_externa, $this->tipo_comprobante, $this->pago_credito, $grupos, $this->seg_sppat);
                //Planillas
                $res['SUCRE'] = $this->rep_integ_mensual->get_tot_grupo_servicio_planilla($fecha_desde, $fecha_hasta, $this->cod_paciente_civil, $this->estado_planilla, $grupos, $this->seg_sucre);
                $res['ISSFA'] = $this->rep_integ_mensual->get_tot_grupo_servicio_planilla($fecha_desde, $fecha_hasta, $this->cod_paciente_civil, $this->estado_planilla, $grupos, $this->seg_issfa);
                $res['IESS'] = $this->rep_integ_mensual->get_tot_grupo_servicio_planilla($fecha_desde, $fecha_hasta, $this->cod_paciente_civil, $this->estado_planilla, $grupos, $this->seg_iess);
                $res['CAMPESINO'] = $this->rep_integ_mensual->get_tot_grupo_servicio_planilla($fecha_desde, $fecha_hasta, $this->cod_paciente_civil, $this->estado_planilla, $grupos, $this->seg_campesino);
                $res['MSP'] = $this->rep_integ_mensual->get_tot_grupo_servicio_planilla($fecha_desde, $fecha_hasta, $this->cod_paciente_civil, $this->estado_planilla, $grupos, $this->seg_msp);
                $res['SPPAT'] = $this->rep_integ_mensual->get_tot_grupo_servicio_planilla($fecha_desde, $fecha_hasta, $this->cod_paciente_civil, $this->estado_planilla, $grupos, $this->seg_sppat);
                $this->load->view('tesoreria/result_rep_integrado_ing', $res);
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas', '18px');
        }
    }

    public function get_grupos_reporte($fecha_desde, $fecha_hasta) {

        $grupos_facts = $this->get_grupos_facturas($fecha_desde, $fecha_hasta);
        $grupos_plans = $this->get_grupos_planillas($fecha_desde, $fecha_hasta);
//Carga la lista con todos los grupos otenidos de las facturas
        if ($grupos_facts) {
            foreach ($grupos_facts as $value) {
                array_push($this->lista_grupos, $value);
            }
        }
//Carga la lista con los grupos obtenidos de las plantillas, siempre y cuando no esten en el grupo de las facturas
        if ($grupos_plans) {
            foreach ($grupos_plans as $val) {
                if (!$this->verificar_existe_grupo($val->id)) {
                    array_push($this->lista_grupos, $val);
                }
            }
        }
        return $this->lista_grupos;
    }

    public function get_aseguradoras_reporte($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante, $tipo_paciente, $estado_pla) {
        $aseg_facts = $this->liquidacion_all_servicios->get_aseguradoras_por_factura($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_pago, $tipo_comprobante);
        $aseg_plans = $this->liquidacion_planillas->get_aseg_pre_o_planillas($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado_pla);
//Carga la lista con  todas las aseguradoras obtenidas de las factuaras
        if ($aseg_facts) {
            foreach ($aseg_facts as $value) {
                array_push($this->lista_asegs, $value);
            }
        }
//Carga la lista con las aseguradoras obtenidas en las planillas, siempre y cuando no esten en
//las aseguradoras de las facturas
        if ($aseg_plans) {
            foreach ($aseg_plans as $value) {
                if (!$this->verificar_existe_aseguradora($value->id_aseg)) {
                    array_push($this->lista_asegs, $value);
                }
            }
        }
    }

    public function verificar_existe_grupo($id_grupo) {
        $cont = 0;
        $tam_lista = sizeof($this->lista_grupos);
        $existe = false;
        while ($existe == false && $cont < $tam_lista) {
            if ($this->lista_grupos[$cont]->id == $id_grupo) {
                $existe = true;
            }
            $cont++;
        }
        return $existe;
    }

    public function verificar_existe_aseguradora($id_aseg) {
        $cont_a = 0;
        $tam_list_a = sizeof($this->lista_asegs);
        $existe_a = false;
        while ($existe_a == false && $cont_a < $tam_list_a) {
            if ($this->lista_grupos[$cont_a]->id == $id_aseg) {
                $existe_a = true;
            }
            $cont_a++;
        }
        return $existe_a;
    }

    public function get_grupos_facturas($fecha_desde, $fecha_hasta) {
        $fields = 'DISTINCT(g.codigo) id, g.nombre';
        $where_data = array('fv.fechaarchivada >= ' => $fecha_desde, 'fv.fechaarchivada <= ' => $fecha_hasta, 'fv.tipo_pago' => $this->pago_efectivo,
            'fv.puntoventaempleado_tiposcomprobante_cod' => $this->tipo_comprobante, 'fv.estado' => $this->estado_factura);
        $join_cluase = array(
            '0' => array('table' => 'billing_facturaventadetalle fvd', 'condition' => 'fvd.facturaventa_codigofactventa=fv.codigofactventa'),
            '1' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=fvd.Producto_codigo'),
            '2' => array('table' => 'billing_productogrupo g', 'condition' => 'g.codigo=p.productogrupo_codigo'),
        );

        $grupos = $this->generic_model->get_join('billing_facturaventa fv', $where_data, $join_cluase, $fields);
        return $grupos;
    }

    public function get_grupos_planillas($fecha_desde, $fecha_hasta) {
        $fields = 'DISTINCT(g.codigo) id, g.nombre';

        $where_data = array('pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $this->estado_planilla,
            'bc.clientetipo_idclientetipo !=' => $this->cod_paciente_civil);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
            '3' => array('table' => 'billing_productogrupo g', 'condition' => 'g.codigo=p.productogrupo_codigo'),
        );

        $grupos = $this->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $grupos;
    }

    public function get_lista_grupos() {
        return $this->generic_model->get('billing_productogrupo', null, 'codigo id, nombre', array('codigo'=>'asc'));
    }

}
