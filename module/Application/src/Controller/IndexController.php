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

    public function solicitudAction()
    {

        $request = $this->getRequest();
        $post = $request->getPost();
        var_dump($post);
        die;
    }
}
