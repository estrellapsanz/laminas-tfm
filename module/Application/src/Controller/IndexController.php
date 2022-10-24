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
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();

            $estudios = explode('#', $post['estudios']); //plan, area
            $plan = $estudios[0];
            $area = $estudios[1];
            $normativa = $post['normativa'];
            $titulo = $post['titulo'];
            $descripcion = $post['descripcion'];
            $exito = $this->daoService->getOfertaDAO()->insertaOferta($curso, $titulo, $descripcion, $usuario_logueado);

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
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($usuario_logueado);
        foreach ($perfil_estudiante as $i => $plan_estudiante) {
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


        //recogemos una posible accion
        $exito_accion = $this->params()->fromRoute('exito');

        return new ViewModel([
            'ofertas' => $ofertas,
            'exito_accion' => $exito_accion,
            'curso' => '2022-23']);
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
            $flag_pertenece = !empty($post['flg']) || $this->daoService->getEstudianteOfertaDAO()->existeAsociacion($usuario_logueado, $cod_oferta);

            if ($accion == 'anular')
                $nuevo_estado = 'Anulado';
            else if ($accion == 'solicitar')
                $nuevo_estado = 'Pendiente';

            if (!empty($nuevo_estado) && $flag_pertenece) {
                $exito = $this->daoService->getEstudianteOfertaDAO()->actualizarEstadoEstudiante($cod_oferta, $nuevo_estado, $usuario_logueado);
            } else {
                $exito = $this->daoService->getEstudianteOfertaDAO()->insertaEstudianteOferta($curso, $cod_oferta, 'Pendiente', $usuario_logueado,);
            }

            //todo meter en sesion   lo que sea
            $this->redirect()->toRoute('trabajos-ofertados/', ['exito' => $exito]);

        }
    }
}
