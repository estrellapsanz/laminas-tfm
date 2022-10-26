<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\View\Model\ViewModel;


class IndexController extends MasterController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function miPerfilAction()
    {
        //var_dump(getenv('APPLICATION_ENV'));
        //die;

        $login_estudiante = 'estrella.parrilla';
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($login_estudiante);
        foreach ($perfil_estudiante as &$plan_estudiante) {
            $plan_estudiante['ASIGNATURAS'] = $this->daoService->getEstudianteDAO()->dameAsignaturasEstudiante($plan_estudiante['COD_PLAN'], $plan_estudiante['NUMORD']);
        }


        return new ViewModel(['estudiante' => $perfil_estudiante]);
    }

    public function propuestaOfertaAction()
    {
        $usuario_logueado = 'estrella.parrilla';
        $curso = '2022-23';
        $misPropuestas = $exito = null;

        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($usuario_logueado);
        $misPropuestas = $this->daoService->getOfertaDAO()->dameMisOfertasPropuestas($usuario_logueado);

        /*echo('<pre>');
        var_dump($misPropuestas);
        echo('</pre>');
        die;*/
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();
            $estudios = explode('#', $post['estudios']); //plan, area
            $cod_plan = $estudios[0];
            //$area = $estudios[1];
            $titulo = $post['titulo'];
            $descripcion = $post['descripcion'];


            //controlamos que sólo haya una oferta creada por un estudiante asociada a ese plan, el estado en principio, distinto de Anulada
            $oferta_existente = $this->daoService->getEstudianteOfertaDAO()->existeAsociacion($usuario_logueado, null, $cod_plan);

            if (!$oferta_existente) {
                $cod_oferta = $this->daoService->getOfertaDAO()->insertaOferta($curso, $titulo, $descripcion, $usuario_logueado);

                if ($cod_oferta != -1) {
                    //$cod_oferta = $this->daoService->getOfertaDAO()->dameCodigoOferta($curso, $cod_plan, $usuario_logueado, 'Pendiente');
                    $exito = $this->daoService->getEstudianteOfertaDAO()->insertaEstudianteOferta($curso, $cod_oferta, 'Pendiente', $usuario_logueado, $cod_plan);
                }

            }
        }


        return new ViewModel(
            [
                'estudiante' => $perfil_estudiante,
                'propuestas' => $misPropuestas,
                'exito' => $exito
            ]);


    }

    public function solicitudDepositoAction()
    {

        $request = $this->getRequest();
        $post = $request->getPost();
        var_dump($post);
        die;
    }

    public function trabajosOfertadosAction()
    {
        $usuario_logueado = 'estrella.parrilla';
        $ofertas = [];
        $exito_accion = null;
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($usuario_logueado);
        foreach ($perfil_estudiante as $i => $plan_estudiante) {
            $codigo_plan = $plan_estudiante['COD_PLAN'];
            $datos_plan = $this->daoService->getDatosAcademicosDAO()->dameDatosPlan($codigo_plan);
            $planes_modal[$i]['COD_PLAN'] = $codigo_plan;
            $planes_modal[$i]['NOMBRE_PLAN'] = !empty($datos_plan) ? $datos_plan['NOMBRE_PLAN'] : 'DESCONOCIDO';
            if (!isset($ofertas[$plan_estudiante['NOMBRE_AREA']])) {
                $ofertas[$plan_estudiante['NOMBRE_AREA']] = $this->daoService->getOfertaDAO()->dameOfertasArea($plan_estudiante['COD_AREA']);
            }
        }

        if (!empty($ofertas)) {
            foreach ($ofertas as &$ofertas_area) {
                foreach ($ofertas_area as &$oferta) {
                    $oferta['FLG_EDITAR'] = ($oferta['USUARIO_ESTUDIANTE'] == $usuario_logueado) && ($oferta['ESTADO_ESTUDIANTE'] != 'Anulado' && !empty($oferta['USUARIO_ESTUDIANTE'])) ? 1 : 0;
                }
            }
        }

        /*echo('<pre>');
        var_dump($planes_modal);
        echo('</pre>');
        die;*/

        //recogemos una posible accion
        $exito_accion = $this->params()->fromRoute('exito');

        return new ViewModel([
            'flg_elegir_plan' => sizeof($planes_modal) > 1,
            'planes_estudiante' => $planes_modal,
            'ofertas' => $ofertas,
            'exito_accion' => $exito_accion,
            'curso' => '2022-23'
        ]);
    }


    public function guardarSolicitudOfertaAction()
    {

        //$this->sesion->getSesion();

        $usuario_logueado = 'estrella.parrilla';
        $curso = '2022-23';

        $nuevo_estado = null;
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();
            $cod_oferta = $post['cod_oferta'];
            $accion = $post['accion'];
            $plan_trabajo = $post['plan_trabajo'];
            $flag_pertenece = !empty($post['flg']) || $this->daoService->getEstudianteOfertaDAO()->existeAsociacion($usuario_logueado, $cod_oferta);

            if ($accion == 'anular')
                $nuevo_estado = 'Anulado';
            else if ($accion == 'solicitar')
                $nuevo_estado = 'Pendiente';

            if (!empty($nuevo_estado) && $flag_pertenece) {

                //update estado asociacion estudiante-oferta
                $exito = $this->daoService->getEstudianteOfertaDAO()->actualizarEstadoEstudiante($cod_oferta, $nuevo_estado, $usuario_logueado);
                if ($nuevo_estado == 'Anulado')
                    $exito2 = $this->daoService->getOfertaDAO()->actualizaEstado($cod_oferta, 'Anulada');

                /*VAR_DUMP(1);
                VAR_DUMP($exito);
                die;*/
            } else {
                //insert asociación estudiante - oferta
                $exito = $this->daoService->getEstudianteOfertaDAO()->insertaEstudianteOferta($curso, $cod_oferta, 'Pendiente', $usuario_logueado, $plan_trabajo);
                /*VAR_DUMP(2);
                VAR_DUMP($exito);
                die;*/
            }

            //todo meter en sesion   lo que sea
            $this->redirect()->toRoute('trabajos-ofertados', ['exito' => $exito]);

        }
    }
}
