<?php

declare(strict_types=1);

namespace Tfe\Controller;

use Laminas\View\Model\ViewModel;
use Psr\Http\Message\ResponseInterface;
use Tfe\Util\Constantes;


class IndexController extends MasterController
{

    /**
     * @return ViewModel
     */
    public function homeAction()
    {
        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_HOME_ESTUDIANTE);
        return new ViewModel();
    }

    /**
     * @return void
     */
    public function controlLogueado()
    {

        if (!$this->sesion->offsetExists(Constantes::SESION_NOMBRE_USUARIO))
            $this->redirect()->toRoute('desconectar');

    }

    /**
     * @return ViewModel
     */
    public function miExpedienteAction()
    {

        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_EXPEDIENTE_ESTUDIANTE);

        $login_estudiante = $this->sesion->offsetGet(Constantes::SESION_USUARIO);
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->getPerfilEstudiante($login_estudiante);
        foreach ($perfil_estudiante as &$plan_estudiante) {
            $plan_estudiante['ASIGNATURAS'] = $this->daoService->getEstudianteDAO()->getAsignaturasEstudiante($plan_estudiante['COD_PLAN'], $plan_estudiante['NUMORD']);
        }

        return new ViewModel(['estudiante' => $perfil_estudiante]);
    }

    /**
     * @return ViewModel
     * Solo se podrá proponer una oferta por cada plan que tenga matriculado.
     */
    public function propuestaOfertaAction()
    {

        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_PROPONER_OFERTA_ESTUDIANTE);

        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);
        $curso = $this->daoService->getParametrosDAO()->getParametroNombre(Constantes::PARAMETRO_CURSO_ACADEMICO);
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->getPerfilEstudiante($usuario_logueado);
        $estado_operacion = false;

        /*echo('<pre>');
        var_dump($misPropuestas);
        echo('</pre>');
        die;*/

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();
            $estudios = explode('#', $post['estudios']); //plan, area
            $cod_plan = $estudios[0];
            $area = $estudios[1];
            $titulo = $post['titulo'];
            $subtitulo = $post['subtitulo'];
            $descripcion = $post['descripcion'];

            //controlamos que sólo haya una oferta creada por un estudiante asociada a ese plan, el estado en principio, distinto de Anulado Denegada
            $oferta_existente = $this->daoService->getEstudianteOfertaDAO()->existeAsociacion($usuario_logueado, null, $cod_plan);

            if (!$oferta_existente) {
                $cod_oferta = $this->daoService->getOfertaDAO()->insertOferta($curso, $area, $titulo, $subtitulo, $descripcion, $usuario_logueado, true);

                if ($cod_oferta != -1) {
                    $exito = $this->daoService->getEstudianteOfertaDAO()->insertEstudianteOferta($curso, $cod_oferta, 'Pendiente', $usuario_logueado, $cod_plan);
                    $estado_operacion = $exito;

                }

                $this->informarEstadoOperacionSesion($estado_operacion);
            } else
                $this->informarEstadoOperacionSesion($estado_operacion);

        }

        $misPropuestas = $this->daoService->getOfertaDAO()->getMisOfertasPropuestas($usuario_logueado);

        //solamente si las ofertas están denagadas, se permite proponer una nueva
        $flg_permiso_proponer = true;
        if (!empty($misPropuestas)) {
            foreach ($misPropuestas as $propuesta) {
                if ($propuesta['ESTADO'] != Constantes::ESTADO_OFERTA_DENEGADA) {
                    $flg_permiso_proponer = false;
                }
            }
        }

        return new ViewModel(
            [
                'estudiante' => $perfil_estudiante,
                'propuestas' => $misPropuestas,
                'flg_permiso_proponer' => $flg_permiso_proponer
            ]);
    }


    /**
     * @return ViewModel
     */
    public function solicitudDepositoAction()
    {

        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_SOLICITAR_OFERTA_ESTUDIANTE);


        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);
        $curso = $this->daoService->getParametrosDAO()->getParametroNombre(Constantes::PARAMETRO_CURSO_ACADEMICO);
        $estado_operacion = false;

        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();
            $files = $request->getFiles();
            $flg_normativa = $post['normativa'];

            if ($flg_normativa == 'on') {
                $cod_oferta = $post['cod_oferta'];
                $error_file = $files['archivo']['error'] != 0 ? 1 : 0;

                if (!$error_file) {
                    $ruta_servidor = $this->daoService->getParametrosDAO()->getParametroNombre('RUTA_SERVIDOR');
                    $nombre_fichero = "TFM_COD_" . $cod_oferta . '_CUR_' . $curso . '_USU_' . $usuario_logueado;
                    $extension_fichero = explode('/', $files['archivo']['type']);

                    //todo: mejora implementar servicio subir fichero
                    $ruta_fichero = trim($ruta_servidor . ' /' . $curso . '/' . $nombre_fichero . '.' . $extension_fichero[1]);
                    $cod_deposito = $this->daoService->getDepositoDAO()->insertDeposito($curso, $cod_oferta, $ruta_fichero, $usuario_logueado);

                    if ($cod_deposito > 0) {
                        $estado_operacion = true;
                    }
                }
            }

            $this->informarEstadoOperacionSesion($estado_operacion);

        }


        $perfil_estudiante = $this->daoService->getEstudianteDAO()->getPerfilEstudiante($usuario_logueado);
        $misOfertasValidadas = $this->daoService->getEstudianteOfertaDAO()->getMisOfertasVigentes($usuario_logueado);
        $misDepositos = $this->daoService->getDepositoDAO()->getMisDepositos($curso, $usuario_logueado);

        return new ViewModel(
            [
                'estudiante' => $perfil_estudiante,
                'misOfertas' => $misOfertasValidadas,
                'misDepositos' => $misDepositos
            ]);

    }

    /**
     * @return ViewModel
     */
    public function trabajosOfertadosAction()
    {


        $this->controlLogueado();
        $this->sesion->setUrlInSession(Constantes::RUTA_TRABAJOS_OFERTADOS);


        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);

        //inicialización de variables
        $ofertas = [];
        $exito_accion = null;

        $curso = $this->daoService->getParametrosDAO()->getParametroNombre('CURSO_ACADEMICO');
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->getPerfilEstudiante($usuario_logueado);

        foreach ($perfil_estudiante as $i => $plan_estudiante) {
            $codigo_plan = $plan_estudiante['COD_PLAN'];
            $datos_plan = $this->daoService->getDatosAcademicosDAO()->getDatosPlan($codigo_plan);
            $planes_modal[$i]['COD_PLAN'] = $codigo_plan;
            $planes_modal[$i]['NOMBRE_PLAN'] = !empty($datos_plan) ? $datos_plan['NOMBRE_PLAN'] : 'DESCONOCIDO';

            if (!isset($ofertas[$plan_estudiante['NOMBRE_AREA']])) {
                $ofertas[$plan_estudiante['NOMBRE_AREA']] = $this->daoService->getOfertaDAO()->getOfertasArea($plan_estudiante['COD_AREA']);
            }
        }

        if (!empty($ofertas)) {
            foreach ($ofertas as &$ofertas_area) {
                foreach ($ofertas_area as &$oferta) {
                    //un estudiante podrá editar una oferta si la ha creado él y un docente aún no le tutoriza
                    $oferta['FLG_EDITAR'] = ($oferta['USUARIO_ESTUDIANTE'] == $usuario_logueado) && ($oferta['ESTADO_ESTUDIANTE'] != 'Anulado' && !empty($oferta['USUARIO_ESTUDIANTE'])) ? 1 : 0;
                }
            }
        }

        return new ViewModel([
            'flg_elegir_plan' => sizeof($planes_modal) > 1,
            'planes_estudiante' => $planes_modal,
            'ofertas' => $ofertas,
            'curso' => $curso
        ]);
    }

    /**
     * Se podrán solicitar tantas ofertas como se quiera, y luego el profesor que primero la coja, anularía el resto de forma automática.
     * @return void
     */
    public function guardarSolicitudOfertaAction()
    {


        $this->controlLogueado();
        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);

        //inicialización de variables
        $nuevo_estado = null;

        $curso = $this->daoService->getParametrosDAO()->getParametroNombre('CURSO_ACADEMICO');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();

            $cod_oferta = $post['cod_oferta'];
            $accion = $post['accion'];
            $plan_trabajo = $post['plan_trabajo'];

            //se podrá editar la oferta si pertenece al estudiante
            $flag_pertenece = !empty($post['flg']) || $this->daoService->getEstudianteOfertaDAO()->existeAsociacion($usuario_logueado, $cod_oferta, $plan_trabajo);

            if ($accion == Constantes::ACCION_ANULAR_OFERTA)
                $nuevo_estado = Constantes::ESTADO_ESTUDIANTE_ANULADO;
            else if ($accion == Constantes::ACCION_SOLICITAR_OFERTA)
                $nuevo_estado = Constantes::ESTADO_ESTUDIANTE_PENDIENTE;

            if (!empty($nuevo_estado) && $flag_pertenece) {

                //update estado asociacion estudiante-oferta
                $exito = $this->daoService->getEstudianteOfertaDAO()->updateEstadoEstudiante($cod_oferta, $nuevo_estado, $usuario_logueado);

                //si se anula la oferta, se libera para otro estudiante
                if ($nuevo_estado == Constantes::ESTADO_ESTUDIANTE_ANULADO) {
                    $exito2 = $this->daoService->getOfertaDAO()->updateEstado($cod_oferta, Constantes::ESTADO_OFERTA_VIGENTE);
                }
                $estado_operacion = !isset($exito2) ? $exito : $exito && $exito2;

            } else {
                //insert asociación estudiante - oferta
                $estado_operacion = $this->daoService->getEstudianteOfertaDAO()->insertEstudianteOferta($curso, $cod_oferta, 'Pendiente', $usuario_logueado, $plan_trabajo);
            }

            $this->informarEstadoOperacionSesion($estado_operacion);
            if ($this->sesion->getUrlInSession() == Constantes::RUTA_TRABAJOS_OFERTADOS)
                $this->redirect()->toRoute('trabajos-ofertados');
            else
                $this->redirect()->toRoute('propuesta-oferta');

        }
    }


    public function getDatosOfertaAjaxAction()
    {
        $this->controlLogueado();
        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);

        $request = $this->getRequest();
        $response = $this->getResponse();

        if ($request->isPost()) {
            $post = $request->getPost();
            $cod_oferta = $post['cod_oferta'];

            //se podrá solicitar el deposito si la oferta  pertenece al estudiante
            $flag_pertenece = $this->daoService->getEstudianteOfertaDAO()->existeAsociacion($usuario_logueado, $cod_oferta);

            if (!empty($cod_oferta) && $flag_pertenece) {
                $oferta = $this->daoService->getOfertaDAO()->getOferta($cod_oferta);
                $data = [
                    'DESCRIPCIÓN' => $oferta['DESCRIPCION'],
                    'DOCENTE' => $oferta['USUARIO_DOCENTE']
                ];

                $response->setStatusCode(200);
                $response->setContent(json_encode($data));
                $headers = $response->getHeaders();
                $headers->addHeaderLine('Content-Type', 'application/json');
                return $response;
            }
        }

    }
}
