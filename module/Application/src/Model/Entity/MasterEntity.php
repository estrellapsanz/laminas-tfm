<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Model\Entity;


use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Expression;
use Laminas\Db\TableGateway\TableGateway;

class MasterEntity extends TableGateway
{

    /**
     * MasterEntity constructor.
     */
    public function __construct($table, AdapterInterface $adapter, $features = null, ResultSetInterface $resultSetPrototype = null)
    {
        parent::__construct($table, $adapter, $features, $resultSetPrototype);
    }

    /**
     * @param array $set
     * @param string|array|\Closure $where
     * @param null|array $joins
     * @return int
     */
    public function update($set, $where = null, array $joins = null)
    {
        /* if ($this->userExist()) {
             $set['FECHA_MODIFICACION'] = $this->formatTimeStamp();
             $set['ID_USULDAP_MOD'] = $this->user;
         }*/
        return parent::update($set, $where);
    }

    /**
     * @param string $query
     * @param null|array $params
     * @return array
     */
    protected function executeQueryArray($query, $params = null)
    {
        if (!empty($query)) {
            /** @var Adapter $db */
            $db = $this->adapter;
            $stmt = $db->query($query);
            $resultSet = $stmt->execute($params);
            $returnArray = [];
            foreach ($resultSet as $result) {
                $returnArray[] = $result;
            }
            return $returnArray;
        } else {
            return [];
        }
    }

    /**
     * @param string $query
     * @param null|array $params
     * @return bool
     */
    protected function executeQueryStmt($query, $params = null)
    {
        if (!empty($query)) {
            /** @var Adapter $db */
            $db = $this->adapter;
            $stmt = $db->query($query);
            $exec = $stmt->execute($params);
            return $exec->getAffectedRows() > 0;
        } else {
            return false;
        }
    }

    /**
     * @param string $query
     * @param null|array $params
     * @return mixed
     */
    protected function executeQueryRow($query, $params = null)
    {
        if (!empty($query)) {
            $db = $this->adapter;
            /** @var Adapter $db */
            $stmt = $db->query($query);
            $resultSet = $stmt->execute($params);
            return $resultSet->current();
        } else {
            return false;
        }
    }

    /**
     * @param string $query
     * @param null|array $params
     * @return ResultSet
     */
    protected function executeQueryIterator($query, $params = null)
    {
        if (!empty($query)) {
            /** @var Adapter $db */
            $db = $this->adapter;
            $stmt = $db->query($query);
            $result = $stmt->execute($params);
            $resultSet = new ResultSet;
            $resultSet->initialize($result);
            return $resultSet;
        } else {
            return null;
        }
    }

    /**
     * @param string $fecha
     * @return Expression
     */
    protected function formatTimeStamp($fecha = null)
    {
        $fecha = !empty($fecha) ? date(Constantes::FORMATO_FECHA_ORACLE_TIMESTAMP, strtotime($fecha)) : date(Constantes::FORMATO_FECHA_ORACLE_TIMESTAMP);
        return new Expression("TO_DATE('" . str_replace('-', '/', $fecha) . "','" . Constantes::MASCARA_FECHA_ORACLE_TIMESTAMP . "')");
    }

    /**
     * @param string $fecha
     * @return Expression
     */
    protected function formatDateOracle($fecha)
    {
        return new Expression("TO_DATE('" . $fecha . "', '" . Constantes::MASCARA_FECHA_DATE_ORACLE . "')");
    }

    /**
     * formatParamNumberToSql y formatNumberToSql para consultas sql por parametro
     * @param $param
     * @return string|null
     */
    protected function formatParamNumberToSql($param)
    {
        if ($param === '' || $param == null)
            return null;
        else
            return "TO_NUMBER('" . $this->formatNumberToSql($param) . "', '9999999D00', 'NLS_NUMERIC_CHARACTERS='',.''')";
    }

    /**
     * @param int|string|null $numero
     * @return float
     */
    protected function formatNumberToSql($numero)
    {
        return str_replace('.', ',', $this->formatNumberToPhp($numero));
    }

    /**
     * formateo basico de php para trabajar con el numero
     * @param int|string|null $numero
     * @return float|null
     */
    protected function formatNumberToPhp($numero)
    {
        if ($numero === '' || empty($numero))
            return 0;
        else
            return round(str_replace(',', '.', $numero), 2);
    }

    /**
     * para insert en consulta mediante zend
     * @param int|null $numero
     * @return Expression|string
     */
    protected function formatNumberToOracle($numero)
    {
        if ($numero === '' || (empty($numero) && strcmp($numero, 0) != 0))
            return '';
        else
            return new Expression("TO_NUMBER('" . $this->formatNumberToSql($numero) . "', '9999999D00', 'NLS_NUMERIC_CHARACTERS='',.''')");
    }

    /**
     * formateo basico de php para trabajar con el numero sin redondear
     * @param int|string|null $numero
     * @return float|null
     */
    protected function formatNumberToPhpNoRound($numero)
    {
        if ($numero === '' || empty($numero))
            return 0;
        else
            return str_replace(',', '.', $numero);
    }

    /**
     * @param string $fecha
     * @return string|Expression
     */
    protected function toDateBD($fecha)
    {
        return (!empty($fecha)) ? new Expression("TO_DATE('" . $fecha . "','" . Constantes::MASCARA_FECHA_ORACLE_TIMESTAMP . "')") : '';
    }
}
