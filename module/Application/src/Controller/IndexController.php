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
        $estudiante = 'estrella.parrilla';
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($estudiante);
        $request = $this->getRequest();
        $post = $request->getPost();
        return new ViewModel(['estudiante' => $perfil_estudiante]);
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
            foreach ($ofertas as $area => &$ofertas_area) {
                foreach ($ofertas_area as &$oferta) {
                    $oferta['FLG_EDITAR'] = ($oferta['USUARIO_ESTUDIANTE'] == $usuario_logueado && $oferta['ESTADO_ESTUDIANTE'] != 'Anulado');
                }
            }
        }

        //recogemos una posible accion
        $exito_accion = $this->params('exito');

        return new ViewModel([
            'ofertas' => $ofertas,
            'exito_accion' => $exito_accion,
            'curso' => '2022-23']);
    }


    public function guardarSolicitudOfertaAction()
    {

        //$this->sesion->getSesion();

        $usuario_logueado = 'estrella.parrilla';
        $nuevo_estado = null;
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();
            $cod_oferta = $post['cod_oferta'];
            $accion = $post['accion'];

            if ($accion == 'anular')
                $nuevo_estado = 'Anulado';
            else if ($accion == 'solicitar')
                $nuevo_estado = 'Pendiente';

            if (!empty ($nuevo_estado)) {
                //todo mirar si la oferta pertenece ya al estudiante, si si updateo, sino insser
                $exito = $this->daoService->getEstudianteOfertaDAO()->actualizarEstadoEstudiante($cod_oferta, $nuevo_estado, $usuario_logueado);
            }


            //todo meter en sesion   lo que sea
            //$this->redirect()->toRoute('trabajos-ofertados', ['exito' => $exito]);

            $this->redirect()->toRoute('trabajos-ofertados');


        }
    }
}
