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

        $request = $this->getRequest();
        $post = $request->getPost();
        $login_estudiante = 'estrella.parrilla';
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($login_estudiante);
        foreach ($perfil_estudiante as &$plan_estudiante) {
            $plan_estudiante['ASIGNATURAS'] = $this->daoService->getEstudianteDAO()->dameAsignaturasEstudiante($plan_estudiante['COD_PLAN'], $plan_estudiante['NUMORD']);
        }


        return new ViewModel(['estudiante' => $perfil_estudiante]);
    }
}
