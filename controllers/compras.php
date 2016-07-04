<?php

class Compras extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->user->check_session();
    }

    public function load_compras() {
        $data['bodega'] = $this->generic_model->get_data('billing_bodega', array('deleted' => 0), 'id,nombre');
        $this->load->view('compras_view', $data);
    }

    public function crud_compras() {
        $this->load->model('common/empleadocapacidad_model');

        $proveedor_id = $this->input->post('supplier_id');
        $desde = $this->input->post('f_desde');
        $hasta = $this->input->post('f_hasta');
        $arch_desde = $this->input->post('f_arch_desde');
        $arch_hasta = $this->input->post('f_arch_hasta');
        $emi_desde = $this->input->post('f_emi_desde');
        $emi_hasta = $this->input->post('f_emi_hasta');
        $where_data = array('estado >' => 0);
        if (($desde and $hasta) or ( $arch_desde and $arch_hasta) or ( $emi_desde and $emi_hasta)) {
            $bodega_id = $this->input->post('bodega_id');
            if ($bodega_id != -1) {
                $where_data['bodega_id'] = $bodega_id;
                $compra['nombre_bodega'] = $this->generic_model->get_val('billing_bodega', $bodega_id, 'nombre');
            } else {
                $compra['nombre_bodega'] = 'RESUMEN GENERAL';
            }
            if (!empty($proveedor_id)) {
                $where_proveedor = array('proveedor_id' => $proveedor_id);
                $where = array_merge($where_data, $where_proveedor);
                $where_data = array_merge($where, $where_proveedor);
            }
            if (!empty($desde) and ! empty($hasta)) {
                $where_creacion = array('fechaCreacion >=' => $desde, 'fechaCreacion <=' => $hasta);
                $where = array_merge($where_data, $where_creacion);
            }
            if (!empty($arch_desde) and ! empty($arch_hasta)) {
                $where_archivada = array('fechaarchivada >=' => $arch_desde, 'fechaarchivada <=' => $arch_hasta);
                $where = array_merge($where_data, $where_archivada);
                $desde = $arch_desde;
                $hasta = $arch_hasta;
            }
            if (!empty($emi_desde) and ! empty($emi_hasta)) {
                $where_emision = array('fechaemisionfactura >=' => $emi_desde, 'fechaemisionfactura <=' => $emi_hasta);
                $where = array_merge($where_data, $where_emision);
                $desde = $emi_desde;
                $hasta = $emi_hasta;
            }
            $join_cluase = array(
                '0' => array('table' => 'billing_proveedor bp', 'condition' => 'bp.id = fc.proveedor_id')
            );
            $compra['fact_data'] = $this->generic_model->get_join('billing_facturacompra fc', $where, $join_cluase, $fields = 'fc.*,bp.nombres, bp.apellidos');
            $compra['desde'] = $desde;
            $compra['hasta'] = $hasta;

//            $compra['admin_farmacia'] = $this->empleadocapacidad_model->get('admin_farmacia');
//            $compra['contador'] = $this->empleadocapacidad_model->get('contador');
//            $compra['jefe_farmacia'] = $this->empleadocapacidad_model->get('jefe_farmacia');
//            $compra['jefe_financiero'] = $this->empleadocapacidad_model->get('jefe_financiero');
//            $compra['jefe_logistica'] = $this->empleadocapacidad_model->get('jefe_logistica');
//            $compra['director'] = $this->empleadocapacidad_model->get('director');
            $compra['auxiliar_cont'] = $this->empleadocapacidad_model->get('aux_contabilidad');
            $this->load->view('resumen_compras_hmc', $compra);
        } else {
            echo info_msg('Debe seleccionar un rango de fechas para buscar!!!');
        }
    }

}
