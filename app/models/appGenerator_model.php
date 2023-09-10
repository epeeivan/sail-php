<?php

namespace app\models;

use system\core\Model;

class appGenerator_model extends Model
{

    public function __construct()
    {
        // $this->setDb();
    }
    public function dbTables()
    {

        $this->db->query('show tables');
        return $this->db->result();
    }

    public function tableColumns($tablename)
    {
        return $this->db->select('*')
            ->from('INFORMATION_SCHEMA.COLUMNS')
            ->where('TABLE_NAME', $tablename)
            ->get()
            ->result();
    }
}
