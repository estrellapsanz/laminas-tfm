<?php

declare(strict_types=1);

namespace Tfe\Controller;

use Laminas\View\Model\ViewModel;
use Tfe\Util\Constantes;


class IndexController extends MasterController
{

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function miPerfilAction()
    {

        $this->informarSesion();

        $login_estudiante = 'estrella.parrilla';
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($login_estudiante);
        foreach ($perfil_estudiante as &$plan_estudiante) {
            $plan_estudiante['ASIGNATURAS'] = $this->daoService->getEstudianteDAO()->dameAsignaturasEstudiante($plan_estudiante['COD_PLAN'], $plan_estudiante['NUMORD']);
        }

        return new ViewModel(['estudiante' => $perfil_estudiante]);
    }

    //todo borrar cuando se implemente AuthController
    public function informarSesion()
    {
        $this->sesion->offsetSet(Constantes::SESION_USUARIO, 'estrella.parrilla');
        $this->sesion->offsetSet(Constantes::SESION_NOMBRE_USUARIO, 'Estrella Parrilla Sanz');
        $this->sesion->offsetSet(Constantes::SESION_ESTUDIANTE, true);
        $this->sesion->offsetSet(Constantes::SESION_DOCENTE, false);

    }

    /**
     * @return ViewModel
     */
    public function propuestaOfertaAction()
    {
        //todo borrar al implementar AuthController
        $this->informarSesion();

        //inicialización de variables
        $misPropuestas = $exito = null;

        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);
        $curso = $this->daoService->getParametrosDAO()->dameParametroNombre('CURSO_ACADEMICO');
        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($usuario_logueado);

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
                    $exito = $this->daoService->getEstudianteOfertaDAO()->insertaEstudianteOferta($curso, $cod_oferta, 'Pendiente', $usuario_logueado, $cod_plan);
                    $this->sesion->setEstadoOperacion($exito);
                }
            } else
                $this->sesion->setEstadoOperacion(false);

        }

        $misPropuestas = $this->daoService->getOfertaDAO()->dameMisOfertasPropuestas($usuario_logueado);

        return new ViewModel(
            [
                'estudiante' => $perfil_estudiante,
                'propuestas' => $misPropuestas
            ]);
    }

    /**
     * @return ViewModel
     */
    public function solicitudDepositoAction()
    {
        //todo borrar cuando se implemente AuthController
        $this->informarSesion();
        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);

        //inicialización de variables
        $misOfertasValidadas = $cod_deposito = null;
        $curso = $this->daoService->getParametrosDAO()->dameParametroNombre('CURSO_ACADEMICO');


        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();
            $files = $request->getFiles();
            $flg_normativa = $post['normativa'];

            if ($flg_normativa == 'on') {
                $cod_oferta = $post['cod_oferta'];
                $error_file = $files['archivo']['error'] != 0 ? 1 : 0;

                if (!$error_file) {
                    $ruta_servidor = $this->daoService->getParametrosDAO()->dameParametroNombre('RUTA_SERVIDOR');
                    $nombre_fichero = "TFM_COD_" . $cod_oferta . '_CUR_' . $curso . '_USU_' . $usuario_logueado;
                    $extension_fichero = explode('/', $files['archivo']['type']);

                    //todo: mejora implementar servicio subir fichero
                    $ruta_fichero = trim($ruta_servidor . ' /' . $curso . '/' . $nombre_fichero . '.' . $extension_fichero[1]);
                    $cod_deposito = $this->daoService->getDepositoDAO()->insertaDeposito($curso, $cod_oferta, $ruta_fichero, $usuario_logueado);

                    if ($cod_deposito == -1) {
                        //todo redirect error
                    }

                }

            } else {
                //todo redirect error
            }
        }


        $perfil_estudiante = $this->daoService->getEstudianteDAO()->damePerfilEstudiante($usuario_logueado);
        $misOfertasValidadas = $this->daoService->getEstudianteOfertaDAO()->dameMisOfertasVigentes($usuario_logueado);
        $misDepositos = $this->daoService->getDepositoDAO()->dameMisDepositos($curso, $usuario_logueado);

        return new ViewModel(
            [
                'estudiante' => $perfil_estudiante,
                'misOfertas' => $misOfertasValidadas,
                'misSolicitudes' => $misDepositos
            ]);


    }

    public function trabajosOfertadosAction()
    {

        //todo borrar cuando se implemente AuthController
        $this->informarSesion();
        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);

        //inicialización de variables
        $ofertas = [];
        $exito_accion = null;

        $curso = $this->daoService->getParametrosDAO()->dameParametroNombre('CURSO_ACADEMICO');
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

        return new ViewModel([
            'flg_elegir_plan' => sizeof($planes_modal) > 1,
            'planes_estudiante' => $planes_modal,
            'ofertas' => $ofertas,
            'curso' => $curso
        ]);
    }


    public function guardarSolicitudOfertaAction()
    {

        //todo borrar cuando se implemente AuthController
        $this->informarSesion();
        $usuario_logueado = $this->sesion->offsetGet(Constantes::SESION_USUARIO);

        //inicialización de variables
        $nuevo_estado = null;

        $curso = $this->daoService->getParametrosDAO()->dameParametroNombre('CURSO_ACADEMICO');
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

                if ($exito != -1 && $exito2 != -1)
                    $this->sesion->setEstadoOperacion(1);
                else
                    $this->sesion->setEstadoOperacion(0);
            } else {
                //insert asociación estudiante - oferta
                $exito = $this->daoService->getEstudianteOfertaDAO()->insertaEstudianteOferta($curso, $cod_oferta, 'Pendiente', $usuario_logueado, $plan_trabajo);
                $this->sesion->setEstadoOperacion($exito);
            }


            //todo meter en sesion   lo que sea
            $this->redirect()->toRoute('propuesta-oferta');

        }
    }
}
