<?php

/**
 * Description of ingresos_devoluc
 *
 * @author MARIUXI
 */
class Ingresos_devoluc extends MX_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function load_search_ing_dev() {
        $data['bodega'] = $this->generic_model->get_data('billing_bodega', array('deleted' => 0, 'vistaweb'=>1), 'id,nombre');
        $this->load->view('search_ing_dev', $data);
    }

    public function get_ingresos($where_data) {

        $join_cluase = array(
            '0' => array('table' => 'billing_proveedor p', 'condition' => 'p.id = aj.proveedor_id')
        );

        $fields = 'CONCAT(p.nombres," ",p.apellidos) proveedor,aj.fecha,
                    ROUND(aj.total,2) subtotal,
                    ROUND((aj.total - (aj.subtbienes + aj.subtservicios)),2) iva,
                    ROUND((aj.subtbienes + aj.subtservicios),2) total, aj.id, aj.observaciones';

        $send['query'] = $this->generic_model->get_join('bill_ajustentrada aj', $where_data, $join_cluase, $fields);

        $fields_suma = 'ROUND(SUM(aj.total),2) subtotal,
                        ROUND(SUM(aj.total - (aj.subtbienes + aj.subtservicios)),2) iva,
                        ROUND(SUM((aj.subtbienes + aj.subtservicios)),2) total, aj.id, aj.observaciones';

        $send['suma'] = $this->generic_model->get_join('bill_ajustentrada aj', $where_data, $join_cluase, $fields_suma)[0];

        return $send;
    }

    public function get_devoluciones($where_data) {
        $fields2 = 'aj.fecha,ROUND(aj.total,2) subtotal,
                    ROUND((aj.total - (aj.subtbienes + aj.subtservicios)),2) iva,
                    ROUND((aj.subtbienes + aj.subtservicios),2) total, aj.observaciones, aj.id';

        $send['query2'] = $this->generic_model->get('bill_ajustesalida aj', $where_data, $fields2);

        $fields_suma2 = 'ROUND(SUM(aj.total),2) subtotal,
                        ROUND(SUM(aj.total - (aj.subtbienes + aj.subtservicios)),2) iva,
                        ROUND(SUM((aj.subtbienes + aj.subtservicios)),2) total';

        $send['suma2'] = $this->generic_model->get('bill_ajustesalida aj', $where_data, $fields_suma2)[0];
        return $send;
    }

    public function get_ingresos_devoluciones() {
        $fecha_ini = $this->input->post('f_desde');
        $fecha_fin = $this->input->post('f_hasta');
        $bodega_id = $this->input->post('bodega_id');
        if (!empty($fecha_ini) && !empty($fecha_fin)) {
            if ($fecha_ini <= $fecha_fin) {
                if ($bodega_id != -1) {

                    $where_data['aj.fecha >='] = $fecha_ini;
                    $where_data['aj.fecha <='] = $fecha_fin;
                    $where_data['aj.fecha <>'] = '2016-09-06';
                    $where_data['aj.bodega_id'] = $bodega_id;
                    $where_data['aj.estado'] = 1;//Para los ajustes de salida
                    
                    $send['devoluciones'] = $this->get_devoluciones($where_data);

//                    $where_data['aj.tipo'] = 2; //Cuando se trata de una ajuste de entrada, que no se carga inicialmente
                    $where_data['aj.estado'] = 2;//Para los ajustes de entrada
                    $send['ingresos'] = $this->get_ingresos($where_data);

                    if ($send['ingresos']['suma']->total != null && $send['devoluciones']['suma2']->total != null) {
                        $send['saldo_favor'] = $send['ingresos']['suma']->total - $send['devoluciones']['suma2']->total;
                    } else {
                        $send['saldo_favor'] = 0;
                    }

                    $send['fecha_desde'] = $fecha_ini;
                    $send['fecha_hasta'] = $fecha_fin;
                    $send['nombre_bodega'] = $this->generic_model->get_val_where('billing_bodega', array('id' => $bodega_id), 'nombre', null, -1);

                    $this->load->model('common/empleadocapacidad_model');
                    $send['auxiliar_cont'] = $this->empleadocapacidad_model->get('aux_contab_farmacia');
                    $this->load->view('ingresos_devoluciones_hmc', $send);
                } else {
                    echo info_msg('Debe seleccionar una bodega.', '18px');
                }
            } else {
                echo info_msg('La fecha de inicio debe ser menor o igual a la fecha final de busqueda.', '18px');
            }
        } else {
            echo info_msg('Debe seleccionar un rango de fechas.', '18px');
        }
    }

}
