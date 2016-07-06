<?php

/**
 * Description of cosulta_externa_rep
 *
 * @author MARIUXI
 */
class Servicio_consulta_externa {

    private $ci;
    private $tipo_pago_efectivo = 1; // Cuando las facturas se pagan en efectivo
    private $tipo_pago_credito = 2; //Cuando las facturas se pagan a crédito
    private $comprob_factura = '01'; //01 para facturas
    private $cod_cons_externa = 1; //1 corresponde al código de consulta externa
    private $cod_serv_default = -1; //-1 corresponde al código del servicio por defecto, es decir NINGUNO
    private $comprob_servicio = '59'; //Tipo de comprobante creado para guardar las cuentas por cobrar de aquellos pacientes que tienen aseguradora
    
    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->library('liquidacion_all_servicios');
    }

    //Permite obtener el total de los servicios consumidos por pacientes que se encuentran en consulta externa
    public function get_fact_pago_efectivo_pacientes($fecha_desde, $fecha_hasta){
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_servicio($fecha_desde, $fecha_hasta, $this->cod_cons_externa, $this->tipo_pago_efectivo, $this->comprob_factura);
    }
    
    //Permite obtener el total de los servicios consumidos por clientes externos
    public function get_fact_pago_efectivo_clientes($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_servicio($fecha_desde, $fecha_hasta, $this->cod_serv_default, $this->tipo_pago_efectivo, $this->comprob_factura);
    }
      
    //Permite obtener el total de los servicios consumidos por pacientes que se encuentran en consulta externa y tienen una aseguradora que cubre sus gastos
    public function get_fact_pago_credito_pacientes($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_aseguradora($fecha_desde, $fecha_hasta, $this->cod_cons_externa, $this->tipo_pago_credito, $this->comprob_servicio);
    }

}
