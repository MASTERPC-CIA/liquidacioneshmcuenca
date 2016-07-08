<?php

/**
 * Description of liquid_recaudacion
 *
 * @author MARIUXI
 */
class Liquid_recaudacion extends MX_Controller {

    private $consulta_externa = 1;
    private $hospitalizacion = 2;
    private $emergencia = 3;
    private $liq_ing_diarios = 1;

    public function __construct() {
        parent::__construct();
        $this->load->library('servicio_consulta_externa');
        $this->load->library('servicio_emerg_hospit');
        $this->load->library('liquidaciones');
    }

    public function load_view_search_desg_ing_diario() {
        $this->load->view('recaudacion/search_desg_ing_diario');
    }

    public function get_desg_ing_diario() {
        $this->load->view('recaudacion/result_desg_ing_diarios');
    }

    public function load_view_search_ing_diario() {
        $this->load->view('recaudacion/search_ing_diario');
    }

    public function get_ing_diario() {
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        if ($fecha_desde && $fecha_hasta) {
            if ($fecha_desde > $fecha_hasta) {
                echo info_msg('La fecha inicial debe ser menor a la fecha final de la cual desea sacar el reporte', '18px');
            } else {
                $res['fecha_desde'] = $fecha_desde;
                $res['fecha_hasta'] = $fecha_hasta;

                //Ningún servicio, clientes externos
                $res['efectivo_ext'] = $this->servicio_consulta_externa->get_fact_pago_efectivo_clientes($fecha_desde, $fecha_hasta);

                //Consulta Externa
                $res['efectivo'] = $this->servicio_consulta_externa->get_fact_pago_efectivo_pacientes($fecha_desde, $fecha_hasta);
                $res['credito'] = $this->servicio_consulta_externa->get_fact_pago_credito_pacientes($fecha_desde, $fecha_hasta);

                //Emergencia Pacientes Civiles
                $res['emerg_efectivo'] = $this->servicio_emerg_hospit->get_fact_pago_efectivo_emergencia($fecha_desde, $fecha_hasta);
                $res['emerg_credito'] = $this->servicio_emerg_hospit->get_fact_pago_credito_emergencia($fecha_desde, $fecha_hasta);
                //Hospitalizados
                $res['hospit_efectivo'] = $this->servicio_emerg_hospit->get_fact_pago_efectivo_hospitalizados($fecha_desde, $fecha_hasta);
                $res['hospit_credito'] = $this->servicio_emerg_hospit->get_fact_pago_credito_hospitalizados($fecha_desde, $fecha_hasta);

                //Desglose de Ingresos Diarios
                //Emergencia
                $res['desg_emerg_efectivo'] = $this->servicio_emerg_hospit->get_desglose_emergencia($fecha_desde, $fecha_hasta);
//                Emergencia para pacientes con aseguradora
                $res['desg_emerg_efect_ase'] = $this->servicio_emerg_hospit->get_desglose_emergencia_aseg_fact($fecha_desde, $fecha_hasta);
                $res['desg_emerg_credito'] = $this->servicio_emerg_hospit->get_desglose_emergencia_aseguradoras($fecha_desde, $fecha_hasta);
                //Hospitalización
                $res['desg_hospit_efectivo'] = $this->servicio_emerg_hospit->get_desglose_hospitalizacion($fecha_desde, $fecha_hasta);
                //Hospitalizados pacientes con aseguradora
                $res['desg_hospit_efect_aseg'] = $this->servicio_emerg_hospit->get_desglose_hospitaliz_aseg_fact($fecha_desde, $fecha_hasta);
                $res['desg_hospit_credito'] = $this->servicio_emerg_hospit->get_desglose_hospitaliz_aseguradoras($fecha_desde, $fecha_hasta);

                $this->load->view('recaudacion/result_ing_diarios', $res);
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas', '18px');
        }
    }

//    public function save_liq_ingresos_diarios($fecha_desde, $fecha_hasta, $efectivo_cli, $efectivo_cext, $efectivo_hosp, $efectivo_emerg, $credito_cext, $credito_hosp, $credito_emerg){
    public function save_liq_ingresos_diarios($fecha_desde, $fecha_hasta) {
        //Guarda la liquidación actual, guarda el id de la misma para guarda el detalle
        $fecha_act = date('Y-m-d', time());
        $fecha_liq = $this->generic_model->get('liquidacionhmc', array('liq_fechaDesde' => $fecha_desde, 'liq_fechaHasta' => $fecha_hasta, 'liq_fechaCreacion' => $fecha_act, 'liq_tipo' => $this->liq_ing_diarios));

        if (!$fecha_liq) {
            $id_liq = $this->liquidaciones->save_liquidacion($fecha_desde, $fecha_hasta, $this->liq_ing_diarios);
            if ($id_liq > 0) {
                $detalle = $this->get_detalle_save_liq($fecha_desde, $fecha_hasta);
                $id_det = $this->liquidaciones->save_det_liq_ingresos($id_liq, $detalle);
                if ($id_det) {
                    echo success_info_msg('Liquidación guardada', '18px');
                } else {
                    echo warning_msg('No se pudo guardar el detalle de la liquidación', '18px');
                }
            } else {
                echo warning_msg('No se pudo guardar la liquidación', '18px');
            }
        } else {
            echo info_msg('No se puede guardar la liquidación, ya existe una registrada en la misma fecha.', '18px');
        }
    }

    public function get_detalle_save_liq($fecha_desde, $fecha_hasta) {

        //Ningún servicio, clientes externos
        $efectivo_ext = $this->servicio_consulta_externa->get_fact_pago_efectivo_clientes($fecha_desde, $fecha_hasta);

        //Consulta Externa
        $efectivo = $this->servicio_consulta_externa->get_fact_pago_efectivo_pacientes($fecha_desde, $fecha_hasta);
        $credito = $this->servicio_consulta_externa->get_fact_pago_credito_pacientes($fecha_desde, $fecha_hasta);

        //Desglose de Ingresos Diarios
        //Emergencia
        $desg_emerg_efectivo = $this->servicio_emerg_hospit->get_desglose_emergencia($fecha_desde, $fecha_hasta);
        $desg_emerg_credito = $this->servicio_emerg_hospit->get_desglose_emergencia_aseguradoras($fecha_desde, $fecha_hasta);
        //Hospitalización
        $desg_hospit_efectivo = $this->servicio_emerg_hospit->get_desglose_hospitalizacion($fecha_desde, $fecha_hasta);
        $desg_hospit_credito = $this->servicio_emerg_hospit->get_desglose_hospitaliz_aseguradoras($fecha_desde, $fecha_hasta);

        $det_liquidaciones = array();

        foreach ($efectivo_ext['list'] as $value) {
            $det_liq['det_grupo_servicio'] = $this->consulta_externa;
            $det_liq['grupo_prod'] = $value->grupo->id;
            $det_liq['credito'] = 0;
            $det_liq['efectivo'] = $value->valor_grupo;
            $det_liq['total'] = $value->valor_grupo;
            $det_liq['id_aseguradora'] = -1;
            array_push($det_liquidaciones, $det_liq);
        }
        foreach ($efectivo['list'] as $value) {
            $det_liq['det_grupo_servicio'] = $this->consulta_externa;
            $det_liq['grupo_prod'] = $value->grupo->id;
            $det_liq['credito'] = 0;
            $det_liq['efectivo'] = $value->valor_grupo;
            $det_liq['total'] = $value->valor_grupo;
            $det_liq['id_aseguradora'] = -1;
            array_push($det_liquidaciones, $det_liq);
        }
        foreach ($credito['list_aseg'] as $value) {

            foreach ($value->lista_grupos as $val) {
                $det_liq['det_grupo_servicio'] = $this->consulta_externa;
                $det_liq['grupo_prod'] = $val->grupo->id;
                $det_liq['credito'] = $val->valor_grupo;
                $det_liq['efectivo'] = 0;
                $det_liq['total'] = $val->valor_grupo;
                $det_liq['id_aseguradora'] = $value->aseg->id;
                array_push($det_liquidaciones, $det_liq);
            }
        }

        foreach ($desg_emerg_efectivo['list'] as $value) {
            $det_liq['det_grupo_servicio'] = $this->emergencia;
            $det_liq['grupo_prod'] = $value->grupo->id;
            $det_liq['credito'] = 0;
            $det_liq['efectivo'] = $value->valor_grupo;
            $det_liq['total'] = $value->valor_grupo;
            $det_liq['id_aseguradora'] = -1;
            array_push($det_liquidaciones, $det_liq);
        }

        foreach ($desg_emerg_credito ['list_aseg'] as $value) {

            foreach ($value->lista_grupos as $val) {
                $det_liq['det_grupo_servicio'] = $this->emergencia;
                $det_liq['grupo_prod'] = $val->grupo->id;
                $det_liq['credito'] = $val->valor_grupo;
                $det_liq['efectivo'] = 0;
                $det_liq['total'] = $val->valor_grupo;
                $det_liq['id_aseguradora'] = $value->aseg->id_aseg;
                array_push($det_liquidaciones, $det_liq);
            }
        }

        foreach ($desg_hospit_efectivo['list'] as $value) {
            $det_liq['det_grupo_servicio'] = $this->hospitalizacion;
            $det_liq['grupo_prod'] = $value->grupo->id;
            $det_liq['credito'] = 0;
            $det_liq['efectivo'] = $value->valor_grupo;
            $det_liq['total'] = $value->valor_grupo;
            $det_liq['id_aseguradora'] = -1;
            array_push($det_liquidaciones, $det_liq);
        }

        foreach ($desg_hospit_credito ['list_aseg'] as $value) {

            foreach ($value->lista_grupos as $val) {
                $det_liq['det_grupo_servicio'] = $this->hospitalizacion;
                $det_liq['grupo_prod'] = $val->grupo->id;
                $det_liq['credito'] = $val->valor_grupo;
                $det_liq['efectivo'] = 0;
                $det_liq['total'] = $val->valor_grupo;
                $det_liq['id_aseguradora'] = $value->aseg->id;
                array_push($det_liquidaciones, $det_liq);
            }
        }

        return $det_liquidaciones;
    }

}
