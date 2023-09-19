<?php

namespace system\base;

use PDO;

/**
 *
 */
class Database
{
    /**
     * @var mixed|string
     */
    private $host = "";
    /**
     * @var mixed|string
     */
    private $user = "";
    /**
     * @var mixed|string
     */
    private $pass = "";
    /**
     * @var mixed|string
     */
    private $dbname = "";
    /**
     * @var PDO
     */
    private $dbh;
    /**
     * @var string
     */
    private $error;
    /**
     * @var string
     */
    private $stmt = "";
    /**
     * @var string
     */
    private $request = "";
    /**
     * @var array
     */
    private $attributes = [];

    public function __construct()
    {
        // Set DSN
        $this->host = dbInfo("host");
        $this->user = dbInfo("user");
        $this->pass = dbInfo("pass");
        $this->dbname = dbInfo("dbname");

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        );
        //Create a new PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            //catch any error of type PDOException
            $this->error = $e->getMessage();
        }
    }

    /**
     * @param $value
     * @return int|string
     */
    public function checktype($value = '')
    {
        # code...
        switch (true) {
            case is_int($value):
                return (int) $value;
                break;
            default:
                return "'" . $value . "'";
        }
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param $query
     * @return void
     */
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
        // return $this->resultSet();
    }

    /**
     * @param $type
     * @return $this
     */
    public function select($type)
    {
        $this->request = "select " . $type;
        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function delete($type)
    {
        $this->request = "delete " . $type;
        return $this;
    }
    /**
     * @param $type
     * @return $this
     */
    public function select_distinct($type)
    {
        $this->request .= "select " . $type;
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function from($table)
    {
        $tableString = $table;
        if (is_array($table) && !empty($table)) {
            $tableString = implode(',', $table);
        }
        $this->request .= " from " . $tableString;
        return $this;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $operator
     * @return $this
     */
    public function where($attribute, $value, $operator = "=")
    {
        $at = $this->formatAndSetAttr($attribute, $value);
        $this->request .= " where " . $attribute . $operator . ":" . $at;
        return $this;
    }


    /**
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function join($attribute, $value)
    {
        $this->request .= " where " . $attribute . " = " . $value;
        return $this;
    }

    /**
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function and_join($attribute, $value)
    {
        $this->request .= " and " . $attribute . " = " . $value;
        return $this;
    }

    /**
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function where_not($attribute, $value)
    {
        $this->set($attribute, $value, "cond");
        $this->request .= " where " . $attribute . "!=:" . $attribute;
        return $this;
    }

    /**
     * @param $attribute
     * @param $value
     * @return $this
     */
    function  and($attribute, $value, $operator = "=")
    {

        $at = $this->formatAndSetAttr($attribute, $value);
        $this->request .= " and " . $attribute . $operator . ":" . $at;

        return $this;
    }


    /**
     * @param $attribute
     * @param $value
     * @return array|string|string[]
     */
    private function formatAndSetAttr($attribute, $value)
    {
        $at = str_replace(['.', ',', '*', '/', '-', '+'], '', $attribute);
        // $this->get;
        $at .= isset($this->attributes[$at]) ? count($this->attributes) : "";

        $this->set($at, $value, "cond");
        return $at;
    }

    /**
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function and_not($attribute, $value)
    {
        $at = $this->formatAndSetAttr($attribute, $value);
        $this->request .= " and " . $attribute . "!=:" . $at;
        return $this;
    }

    /**
     * @param $start
     * @param $end
     * @return $this
     */
    public function limit($start, $end)
    {
        $this->request .= " limit " . $start . "," . $end;
        return $this;
    }

    /**
     * @param $attribute
     * @param $value
     * @return $this
     */
    function  or($attribute, $value, $operator = "=")
    {
        $at = $this->formatAndSetAttr($attribute, $value);
        $this->request .= " or " . $attribute . $operator . ":" . $at;
        return $this;
    }

    /**
     * @param $attribute
     * @return $this
     */
    public function group_by($attribute)
    {
        $this->request .= " group by " . $attribute;
        return $this;
    }

    /**
     * @param $attribute
     * @param $sort
     * @return $this
     */
    public function order_by($attribute, $sort)
    {
        $this->request .= " order by " . $attribute . " " . $sort;
        return $this;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $type
     * @return void
     */
    public function set($attribute, $value, $type = null)
    {
        # code...
        $this->attributes[$attribute]["value"] = $value;
        if (!is_null($type)) {
            # code...
            $this->attributes[$attribute]["type"] = $value;
        }
    }

    /**
     * @param $attribute
     * @return void
     */
    public function unset($attribute)
    {
        $this->unset($this->attributes[$attribute]);
    }

    /**
     * @param $tablename
     * @return mixed
     */
    public function add($tablename)
    {
        $attrs = [];
        foreach ($this->attributes  as $key => $value) {
            # code...
            if (!isset($value["type"])) {
                # code...
                array_push($attrs, $key);
            }
        }
        // echo 'insert into ' . $tablename . ' (' . implode(',', $attrs) . ') values (:' . implode(',:', $attrs) . ')</br>';
        $this->request = 'insert into ' . $tablename . ' (' . implode(',', $attrs) . ') values (:' . implode(',:', $attrs) . ')';

        $this->stmt = $this->dbh->prepare($this->request);


        return $this->execute();
    }

    /**
     * @param $tablename
     * @return mixed
     */
    public function update($tablename)
    {
        # code...
        $sets = "";
        foreach ($this->attributes as $attribute => $datas) {
            # code...
            if (!isset($datas["type"])) {
                # code...
                $sets .= $attribute . ' =:' . $attribute . ',';
            }
        }
        $sets = substr($sets, 0, strlen($sets) - 1);
        $this->request = 'update ' . $tablename . ' set ' . $sets . $this->request;
        //         echo $this->request;
        $this->stmt = $this->dbh->prepare($this->request);

        return $this->execute();
    }

    /**
     * @return void
     */
    public function reset()
    {
        # code...
        $this->request = "";
        $this->attributes = [];
        $this->values = [];
        $this->alias = [];
    }

    /**
     * @return $this
     */
    public function get()
    {
        // echo $this->request;
        $this->stmt = $this->dbh->prepare($this->request);
        return $this;
    }

    /**
     * @return mixed
     */
    public function result()
    {
        return $this->resultset();
    }

    /**
     * @param $param
     * @param $value
     * @param $type
     * @return void
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        if (!empty($this->attributes)) {
            foreach ($this->attributes as $attribute => $datas) {
                # code...
                $this->bind(":" . $attribute, $datas["value"]);
            }
            $this->reset();
        }
        return $this->stmt->execute();
    }

    /**
     * @return mixed
     */
    public function resultset()
    {

        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * @return false|string
     */
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * @return bool
     */
    public function endTransaction()
    {
        return $this->dbh->commit();
    }

    /**
     * @return bool
     */
    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }

    /**
     * @return mixed
     */
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}
