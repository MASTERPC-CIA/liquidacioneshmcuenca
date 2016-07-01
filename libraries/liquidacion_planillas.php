<?php

/**
 * Description of liquidacion_planillas
 *
 * @author MARIUXI
 */
class liquidacion_planillas {

    private $ci;

    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->library('liquidacion_all_servicios');
    }

    //Para obtener las aseguradoras de las cuales se han hecho planillas
    public function get_aseg_pre_o_planillas($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado) {
        $fields = 'DISTINCT (ag.id) id_aseg, ag.ase_nombre';

        $where_data = array('pl.pla_tipo' => $tipo_servicio, 'pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'aseguradoras ag', 'condition' => 'ag.id=pl.pla_id_ase'),
        );

        $aseguradoras = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $aseguradoras;
    }

    //Para obtener clientes por pre o planillas de diferentes tipos de paciente

    public function get_clientes_por_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $id_aseg) {
        $fields = 'pl.id, pl.pla_aseguradora pla_valor_aseguradora, pl.pla_paciente pla_valor_paciente,pl.pla_total pla_valor_total, CONCAT_WS(" ",bc.nombres," ",bc.apellidos) nombres';

        $where_data = array('pl.pla_tipo' => $tipo_servicio, 'pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'pl.pla_id_ase' => $id_aseg);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
        );

        $clientes = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $clientes;
    }

    //para obtener los totales de pre o planillas
    public function get_totales_pre_o_planillas($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado) {

        $aseguradoras = $this->get_aseg_pre_o_planillas($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado);
        $list_aseg_serv = array();
        $tot_serv_cred = 0;
        $tot_serv_efect = 0;
        $total_servicio = 0;
        if ($aseguradoras) {
            $cont_aseg = 0;
            foreach ($aseguradoras as $key1 => $aseg) {
                $clientes = $this->get_clientes_por_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $aseg->id_aseg);
                if ($clientes) {
                    $val_aseg_cred = 0;
                    $val_aseg_efect = 0;
                    $val_tot_aseg = 0;
                    foreach ($clientes as $value) {
                        $val_aseg_cred+=$value->pla_valor_aseguradora;
                        $val_aseg_efect+=$value->pla_valor_paciente;
                        $val_tot_aseg+=$value->pla_valor_total;
                    }
                }
                $list_aseg_serv[$cont_aseg] = (Object) array('aseg' => $aseg, 'clientes' => $clientes, 'val_aseg_cred' => $val_aseg_cred, 'val_aseg_efect' => $val_aseg_efect, 'val_tot_aseg' => $val_tot_aseg);
                $cont_aseg++;
                $tot_serv_cred+=$val_aseg_cred;
                $tot_serv_efect+=$val_aseg_efect;
                $total_servicio += $val_tot_aseg;
            }
        }

        $res['list_aseg'] = $list_aseg_serv;
        $res['tot_serv_cred'] = $tot_serv_cred;
        $res['tot_serv_efect'] = $tot_serv_efect;
        $res['total_servicio'] = $total_servicio;
        $res['nombre_servicio'] = $this->get_name_servicio($tipo_servicio);

        return $res;
    }

    //Para obtener el nombre del servicio del cual se esta extrayendo los valores para la liquidación
    public function get_name_servicio($tipo_servicio) {
        return $this->ci->generic_model->get_val_where('bill_sttiposervicio', array('id' => $tipo_servicio), 'tipo');
    }

    public function get_valores_planillas_por_servicio($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado) {

        $aseguradoras = $this->get_aseg_pre_o_planillas($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado);

        $tot_serv_cred = 0;
        $tot_serv_efect = 0;

        $total_servicio = 0;
        $tot_aseg_iva_0 = 0;
        $tot_aseg_otro_iva = 0;
        $tot_aseg_iva = 0;
        $list_aseg_serv = array();

        if ($aseguradoras) {
            $cont_aseg = 0;
            foreach ($aseguradoras as $key1 => $aseg) {
                $grupos = $this->get_grupos_por_aseguradora($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $aseg->id_aseg);

                $total_grupos = 0;
                $tot_grupos_iva_0 = 0;
                $tot_grupos_otro_iva = 0;
                $tot_grupos_iva = 0;
                $list_grupos = array();

                if ($grupos) {
                    $cont_grupos = 0;
                    foreach ($grupos as $index1 => $grupo) {
                        $total_marcas = 0;
                        $tot_marcas_iva_0 = 0;
                        $tot_marcas_otro_iva = 0;
                        $tot_marcas_iva = 0;
                        $list_marcas = array();
                        $marcas = $this->get_marcas_por_grupo_y_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $grupo->id, $aseg->id_aseg);
                        if ($marcas) {
                            $cont_marcas = 0;
                            foreach ($marcas as $index2 => $marca) {
                                $productos = $this->get_prod_por_marca_y_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $marca->id, $grupo->id, $aseg->id_aseg);
                                $sum_valor_prod = 0;
                                $prod_iva_0 = 0;
                                $sum_iva = 0;
                                $prod_otro_iva = 0;
                                if ($productos) {
                                    foreach ($productos as $value) {
                                        if ($value->tarporcent == 0) {
                                            $prod_iva_0+=$value->itemprecioxcantidadneto;
                                        } else {
                                            $prod_otro_iva+=$value->itemprecioxcantidadneto;
                                            $sum_iva+=$value->itemprecioxcantidadneto;
                                        }
                                        $sum_valor_prod+=$value->itemprecioxcantidadneto;
                                    }
                                    $list_marcas[$cont_marcas] = (Object) array('marca' => $marca, 'valor_total' => $sum_valor_prod, 'subtotal_0' => $prod_iva_0, 'subtotal_iva' => $prod_otro_iva, 'iva' => $sum_iva);
                                    $cont_marcas++;

                                    $total_marcas+=$sum_valor_prod;
                                    $tot_marcas_iva_0+=$prod_iva_0;
                                    $tot_marcas_otro_iva = $prod_otro_iva;
                                    $tot_marcas_iva+=$sum_iva;
                                }
                            }
                            $list_grupos[$cont_grupos] = (Object) array('grupo' => $grupo, 'lista_marcas' => $list_marcas, 'valor_grupo' => $total_marcas, 'val_iva_0' => $tot_marcas_iva_0, 'val_otro_iva' => $tot_marcas_otro_iva, 'val_iva' => $sum_iva);
                            $cont_grupos++;
                            $total_grupos+=$total_marcas;
                            $tot_grupos_iva_0+=$tot_marcas_iva_0;
                            $tot_grupos_otro_iva+=$tot_marcas_otro_iva;
                            $tot_grupos_iva+=$tot_marcas_iva;
                        }
                    }
                }
                $list_aseg_serv[$cont_aseg] = (Object) array('aseg' => $aseg, 'lista_grupos' => $list_grupos, 'valor_aseg' => $total_grupos, 'val_gp_iva_0' => $tot_grupos_iva_0, 'val_gp_otro_iva' => $tot_grupos_otro_iva, 'val_gp_iva' => $tot_grupos_iva);
                $cont_aseg++;
                $total_servicio+=$total_grupos;
                $tot_aseg_iva_0+=$tot_grupos_iva_0;
                $tot_aseg_otro_iva+=$tot_grupos_otro_iva;
                $tot_aseg_iva+=$tot_grupos_iva;
            }
        } 
        $res['list_aseg'] = $list_aseg_serv;
//        $res['tot_serv_cred'] = $tot_serv_cred;
//        $res['tot_serv_efect'] = $tot_serv_efect;
        $res['total_servicio'] = $total_servicio;
        $res['total_serv_iva_0'] = $tot_aseg_iva;
        $res['total_serv_otro_iva'] = $tot_aseg_otro_iva;
        $res['total_serv_iva'] = $tot_aseg_iva;
        $res['nombre_servicio'] = $this->get_name_servicio($tipo_servicio);

        return $res;
    }

    public function get_grupos_por_aseguradora($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $id_aseg) {
        $fields = 'DISTINCT(g.codigo) id, g.nombre';

        $where_data = array('pl.pla_tipo' => $tipo_servicio, 'pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'pla_id_ase' => $id_aseg);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
            '3' => array('table' => 'billing_productogrupo g', 'condition' => 'g.codigo=p.productogrupo_codigo'),
        );

        $grupos = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $grupos;
    }

    public function get_marcas_producto_por_grupo($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $id_grupo) {
        $fields = 'DISTINCT(m.id) id, m.nombre';

        $where_data = array('pl.pla_tipo' => $tipo_servicio, 'pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'p.productogrupo_codigo' => $id_grupo);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
            '3' => array('table' => 'billing_marca m', 'condition' => 'm.id=p.marca_id'),
        );

        $marcas = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $marcas;
    }

    public function get_productos_por_marca($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $id_marca) {
        $fields = 'pld.pdet_total itemprecioxcantidadneto, it.tarporcent';

        $where_data = array('pl.pla_tipo' => $tipo_servicio, 'pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'p.marca_id' => $id_marca);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
            '3' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
            '4' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
        );
        $productos = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $productos;
    }
     public function get_marcas_por_grupo_y_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $id_grupo, $id_aseg) {
        $fields = 'DISTINCT(m.id) id, m.nombre';

        $where_data = array('pl.pla_tipo' => $tipo_servicio, 'pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'p.productogrupo_codigo' => $id_grupo, 'pl.pla_id_ase'=>$id_aseg);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
            '3' => array('table' => 'billing_marca m', 'condition' => 'm.id=p.marca_id'),
        );

        $marcas = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $marcas;
    }
     public function get_prod_por_marca_y_aseg($fecha_desde, $fecha_hasta, $tipo_servicio, $tipo_paciente, $estado, $id_marca, $id_grupo, $id_aseg) {
        $fields = 'pld.pdet_total itemprecioxcantidadneto, it.tarporcent';

        $where_data = array('pl.pla_tipo' => $tipo_servicio, 'pl.pla_fecha_creacion >= ' => $fecha_desde, 'pl.pla_fecha_creacion <= ' => $fecha_hasta, 'pl.pla_estado' => $estado,
            'bc.clientetipo_idclientetipo !=' => $tipo_paciente, 'p.marca_id' => $id_marca, 'p.productogrupo_codigo'=>$id_grupo, 'pl.pla_id_ase'=>$id_aseg);

        $join_cluase = array(
            '0' => array('table' => 'billing_cliente bc', 'condition' => 'bc.PersonaComercio_cedulaRuc=pl.pla_cedula_cliente'),
            '1' => array('table' => 'planillaje_det pld', 'condition' => 'pld.pdet_id_planillaje=pl.id'),
            '2' => array('table' => 'billing_producto p', 'condition' => 'p.codigo=pld.pdet_id_cod_producto'),
            '3' => array('table' => 'bill_productoimpuestotarifa pit', 'condition' => 'pit.producto_id = p.codigo'),
            '4' => array('table' => 'bill_impuestotarifa it', 'condition' => 'it.id = pit.impuestotarifa_id')
        );
        $productos = $this->ci->generic_model->get_join('planillaje pl', $where_data, $join_cluase, $fields);
        return $productos;
    }

}
