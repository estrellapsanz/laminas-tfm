<?php

declare(strict_types=1);

namespace Tfe\Controller;

use Laminas\View\Model\ViewModel;
use Tfe\Util\Constantes;


class DocenteController extends MasterController
{

    /**
     * @return ViewModel
     */
    public function altaOfertaAction()
    {
        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_ALTA_OFERTA_DOCENTE);
        $login_docente = $this->sesion->offsetGet(Constantes::SESION_USUARIO_DOCENTE);
        $curso = $this->daoService->getParametrosDAO()->dameParametroNombre(Constantes::PARAMETRO_CURSO_ACADEMICO);
        $request = $this->getRequest();

        $resultado = $oferta = null;
        $insert = $update = false;

        if ($request->isPost()) {

            $post = $request->getPost();
            $titulo = isset($post['titulo']) ? $post['titulo'] : null;
            $descripcion = isset($post['descripcion']) ? $post['descripcion'] : null;
            $subtitulo = isset($post['subtitulo']) ? $post['subtitulo'] : null;
            $area = isset($post['area']) ? $post['area'] : null;
            $cod_oferta = isset($post['cod_oferta']) ? $post['cod_oferta'] : null;
            $flg_editar = $post['flg_editar'];
            //var_dump('<pre>');
            // var_dump($post);
            // die;
            //var_dump('</pre>');
            //die;


            //alta oferta
            if (empty($cod_oferta) && !$flg_editar) {
                $insert = $this->daoService->getOfertaDAO()->insertaOferta($curso, $titulo, $subtitulo, $descripcion, $login_docente);
                $resultado = $insert > 0;

            } else {
                if (!$flg_editar) {
                    //editar oferta desde button editar-oferta
                    $oferta = $this->daoService->getOfertaDAO()->dameOferta($cod_oferta);
                } else {
                    //update oferta desde alta-oferta
                    $update = $this->daoService->getOfertaDAO()->actualizaOferta($cod_oferta, $titulo, $subtitulo, $descripcion, $area);
                    $resultado = $update;

                }
            }
        }


        if ($insert || $update) {
            $this->informarEstadoOperacionSesion($resultado);
            $this->redirect()->toRoute('docente-trabajos-tutorizados');
        }
        $areas = $this->daoService->getAreasDAO()->getAreas();
        return new ViewModel(['areas' => $areas, 'oferta' => $oferta]);
    }


    /**
     * @param bool $exito
     * @return void
     */
    public function informarEstadoOperacionSesion(bool $exito): void
    {
        if ($exito)
            $this->sesion->setEstadoOperacion(Constantes::ESTADO_OPERACION_OK);
        else
            $this->sesion->setEstadoOperacion(Constantes::ESTADO_OPERACION_ERROR);
    }

    /**
     * @return ViewModel
     */
    public function solicitudesDepositoAction()
    {
        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_SOLICITUDES_DEPOSITO_DOCENTE);
        $login_docente = $this->sesion->offsetGet(Constantes::SESION_USUARIO_DOCENTE);
        $solicitudesDepósito = $this->daoService->getDocenteDAO()->getSolicitudesDeposito($login_docente);
        return new ViewModel(['solicitudes' => $solicitudesDepósito]);
    }

    /**
     * @return ViewModel
     */
    public function trabajosTutorizadosAction()
    {
        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_TRABAJOS_TUTORIZADOS_DOCENTE);
        $login_docente = $this->sesion->offsetGet(Constantes::SESION_USUARIO_DOCENTE);
        $trabajos = $this->daoService->getDocenteDAO()->dameOfertasDocente($login_docente);
        return new ViewModel(['trabajos' => $trabajos]);
    }

    /**
     * @return ViewModel
     */
    public function trabajosCalificadosAction()
    {
        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_TRABAJOS_CALIFICADOS_DOCENTE);
        $login_docente = $this->sesion->offsetGet(Constantes::SESION_USUARIO_DOCENTE);
        $trabajos = $this->daoService->getDocenteDAO()->getMisTrabajosCalificados($login_docente);

        return new ViewModel(['trabajos' => $trabajos]);
    }

    public function guardarTramitarEstudianteOfertaAction()
    {
        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_TRABAJOS_TUTORIZADOS_DOCENTE);
        $login_docente = $this->sesion->offsetGet(Constantes::SESION_USUARIO_DOCENTE);

        $request = $this->request;

        if ($request->isPost()) {

            $post = $request->getPost();
            $cod_oferta = $post['cod_oferta'];
            $estudiante = $post['estudiante'];
            $estado = $post['estado'];

            $update = $this->daoService->getEstudianteOfertaDAO()->actualizarEstadoEstudiante($cod_oferta, $estado, $estudiante);
            $this->informarEstadoOperacionSesion($update);
        }
        return $this->redirect()->toRoute('docente-trabajos-tutorizados');
    }

    public function guardarTramitarEstudianteDepositoAction()
    {
        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_SOLICITUDES_DEPOSITO_DOCENTE);
        $login_docente = $this->sesion->offsetGet(Constantes::SESION_USUARIO_DOCENTE);
        $curso = $this->daoService->getParametrosDAO()->dameParametroNombre(Constantes::PARAMETRO_CURSO_ACADEMICO);

        $request = $this->request;

        if ($request->isPost()) {

            $post = $request->getPost();
            $cod_oferta = $post['cod_oferta'];
            $cod_solicitud = $post['cod_solicitud'];
            $nota_final = $post['nota_final'];
            $observaciones = substr($post['observaciones'], 0, 499);
            $accion = $post['accion'];

            if ($accion == 'autorizar')
                $estado = Constantes::ESTADO_DEPOSITO_AUTORIZADO;
            else if ($accion == 'denegar')
                $estado = Constantes::ESTADO_DEPOSITO_DENEGADO;
            else if ($accion == 'cambios')
                $estado = Constantes::ESTADO_DEPOSITO_CAMBIOS_SOLICITADOS;
            else
                $estado = Constantes::ESTADO_DEPOSITO_PENDIENTE;


            //todo_ hasta meter transacciones, hay que hacer una comprobación extra para no hacer commit
            if ($estado == Constantes::ESTADO_DEPOSITO_AUTORIZADO && !empty($nota_final))
                $update1 = $this->daoService->getDepositoDAO()->actualizaEstado($curso, $cod_solicitud, $cod_oferta, $estado);

            // todo actualizar la linea de matricula
            else if ($estado == Constantes::ESTADO_DEPOSITO_AUTORIZADO && empty($nota_final))
                $update1 = false;
            else
                $update1 = $this->daoService->getDepositoDAO()->actualizaEstado($curso, $cod_solicitud, $cod_oferta, $estado);

            if (!empty($nota_final))
                $update2 = $this->daoService->getDepositoDAO()->actualizaNota($curso, $cod_solicitud, $cod_oferta, $nota_final);

            if (!empty($observaciones))
                $update2 = $this->daoService->getDepositoDAO()->actualizaObservaciones($curso, $cod_solicitud, $cod_oferta, $observaciones);

            $this->informarEstadoOperacionSesion($update1 && $update2);
        }
        return $this->redirect()->toRoute('docente-solicitudes-deposito');
    }

}
