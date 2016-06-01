<?php

/**
 * Description of liquid_recaudacion
 *
 * @author MARIUXI
 */
class Liquid_recaudacion extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('servicio_consulta_externa');
        $this->load->library('servicio_emerg_hospit');
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
                
                //Emergencia
                $res['emerg_efectivo'] = $this->servicio_emerg_hospit->get_fact_pago_efectivo_emergencia($fecha_desde, $fecha_hasta);
                $res['emerg_credito'] = $this->servicio_emerg_hospit->get_fact_pago_credito_emergencia($fecha_desde, $fecha_hasta);
                //Hospitalizados
                $res['hospit_efectivo'] = $this->servicio_emerg_hospit->get_fact_pago_efectivo_hospitalizados($fecha_desde, $fecha_hasta);
                $res['hospit_credito'] = $this->servicio_emerg_hospit->get_fact_pago_credito_hospitalizados($fecha_desde, $fecha_hasta);
                
                //Desglose de Ingresos Diarios
                //Emergencia
                $res['desg_emerg_efectivo']= $this->servicio_emerg_hospit->get_desglose_emergencia($fecha_desde, $fecha_hasta);
                $res['desg_emerg_credito']= $this->servicio_emerg_hospit->get_desglose_emergencia_aseguradoras($fecha_desde, $fecha_hasta);
                //Hospitalización
                $res['desg_hospit_efectivo']= $this->servicio_emerg_hospit->get_desglose_hospitalizacion($fecha_desde, $fecha_hasta);
                $res['desg_hospit_credito']= $this->servicio_emerg_hospit->get_desglose_hospitaliz_aseguradoras($fecha_desde, $fecha_hasta);
                
                $this->load->view('recaudacion/result_ing_diarios', $res);
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas', '18px');
        }
    }

}
