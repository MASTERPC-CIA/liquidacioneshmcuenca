<?php

/**
 * Description of facturas_hospit
 *
 * @author MARIUXI
 */
class Facturas_hospit_hmc extends MX_Controller {

    public function load_facturas_hospit() {
        $data['bodega'] = $this->generic_model->get_data('billing_bodega', array('deleted' => 0), 'id,nombre');
        $this->load->view('search_fact_hospit_hmc', $data);
    }

    public function get_facturas_hospit() {
        $this->load->view('result_fact_hospit_hmc');
    }

}
