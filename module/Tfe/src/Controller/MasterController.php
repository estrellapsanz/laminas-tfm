<?php

namespace Tfe\Controller;

use Laminas\Http\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Tfe\Service\DAOServiceInterface;
use Tfe\Service\SessionServiceInterface;
use Tfe\Util\Constantes;

class MasterController extends AbstractActionController
{

    /** @var DAOServiceInterface $daoService */
    protected $daoService;

    /** @var SessionServiceInterface $sesion */
    protected $sesion;

    /**
     * @params DAOServiceInterface $daoService
     * @params SessionServiceInterface $sessionService
     */

    public function __construct(DAOServiceInterface $daoService, SessionServiceInterface $sessionService)
    {
        $this->daoService = $daoService;
        $this->sesion = $sessionService;

        //todo borrar cuando se implemente el AuthController
        //$this->sesion->getSesion();
    }

    public function onDispatch(MvcEvent $e)
    {
        /** @var Request $request */
        return parent::onDispatch($e);
    }

    protected function descargarFicheroAction()
    {

        $this->controlLogueado();

        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post = $request->getPost();

            if (isset($post['cod_solicitud']) && !empty($post['cod_solicitud'])) {

                $sol = $this->daoService->getDepositoDAO()->getSolicitudDeposito($post['cod_solicitud']);
                //PARA PRODUCCIÓN
                $fichero = $sol['RUTA_FICHERO'];
                // $ruta_ficheros = $this->daoService->getParametrosDAO()->dameParametroNombre(Parametro::RUTA_FICHEROS);
                $ruta = __DIR__ . '/../../../../pdf-test.pdf';
                if (file_exists(realpath($ruta))) {
                    while (ob_get_level()) {
                        ob_end_clean();
                    }
                    $fileContents = file_get_contents(realpath($ruta));
                    $response->setContent($fileContents);
                    $headers = $response->getHeaders();
                    $headers->clearHeaders()
                        ->addHeaderLine('Content-Type: application/force-download')
                        ->addHeaderLine('Content-Disposition: attachment; filename=Documento_' . $fichero)
                        ->addHeaderLine('Content-Transfer-Encoding: binary')
                        ->addHeaderLine('Content-Length: ' . filesize($ruta));
                    return $this->response;
                }
            }
        }

        $response = $this->getResponse();
        $response->setContent('Fichero no encontrado');
        return $response;
    }

    /**
     * @return void
     */
    protected function controlLogueado()
    {

        if (!$this->sesion->offsetExists(Constantes::SESION_NOMBRE_USUARIO))
            $this->redirect()->toRoute('desconectar');

    }


    /**
     * @param bool $exito
     * @return void
     */
    protected function informarEstadoOperacionSesion(bool $exito): void
    {
        if ($exito)
            $this->sesion->setEstadoOperacion(Constantes::ESTADO_OPERACION_OK);
        else
            $this->sesion->setEstadoOperacion(Constantes::ESTADO_OPERACION_ERROR);
    }
}
