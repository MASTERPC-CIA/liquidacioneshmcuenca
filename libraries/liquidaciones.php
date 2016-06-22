<?php

/**
 * Description of liquidaciones
 *
 * @author MARIUXI
 */
class liquidaciones {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
    }

    public function save_liquidacion($fecha_desde, $fecha_hasta, $tipo) {
        $fecha_act = date('Y-m-d', time());
        $hora_act = date('H:i:s', time());
        $data = array(
            'liq_fechaDesde' => $fecha_desde,
            'liq_fechaHasta' => $fecha_hasta,
            'liq_user_id' => $this->ci->user->id,
            'liq_fechaCreacion' => $fecha_act,
            'liq_horaCreacion' => $hora_act,
            'liq_tipo' => $tipo, //Para que se determine el tipo de liquidación
        );
        $id_liq = $this->ci->generic_model->save($data, 'liquidacionhmc');
        return $id_liq;
    }

    public function save_det_liq_ingresos($id_liq, $detalle_liquidacion) {
        $id_det = -1; //Se inicializa ya que se puede obtener o no un detalle 
        if ($detalle_liquidacion) {
            foreach ($detalle_liquidacion as $value) {
                $data_det = array(
                    'det_estado_servicio' => $value['det_grupo_servicio'],
                    'det_grupo_servicio' => $value['grupo_prod'],
                    'det_valor_credito' => $value['credito'],
                    'det_valor_efectivo' => $value['efectivo'],
                    'det_valor_total' => $value['total'],
                    'det_id_aseguradora' => $value['id_aseguradora'],
                    'det_id_liquidacion' => $id_liq
                );
                $id_det = $this->ci->generic_model->save($data_det, 'det_liquidacion_diaria');
            }
        }
        return $id_det;
    }
    
    public function save_det_liq_rep_integrado($id_liq, $detalle_liquidacion) {
        $id_det = -1; //Se inicializa ya que se puede obtener o no un detalle
        if ($detalle_liquidacion) {
            foreach ($detalle_liquidacion['list_tot_x_grupo'] as $value) {
                $data_det = array(
                    'det_rep_estado_serv' => $value->id_servicio,
                    'det_rep_grupo_serv' => $value->grupo_id,
                    'det_rep_val_credito' => $value->credito,
                    'det_rep_val_efectivo' => $value->efectivo,
                    'det_rep_id_aseg' => $value->id_aseguradora,
                    'det_id_liquidacion' => $id_liq
                );
                $id_det = $this->ci->generic_model->save($data_det, 'det_liq_integrada_mensual');
            }
        }
        return $id_det;
    }

}
