<?php
namespace system\base;

/**
 * groups together a set of generic
 * functions related to sql queries,
 * including an insertion function,
 * selection functions and a deletion function.
 *
 * @package Miag
 * @subpackage Trait
 * @category Database
 * @author beis
 * @link
 */
trait Crud
{
    /**
     * @param array|null $columns
     * @return mixed
     */
    public $error = [];


    public static function get(Array $columns = null){
        $columnssString = '*';
        !is_null($columns)?
            $columnssString = implode(',',$columns):
            '';
        $res = self::$db->select(isset($_GET["count"])?"count(*) as count":$columnssString)
            ->from(self::getTable());
        // conditional
        (isset($_GET["id"]))?$res->where(self::getId(),self::getIdValue()):null;
        (isset($_GET["date"]))?$res->where("CREATED_AT","%".$_GET["date"]."%"," like "):null;
        // end

        $res->order_by(self::getId(),'desc');
        // conditional
        (isset($_GET["start"]) && isset($_GET["end"]))?$res->limit($_GET["start"],$_GET["end"]):null;
        // end
        $res->get();
        return isset($_GET["count"])?$res->single()["count"]:(isset($_GET["id"])?$res->single():$res->result());
    }

    public function getWithLimit($start = 0,$end = 10,Array $columns = null){
        $columnssString = '*';
        !is_null($columns)?
            $columnssString = implode(',',$columns):
            '';
        return
            self::$db->select($columnssString)
                ->from(self::getTable())
                ->order_by(self::getId(),'desc')
                ->limit($start,$end)
                ->get()
                ->result();
    }

    /**
     * @return mixed
     */
    public function count(){
        return
            self::$db->select('count(*) as count')
                ->from(self::getTable())
                ->get()
                ->single();
    }

    /**
     * @param array|null $columns
     * @return mixed
     */
    public function getWhereId(Array $columns = null){
        $columnsString = (
            !is_null($columns)?
            implode(',',$columns):
            '*'
        );
        return
            self::$db->select($columnsString)
                ->from(self::getTable())
                ->where(self::getId(),self::getIdValue())
                ->order_by(self::getId(),'desc')
                ->get()
                ->single();
    }
    public function getWhereColumn(String $column, Array $columns = null){
        $columnsString = (
        is_null($columns)?
            implode(',',$columns):
            '*'
        );
        return
            self::$db->select($columnsString)
                ->from(self::getTable())
                ->where($column,self::getColumnValue($column))
                ->order_by(self::getId(),'desc')
                ->get()
                ->result();
    }
    /**
     * @return void
     */
    public function add($includeId = false) {
        try {
            self::setFieldsFromRequest($includeId);
            return self::$db->add(self::getTable());
        }catch (PDOException $e){
//            switch
            self::error = self::handleError($e);
        }
    }

    /**
     * @return void
     */
    public function update(){
        try {
            self::setFieldsFromRequest();
            self::$db->where(strtolower(self::getId()), self::getColumnValue(self::getId()));
            return self::$db->update(self::getTable());
        }catch (PDOException $e){
//            switch
            return self::handleError($e);
        }
    }

    /**
     * @return void
     */
    public function delete(){
        return self::$db->delete(self::getTable())
            ->from(self::getTable())
            ->where(self::getId(),self::getIdValue())
            ->get()
            ->execute();

    }

    /**
     * @return void
     */
    public function setFieldsFromRequest($includeId = false)
    {
        foreach (self::getschema() as $column => $value) {
            //
            if (!empty(self::getColumnValue($column))){
                if (!self::is_id($column) && $column!='table'&& !$includeId){
                    self::$db->set(strtolower($column), self::getColumnValue($column));
                }else{
                    if ($column!='table'&& $includeId){
                        self::$db->set(strtolower($column), self::getColumnValue($column));
                    }
                }
            }

        }
    }

    /**
     * @return void
     */
    public function setFieldsFromRequestWithoutID()
    {
        foreach (self::getschema() as $column => $value) {
            //
            if (!self::is_id($column) && $column!='table'){
                self::$db->set(strtolower($column), self::getColumnValue($column));
            }
        }
    }

    /**
     * @return void
     */
    public function setFieldsFromRequestWithId()
    {
        foreach (self::getschema() as $column => $value) {
            //
            if (!self::is_id($column) && $column!='table'){
                self::$db->set(strtolower($column), self::getColumnValue($column));
            }
        }
    }

    public function handleError($e){
        $infos = [];
        switch ($e->getCode()){
            case 23000:
                $infos = explode("'",$e->getMessage());
                $column = lang($infos[3]);
                $infos = ["table"=>$infos[1],"column"=>$infos[3],"message"=>lang("db_column_value_exist",["column"=>$column])];
                break;
            default:
                echo $e->getMessage();
                break;

        }
        return $infos;
    }

}
