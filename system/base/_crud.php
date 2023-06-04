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
        $res = $this->db->select(isset($_GET["count"])?"count(*) as count":$columnssString)
            ->from($this->getTable());
        // conditional
        (isset($_GET["id"]))?$res->where($this->getId(),$this->getIdValue()):null;
        (isset($_GET["date"]))?$res->where("CREATED_AT","%".$_GET["date"]."%"," like "):null;
        // end

        $res->order_by($this->getId(),'desc');
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
            $this->db->select($columnssString)
                ->from($this->getTable())
                ->order_by($this->getId(),'desc')
                ->limit($start,$end)
                ->get()
                ->result();
    }

    /**
     * @return mixed
     */
    public function count(){
        return
            $this->db->select('count(*) as count')
                ->from($this->getTable())
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
            $this->db->select($columnsString)
                ->from($this->getTable())
                ->where($this->getId(),$this->getIdValue())
                ->order_by($this->getId(),'desc')
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
            $this->db->select($columnsString)
                ->from($this->getTable())
                ->where($column,$this->getColumnValue($column))
                ->order_by($this->getId(),'desc')
                ->get()
                ->result();
    }
    /**
     * @return void
     */
    public function add($includeId = false) {
        try {
            $this->setFieldsFromRequest($includeId);
            return $this->db->add($this->getTable());
        }catch (PDOException $e){
//            switch
            $this->error = $this->handleError($e);
        }
    }

    /**
     * @return void
     */
    public function update(){
        try {
            $this->setFieldsFromRequest();
            $this->db->where(strtolower($this->getId()), $this->getColumnValue($this->getId()));
            return $this->db->update($this->getTable());
        }catch (PDOException $e){
//            switch
            return $this->handleError($e);
        }
    }

    /**
     * @return void
     */
    public function delete(){
        return $this->db->delete($this->getTable())
            ->from($this->getTable())
            ->where($this->getId(),$this->getIdValue())
            ->get()
            ->execute();

    }

    /**
     * @return void
     */
    public function setFieldsFromRequest($includeId = false)
    {
        foreach ($this->getschema() as $column => $value) {
            //
            if (!empty($this->getColumnValue($column))){
                if (!$this->is_id($column) && $column!='table'&& !$includeId){
                    $this->db->set(strtolower($column), $this->getColumnValue($column));
                }else{
                    if ($column!='table'&& $includeId){
                        $this->db->set(strtolower($column), $this->getColumnValue($column));
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
        foreach ($this->getschema() as $column => $value) {
            //
            if (!$this->is_id($column) && $column!='table'){
                $this->db->set(strtolower($column), $this->getColumnValue($column));
            }
        }
    }

    /**
     * @return void
     */
    public function setFieldsFromRequestWithId()
    {
        foreach ($this->getschema() as $column => $value) {
            //
            if (!$this->is_id($column) && $column!='table'){
                $this->db->set(strtolower($column), $this->getColumnValue($column));
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
