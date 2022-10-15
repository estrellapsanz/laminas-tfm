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

    public function welcomeAction()
    {
        //var_dump(getenv('APPLICATION_ENV'));DIE;
        $estudiante = $this->daoService->getEstudianteDAO()->getEstudiante('estrella.parrilla');
        return new ViewModel(['estudiante' => $estudiante]);
    }

    public function propuestaOfertaAction()
    {
        $estudiante = $this->daoService->getEstudianteDAO()->getEstudiante('estrella.parrilla');

        $request = $this->getRequest();
        $post = $request->getPost();
        return new ViewModel(['estudiante' => $estudiante]);
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
        var_dump($post);
        die;
    }
}
