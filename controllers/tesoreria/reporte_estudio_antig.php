<?php

/**
 * Description of reporte_estudio_antig
 *
 * @author MARIUXI
 */
class reporte_estudio_antig extends MX_Controller {

    private $pago_credito = 2; //Cuando las facturas se pagan a crédito
    private $tipo_comprobante = '01'; //01 para facturas
    private $cons_externa = 1; //1 corresponde al código de consulta externa
    private $hospitaliz = 2; // Servicio Emergencia
    private $emergencia = 3; // Servicio Hospitalizacion
    private $cod_paciente_civil = 14; //Corresponde al id de un paciente civil
    private $estado_planilla = 3; //Corresponde al estado de una planilla, 3 para la planilla, 2 para la pre-planilla
    private $estado_factura = 2; //Para facturas archivadas

    public function __construct() {
        parent::__construct();
        $this->load->library('rep_estudio_antig');
    }

    public function export_to_excel() {
        $this->get_reporte_estudio_antig();
    }

    public function load_view_search_estudio_antig() {
        $res['aseguradoras'] = $this->generic_model->get('aseguradoras', array('tiene_convenio' => 1), 'id, ase_nombre');
        $this->load->view('tesoreria/search_rep_estudio_antig', $res);
    }

    public function get_reporte_estudio_antig() {
        $fecha_desde = $this->input->post('f_desde');
        $fecha_hasta = $this->input->post('f_hasta');
        $id_aseg = $this->input->post('aseg_id');
        $tipo_rep = $this->input->post('tipo_rep');
        if ($fecha_desde && $fecha_hasta) {
            if ($fecha_desde > $fecha_hasta) {
                echo info_msg('La fecha inicial debe ser menor a la fecha final de la cual desea sacar el reporte', '18px');
            } else {
                if ($id_aseg != -1 && $tipo_rep != -1) {
                    $res['tipo'] = $tipo_rep;
                    $res['fecha_desde'] = $fecha_desde;
                    $res['fecha_hasta'] = $fecha_hasta;
                    if ($tipo_rep == 'Cliente') {
                        $res['campo_tipo'] = $this->rep_estudio_antig->get_estudio_antig_x_cliente($fecha_desde, $fecha_hasta, $this->estado_planilla, $this->cod_paciente_civil, $id_aseg);
                        $this->load->view('tesoreria/result_est_antig_por_cliente', $res);
                    } elseif ($tipo_rep == 'Servicio') {
                        $res['campo_tipo'] = $this->rep_estudio_antig->get_estudio_antig_x_servicio($fecha_desde, $fecha_hasta, $this->estado_planilla, $this->cod_paciente_civil, $id_aseg);
                        $this->load->view('tesoreria/result_est_antig_por_servicio', $res);
                    }
                } else {
                    echo info_msg('Debe seleccionar una aseguradora y un tipo de filtro', '18px');
                }
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas', '18px');
        }
    }

}
