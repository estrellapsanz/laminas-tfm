<?php

declare(strict_types=1);

namespace Tfe\Controller;

use Laminas\View\Model\ViewModel;
use Tfe\Model\Entity\Parametro;
use Tfe\Model\Entity\ParametroPsico;
use Tfe\Util\Constantes;


class AuthController extends MasterController
{

    /**
     * @return ViewModel
     */
    public function loginAction()
    {

        $request = $this->getRequest();
        $post = ($request->isPost()) ? $request->getPost() : null;

        $uri = $request->getUri()->getPath();
        $docente = strpos($uri, 'docente') !== false;

        if ($docente) {
            $texto = 'Login docente';
            $this->sesion->offsetSet(Constantes::SESION_DOCENTE, true);
            $this->sesion->offsetSet(Constantes::SESION_ESTUDIANTE, false);

        } else {
            $texto = 'Login estudiante';
            $this->sesion->offsetSet(Constantes::SESION_DOCENTE, false);
            $this->sesion->offsetSet(Constantes::SESION_ESTUDIANTE, true);
        }


        if ($request->isPost()) {

            $post = $request->getPost();

            if (isset($post['email']) && isset($post['password'])) {

                $email = strtolower(trim($post['email']));
                $pass = $post['password'];

                if (!empty($email) && !empty($pass)) {
                    $usuario = explode('@', $email);

                    if ($this->sesion->offsetGet(Constantes::SESION_DOCENTE))
                        $nombre = $this->daoService->getDocenteDAO()->dameNombre(strtolower(trim($usuario[0])));
                    else
                        $nombre = $this->daoService->getEstudianteDAO()->getNombre(strtolower(trim($usuario[0])));

                    if (!empty($nombre)) {
                        $this->sesion->regenerateId();
                        $this->sesion->offsetSet(Constantes::SESION_NOMBRE_USUARIO, $nombre);
                        if ($docente) {
                            $this->sesion->offsetSet(Constantes::SESION_USUARIO_DOCENTE, $usuario[0]);
                            return $this->redirect()->toRoute(Constantes::RUTA_TRABAJOS_TUTORIZADOS_DOCENTE);
                        } else {
                            $this->sesion->offsetSet(Constantes::SESION_USUARIO, $usuario[0]);
                            return $this->redirect()->toRoute(Constantes::RUTA_HOME_ESTUDIANTE);
                        }
                    } else {
                        $this->sesion->offsetSet('estado_operacion', Constantes::ESTADO_OPERACION_ERROR);
                        $this->sesion->offsetSet('error', Constantes::ERROR_PETICION_LOGIN);
                    }
                }
            }


        } else
            $this->controlErrores();


        return new ViewModel(['texto' => $texto]);
    }


    /*
     * Función para desconectar (borrar la cookie)
     */

    private function controlErrores()
    {
        //Comprobamos si al hacer un login erroneo, guardamos las operaciones antes de que sean destruidas

        if ($this->sesion->offsetExists('estado_operacion'))
            $eo = $this->sesion->offsetGet('estado_operacion');
        if ($this->sesion->offsetExists('error'))
            $e = $this->sesion->offsetGet('error');

        $estudiante = $this->sesion->offsetGet(Constantes::SESION_ESTUDIANTE);
        $docente = $this->sesion->offsetGet(Constantes::SESION_DOCENTE);

        // destruimos la sesion
        $this->sesion->offsetUnset('estado_operacion');
        $this->sesion->offsetUnset('error');
        $this->sesion->offsetUnset(Constantes::SESION_USUARIO);
        $this->sesion->offsetUnset(Constantes::SESION_USUARIO_DOCENTE);
        $this->sesion->offsetUnset(Constantes::SESION_NOMBRE_USUARIO);
        $this->sesion->offsetUnset(Constantes::CURRENT_URL);


        // al destruir la sesion si hubo algún error los volvemos a recuperar, y creamos los estados en la sesió
        if (!empty($eo)) {
            $this->sesion->estado_operacion = $eo;
        }
        if (!empty($e)) {
            $this->sesion->error = $e;
        }
        if (!empty($estudiante)) {
            $this->sesion->offsetSet(Constantes::SESION_ESTUDIANTE, $estudiante);
        }
        if (!empty($docente)) {
            $this->sesion->offsetSet(Constantes::SESION_DOCENTE, $docente);
        }
    }


    /*
     * Función auxiliar para controlar todo lo almacenado en sesion
     */

    public function desconectarAction()
    {
        $this->controlErrores();
        //Borramos la cookie
        //$this->getResponse()->getHeaders()->addHeader(new SetCookie(Constantes::NOMBRE_COOKIE, "", time() - 1, "/", Constantes::DOMINIO_COOKIE));
        //Redireccionamos
        return $this->redirect()->toRoute("login");
    }


}
