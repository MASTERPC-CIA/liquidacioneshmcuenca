<?php

/**
 * Description of servicio_emerg_hospit
 *
 * @author MARIUXI
 */
class servicio_emerg_hospit {

    private $ci;
    private $tipo_pago_efectivo = 1; // Cuando las facturas se pagan en efectivo
    private $tipo_pago_credito = 2; //Cuando las facturas se pagan a crédito
    private $comprob_factura = '01'; //01 para facturas
    private $cod_emergencia = 3; //1 corresponde al código de consulta externa
    private $cod_hospitalizacion = 2; //-1 corresponde al código del servicio por defecto, es decir NINGUNO
    private $cod_cliente_civil = 14; //Corresponde al id de un paciente civil
    private $cod_estado_pla = 3; //Corresponde al estado de una planilla, 3 para la planilla, 2 para la pre-planilla
    private $comprob_servicio = '59';

    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->library('liquidacion_all_servicios');
        $this->ci->load->library('liquidacion_planillas');
    }

    //Permite obtener los valores cobrados por las planillas de hospitalizados facturadas a pacientes civiles que pagan en efectivo 
    public function get_fact_pago_efectivo_hospitalizados($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_all_servicios->get_totales_facturas_pago($fecha_desde, $fecha_hasta, $this->cod_hospitalizacion, $this->tipo_pago_efectivo, $this->comprob_factura, $this->cod_cliente_civil);
    }

    //Permite obtener los valores cobrados por las planillas de emergencia facturadas a pacientes civiles que pagan en efectivo 
    public function get_fact_pago_efectivo_emergencia($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_all_servicios->get_totales_facturas_pago($fecha_desde, $fecha_hasta, $this->cod_emergencia, $this->tipo_pago_efectivo, $this->comprob_factura, $this->cod_cliente_civil);
    }

    //Permite obtener los valores cobrados por las planillas de hospitalizados que pagan a crèdito 
    public function get_fact_pago_credito_hospitalizados($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_planillas->get_totales_pre_o_planillas($fecha_desde, $fecha_hasta, $this->cod_hospitalizacion, $this->cod_cliente_civil, $this->cod_estado_pla);
    }

    //Permite obtener los valores cobrados por las planillas de emergencia que pagan a crédito
    public function get_fact_pago_credito_emergencia($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_planillas->get_totales_pre_o_planillas($fecha_desde, $fecha_hasta, $this->cod_emergencia, $this->cod_cliente_civil, $this->cod_estado_pla);
    }

    //para obtener los totales de las facturas de las planillas a civiles por emergencia
    public function get_desglose_emergencia($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_servicio_new($fecha_desde, $fecha_hasta, $this->cod_emergencia, $this->tipo_pago_efectivo, $this->comprob_factura, 1);
    }

    //para obtener los totales de las facturas de las planillas a civiles por hospitalizacion
    public function get_desglose_hospitalizacion($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_servicio_new($fecha_desde, $fecha_hasta, $this->cod_hospitalizacion, $this->tipo_pago_efectivo, $this->comprob_factura, 1);
    }

    //Para obtener los totales de las facturas de planilla que pagan las aseguradoras para emergencia
    public function get_desglose_emergencia_aseguradoras($fecha_desde, $fecha_hasta) {
//        return $this->ci->liquidacion_planillas->get_valores_planillas_por_servicio($fecha_desde, $fecha_hasta, $this->cod_emergencia, $this->cod_cliente_civil, $this->cod_estado_pla);
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_aseguradora($fecha_desde, $fecha_hasta, $this->cod_emergencia, $this->tipo_pago_credito, $this->comprob_servicio);
    }

    //Para obtener los totales de las facturas de planilla que pagan las aseguradoras para emergencia
    public function get_desglose_hospitaliz_aseguradoras($fecha_desde, $fecha_hasta) {
//        return $this->ci->liquidacion_planillas->get_valores_planillas_por_servicio($fecha_desde, $fecha_hasta, $this->cod_hospitalizacion, $this->cod_cliente_civil, $this->cod_estado_pla);
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_aseguradora($fecha_desde, $fecha_hasta, $this->cod_hospitalizacion, $this->tipo_pago_credito, $this->comprob_servicio);
    }

    //Para obtener los totales de las facturas de planilla que pagan en efectivo las aseguradoras para emergencia
    public function get_desglose_emergencia_aseg_fact($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_aseguradora($fecha_desde, $fecha_hasta, $this->cod_emergencia, $this->tipo_pago_efectivo, $this->comprob_factura);
    }

    //Para obtener los totales de las facturas de planilla que pagan en efectivo las aseguradoras para emergencia
    public function get_desglose_hospitaliz_aseg_fact($fecha_desde, $fecha_hasta) {
        return $this->ci->liquidacion_all_servicios->get_valores_liquid_por_aseguradora($fecha_desde, $fecha_hasta, $this->cod_hospitalizacion, $this->tipo_pago_efectivo, $this->comprob_factura);
    }

}
