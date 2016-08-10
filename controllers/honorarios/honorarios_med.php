<?php

/**
 * Description of liquid_recaudacion
 *
 * @author MARIUXI
 */
class Honorarios_med extends MX_Controller {

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
    
    public function hm_cons_ext() {
        $this->load->view('head_cons_ext');
    }
    
    public function hm_servicio() {
        $this->load->view('head_servicio');
    }
    
    public function hm_proced() {
        $this->load->view('head_proced');
    }
    
    public function hm_h_alta() {
        $this->load->view('head_h_alta');
    }
    
    public function hm_quirof() {
        $this->load->view('head_quirof');
    }
    
    public function get_list_consulta() {
        $this->load->model('reporte_models');
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        $tipo = $this->input->post('tipo');

        $res = $this->reporte_models->get_consulta($fecha_desde, $fecha_hasta, $tipo);
        //echo $res = $this->reporte_models->get_parte_ope($fecha_desde, $fecha_hasta);
        $this->load->view('listado_consulta', $res);
    }
    
    public function get_list_servicio() {
        $this->load->model('reporte_models');
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        //$tipo = $this->input->post('tipo');

        $res = $this->reporte_models->get_servicio($fecha_desde, $fecha_hasta);
        //echo $res = $this->reporte_models->get_parte_ope($fecha_desde, $fecha_hasta);
        $this->load->view('listado_servicio', $res);
    }
    
    public function get_list_proced() {
        $this->load->model('reporte_models');
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        //$tipo = $this->input->post('tipo');

        $res = $this->reporte_models->get_proced($fecha_desde, $fecha_hasta);
        //echo $res = $this->reporte_models->get_parte_ope($fecha_desde, $fecha_hasta);
        $this->load->view('listado_procedimiento', $res);
    }
    
    public function get_list_h_alta() {
        $this->load->model('reporte_models');
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        //$tipo = $this->input->post('tipo');

        $res = $this->reporte_models->get_h_alta($fecha_desde, $fecha_hasta);
        //echo $res = $this->reporte_models->get_parte_ope($fecha_desde, $fecha_hasta);
        $this->load->view('listado_alta', $res);
    }
    
    public function get_list_quirof() {
        $this->load->model('reporte_models');
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        //$tipo = $this->input->post('tipo');

        $res = $this->reporte_models->get_quirof($fecha_desde, $fecha_hasta);
        //echo $res = $this->reporte_models->get_parte_ope($fecha_desde, $fecha_hasta);
        $this->load->view('listado_quirof', $res);
    }

}
