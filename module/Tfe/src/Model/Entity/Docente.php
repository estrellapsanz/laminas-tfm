<?php

namespace Tfe\Model\Entity;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;


class Docente extends MasterEntity
{
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResulPrototype = null)
    {
        return parent::__construct('XXX', $adapter, $databaseSchema, $selectResulPrototype);
    }


    /**
     * @param $user
     * @return false|mixed
     */
    public function getNombre($user)
    {

        $query = "SELECT * FROM TFM_DOCENTE WHERE USUARIO=:P_USER";
        $rs = $this->executeQueryRow($query, [':P_USER' => $user]);

        if (!empty($rs))
            return $rs['NOMBRE'] . ' ' . $rs['APELLIDO1'] . ' ' . $rs['APELLIDO2'];

        return null;

    }


    /**
     * Devuelve las ofertas de un docente, y las creadas por estudiantes y que aún no tienen tutor asociado
     * @param $user
     * @return array
     */
    public function getOfertasDocente($user)
    {
        $query = "  SELECT 
                    OFE.*, 
                    ALU.COD_PLAN,
                    ALU.ESTADO AS ESTADO_ESTUDIANTE, 
                    ALU.USUARIO_ESTUDIANTE, 
                    (SELECT P.NOMBRE_PLAN FROM TFM_AREA A, TFM_PLANES P WHERE P.COD_PLAN=ALU.COD_PLAN AND P.COD_AREA=A.COD_AREA) AS NOMBRE_PLAN,        
                    ALU.OBSERVACIONES_TRAMITACION,
                 
                    CASE
                        WHEN ALU.COD_PLAN IS NOT NULL THEN  (SELECT A.NOMBRE_AREA FROM TFM_AREA A, TFM_PLANES P WHERE P.COD_PLAN=ALU.COD_PLAN AND P.COD_AREA=A.COD_AREA) 
                        ELSE (SELECT A.NOMBRE_AREA FROM TFM_AREA A WHERE  OFE.COD_AREA=A.COD_AREA)
                    END AS NOMBRE_AREA,
                    (SELECT CONCAT(E.NOMBRE,' ',E.APELLIDO1,' ',E.APELLIDO2) FROM TFM_ESTUDIANTE E WHERE E.USUARIO=ALU.USUARIO_ESTUDIANTE) AS NOMBRE_ESTUDIANTE
                
                     FROM 
                    (
                        SELECT O.CURSO_ACADEMICO, upper(O.TITULO) TITULO, upper(O.SUBTITULO) SUBTITULO, upper(O.DESCRIPCION) DESCRIPCION, O.COD_OFERTA, O.ESTADO,
                               O.USUARIO_DOCENTE, O.COD_AREA
                        FROM 
                          TFM_OFERTAS O
                        WHERE 
                              O.USUARIO_DOCENTE=:P_USER   
                    )  OFE LEFT JOIN   TFM_ESTUDIANTE_OFERTA ALU ON 
                    OFE.COD_OFERTA=ALU.COD_OFERTA
                    
                    UNION
                    
                  
                     SELECT
                    OFE.*, 
                    ALU.COD_PLAN, 
                    ALU.ESTADO AS ESTADO_ESTUDIANTE, 
                    ALU.USUARIO_ESTUDIANTE, 
                    (SELECT P.NOMBRE_PLAN FROM TFM_AREA A, TFM_PLANES P WHERE P.COD_PLAN=ALU.COD_PLAN AND P.COD_AREA=A.COD_AREA) AS NOMBRE_PLAN,        
                    ALU.OBSERVACIONES_TRAMITACION,
            
                     (SELECT A.NOMBRE_AREA FROM TFM_AREA A, TFM_PLANES P WHERE P.COD_PLAN=ALU.COD_PLAN AND P.COD_AREA=A.COD_AREA) AS NOMBRE_AREA,
                     (SELECT CONCAT(E.NOMBRE,' ',E.APELLIDO1,' ',E.APELLIDO2) FROM TFM_ESTUDIANTE E WHERE E.USUARIO=ALU.USUARIO_ESTUDIANTE) AS NOMBRE_ESTUDIANTE
                
                     FROM 
                    (
                        SELECT O.CURSO_ACADEMICO, upper(O.TITULO) TITULO, upper(O.SUBTITULO) SUBTITULO, upper(O.DESCRIPCION) DESCRIPCION, O.COD_OFERTA, O.ESTADO,  
                               O.USUARIO_DOCENTE, O.COD_AREA
                               
                        FROM 
                          TFM_OFERTAS O
                        WHERE 
                              O.USUARIO_DOCENTE IS NULL AND 
                              O.ESTADO='Pendiente'          
                    )  OFE , 
                    TFM_ESTUDIANTE_OFERTA ALU
                    WHERE
                    OFE.COD_OFERTA=ALU.COD_OFERTA AND
                    ALU.ESTADO='Pendiente'
                    ";
        return $this->executeQueryArray($query, [':P_USER' => $user]);

    }


    public function getSolicitudesDeposito($usuario_docente)
    {

        $query = "SELECT 
                   DEF.CURSO_ACADEMICO, DEF.USUARIO_ESTUDIANTE,DEF.COD_SOLICITUD,DEF.OBSERVACIONES,
                   DEF.NOTA_FINAL, OFE.TITULO, DEF.ESTADO AS ESTADO_DEPOSITO,
                   P.NOMBRE_PLAN, P.COD_PLAN, A.NOMBRE_AREA, ALU.COD_PLAN, ES.NOMBRE, ES.APELLIDO2 , ES.APELLIDO1,
                   ES.USUARIO USUARIO_ESTUDIANTE, DEF.NOTA_FINAL, DEF.COD_OFERTA, DEF.RUTA_FICHERO
                    
                FROM 
                    TFM_SOLICITUD_DEFENSA DEF, 
                    TFM_OFERTAS OFE ,
                    TFM_ESTUDIANTE ES ,
                    TFM_ESTUDIANTE_OFERTA ALU ,
                    TFM_AREA A,
                    TFM_PLANES P 
                WHERE 
                    DEF.COD_OFERTA=OFE.COD_OFERTA AND
                    OFE.USUARIO_DOCENTE=:P_USUARIO AND
                    DEF.USUARIO_ESTUDIANTE=ES.USUARIO AND
                    DEF.USUARIO_ESTUDIANTE=ALU.USUARIO_ESTUDIANTE AND
                    ALU.COD_OFERTA=OFE.COD_OFERTA AND
                    ALU.COD_PLAN=P.COD_PLAN AND
                    P.COD_AREA=A.COD_AREA";
        return $this->executeQueryArray($query, [':P_USUARIO' => $usuario_docente]);

    }


    public function getMisTrabajosCalificados($usuario)
    {
        $query = "SELECT
                    DEF.NOTA_FINAL,
                    DEF.CURSO_ACADEMICO, 
                    DEF.USUARIO_ESTUDIANTE, 
                    OFE.TITULO, 
                    ES.NOMBRE, 
                    ES.APELLIDO1, 
                    ES.APELLIDO2, 
                    P.COD_PLAN, 
                    P.NOMBRE_PLAN,
                    A.NOMBRE_AREA
                FROM
                    TFM_SOLICITUD_DEFENSA DEF,
                    TFM_OFERTAS OFE,
                     TFM_ESTUDIANTE ES, 
                     TFM_ESTUDIANTE_OFERTA ALU,
                     TFM_PLANES P, TFM_AREA A
                WHERE
                    OFE.USUARIO_DOCENTE=:P_USER AND
                    OFE.COD_OFERTA=DEF.COD_OFERTA AND
                    DEF.NOTA_FINAL IS NOT NULL AND
                    DEF.USUARIO_ESTUDIANTE=ES.USUARIO AND
                    ES.USUARIO=ALU.USUARIO_ESTUDIANTE AND
                    DEF.COD_OFERTA=OFE.COD_OFERTA AND
                    DEF.COD_OFERTA=ALU.COD_OFERTA AND
                    ALU.COD_PLAN=P.COD_PLAN AND
                    P.COD_AREA=A.COD_AREA";
        return $this->executeQueryArray($query, [':P_USER' => $usuario]);
    }
}
