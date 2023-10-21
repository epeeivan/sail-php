<?php

namespace app\controllers;

use app\models\appGenerator_model;
use system\core\Controller;
use system\core\Router;
use system\Loader;

class app extends Controller
{
    protected $appGenerator_model;
    private $dbStructure = [];
    protected $codeFormatter;
    public function __construct()
    {

        $this->model('appGenerator_model', false, ["no_schema" => true]);
        $this->appGenerator_model->setDb();
        $this->library('codeFormatter');
    }
    function index()
    {
        //        echo "i am the app generator";
        var_dump(Router::getCurrentRoute());
        // $dbTables = $this->appGenerator_model->dbTables();
        //        var_dump($dbTables);
        //        var_dump(preg_match("#([0-9]+)#","(65)",$match));
        //        var_dump($match);
        //        header("content-type:JSON");
        // $this->responseJson($this->appGenerator_model->tableColumns("useraccount"));
    }
    function test()
    {
        $dbTables = $this->appGenerator_model->dbTables();
        foreach ($dbTables as $key => $table) {
            foreach ($table as $key1 => $tableName) {
                $this->dbStructure[$tableName] = [];
                $tableColumns = $this->appGenerator_model->tableColumns($tableName);
                //to keep the single column id
                $tableId = null;
                foreach ($tableColumns as $columnKey => $tableColumn) {
                    $columnName = $tableColumn['COLUMN_NAME'];
                    $this->dbStructure[$tableName][$columnName]['type'] = $tableColumn['COLUMN_TYPE'];
                    $this->dbStructure[$tableName][$columnName]['data_type'] = $tableColumn['DATA_TYPE'];
                    $this->dbStructure[$tableName][$columnName]['is_nullable'] = $tableColumn['IS_NULLABLE'] == "YES";
                    $max_length = $tableColumn['CHARACTER_MAXIMUM_LENGTH'];
                    if (preg_match("#([0-9]+)#", $tableColumn['COLUMN_TYPE'], $match)) {
                        $max_length = $match[0];
                    }
                    $this->dbStructure[$tableName][$columnName]['max_length'] = $max_length;

                    //
                    if (!empty($tableColumn['COLUMN_KEY'])) {
                        switch ($tableColumn['COLUMN_KEY']) {
                            case 'PRI':
                                //check if the table has multiple columns key
                                if (is_null($tableId)) {
                                    $tableId = $columnName;
                                    $this->dbStructure[$tableName][$columnName]['id'] = true;
                                } else {
                                    //when another column key is detected
                                    if (isset($this->dbStructure[$tableName][$tableId]['id'])) {
                                        //remove the previous column id
                                        unset($this->dbStructure[$tableName][$tableId]['id']);
                                        //set the previous column id as foreign
                                        $this->dbStructure[$tableName][$tableId]['foreign'] = true;
                                    }
                                    //set the new detected id columns as foreign keys
                                    $this->dbStructure[$tableName][$columnName]['foreign'] = true;
                                }
                                break;
                            case 'MUL':
                                $this->dbStructure[$tableName][$columnName]['foreign'] = true;

                                break;
                        }
                    }
                }
            }
        }
        $this->setForeigns();
        //        $this->responseJson($this->dbStructure);
        //var_dump(array_search("ADMIN_ID",$this->dbStructure["admin"]));
        //res($this->dbStructure);
        //var_dump($this->appGenerator_model->tableColumns('admin')[0][]);

        $this->responseJson($this->generateAppBase());
    }
    private function setForeigns($column = null)
    {

        foreach ($this->dbStructure as $dbTable => $dbTableColumns) {
            foreach ($dbTableColumns as $dbTableColumn => $dbTableProperties) {
                if (isset($dbTableProperties["foreign"]) && is_null($column)) {

                    $this->dbStructure[$dbTable][$dbTableColumn]["foreign"] = $this->setForeigns($dbTableColumn);
                } else {
                    if (isset($dbTableProperties["id"]) && isset($column) && $dbTableColumn == $column) {
                        //echo $dbTable;

                        return $dbTable;
                    }
                }
            }
        }
    }
    public function generateAppBase()
    {
        foreach ($this->dbStructure as $tableName => $columns) {
            $code = [];
            $code["validation"] = $this->declarate($tableName, 'validation');
            $code["controller"] = $this->declarate($tableName, 'controller');

            $code["model"] = $this->declarate($tableName, 'model');
            $code["schema"] = $this->declarate($tableName, 'schema');
            //
            $code["validation"] .= "\$this->setRulesGroup('v" . ucfirst($tableName) . "');";
            $code["validation"] .= "\$this->setRules([";
            //
            $code["model"] .= "use " . $tableName . "_model_schema;public function __construct(){\$this->buildSchema();}}";
            //
            $code["schema"] .= "public function buildSchema(){\$this->table('" . $tableName . "');";
            //
            $code["controller"] .= $this->controllerCode($tableName);

            foreach ($columns as $column => $properties) {
                if (!isset($properties["id"])) {
                    if (!$properties["is_nullable"]) {
                        $code["validation"] .= "'" . strtolower($column) . "' => 'required|" . $properties["data_type"] . (!empty($properties["max_length"]) ? "|max_length(" . $properties["max_length"] . ")" : "") . "',";
                    } else {
                        $code["validation"] .= "'" . strtolower($column) . "' => '" . $properties["data_type"] . (!empty($properties["max_length"]) ? "|max_length(" . $properties["max_length"] . ")" : "") . "',";
                    }
                } else {
                    $modelName = "\$this->" . $tableName . "_model";
                    $code["controller"] .= "public function delete(){if(isset(\$_GET['" . $column . "'])){" . $modelName . "->hydrater(\$_GET);if(" . $modelName . "->delete()){\$this->responseJson(\$_GET);}else{\$this->responseJson();}}else{\$this->responseJson();}}";
                }
                //
                $code["schema"] .= "\$this->column('" . $column . "')->type('" . $properties["type"] . "')";
                switch ($properties) {
                    case isset($properties["id"]):
                        $code["schema"] .= "->id()";
                        break;
                    case isset($properties["foreign"]):
                        $code["schema"] .= "->foreign('" . $properties["foreign"] . "')";
                        break;
                }
                $code["schema"] .= ";";
            }
            $code["validation"] .= "]);}}";
            $code["schema"] .= "}}";
            $code["controller"] .= "}";

            $this->dbStructure[$tableName]['code']['validation'] = $this->codeFormatter->format($code["validation"]);
            $this->dbStructure[$tableName]['code']['schema'] = $this->codeFormatter->format($code["schema"]);
            $this->dbStructure[$tableName]['code']['model'] = $this->codeFormatter->format($code["model"]);
            $this->dbStructure[$tableName]['code']['controller'] = $this->codeFormatter->format($code["controller"]);
            //
            $this->saveCode($tableName, $this->dbStructure[$tableName]['code']['validation'], 'validation');
            $this->saveCode($tableName, $this->dbStructure[$tableName]['code']['schema'], 'schema');
            $this->saveCode($tableName, $this->dbStructure[$tableName]['code']['model'], 'model');
            $this->saveCode($tableName, $this->dbStructure[$tableName]['code']['controller'], 'controller');
        }
        return $this->dbStructure;
    }
    private function controllerCode($tableName)
    {
        $cCode = "";
        $fPrefix = "public function ";

        $cCode .= "\$this->model('" . $tableName . "_model');\$this->validation('v" . ucfirst($tableName) . "');}";

        $cCode .= $fPrefix . "get(){\$this->responseJson(\$this->" . $tableName . "_model->get());}";
        $addUpBody = "{if(\$this->v" . ucfirst($tableName) . "->run()){\$this->" . $tableName . "_model->hydrater();if(\$this->" . $tableName . "_model->add()){ \$this->responseJson(\$_POST);}else{\$this->responseJson();}}else{\$this->responseJson();}}";
        $cCode .= $fPrefix . "add()" . $addUpBody;
        $cCode .= $fPrefix . "update()" . $addUpBody;

        return $cCode;
    }
    private function declarate(string $name, string $type)
    {
        $basicDeclaration = "<?php ";

        switch ($type) {
            case 'model':
                $basicDeclaration .= "namespace app\models;use system\core\Model;use app\schemas\\" . $name . "_model_schema; class " . $name . "_model extends Model{";
                break;
            case 'validation':
                $basicDeclaration .= "namespace app\\validations;use system\core\Validation;class v" . ucfirst($name) . " extends Validation{public function __construct(){";

                break;
            case 'controller':
                $basicDeclaration .= "namespace app\controllers\api;use system\core\Controller;class " . $name . " extends Controller{protected $" . $name . "_model;protected $" . "v" . ucfirst($name) . "; public function __construct(){";
                break;
            case 'schema':
                $basicDeclaration .= "namespace app\schemas;trait " . $name . "_model_schema{";
                break;
            default:
                break;
        }
        return $basicDeclaration;
    }
    private function space($type, int $number, string $text = null)
    {
        $i = 0;
        $tabulate = "";
        while ($i < $number) {
            $tabulate .= ($type == "tab") ? "\t" : ($type == "ret" ? "\n" : '');
            $i++;
        }
        return !is_null($text) ? $tabulate . $text : $tabulate;
    }
    private function saveCode(string $tableName, string $code, string $type)
    {
        $fileName = "";
        switch ($type) {
            case 'model':

                $fileName .= $this->checkDirExistBeforeSetFileName(getConfig("paths")["models_folder"]) . $tableName . "_model";
                break;
            case 'validation':
                $fileName .= $this->checkDirExistBeforeSetFileName(getConfig("paths")["validations_folder"]) . "v" . ucfirst($tableName);

                break;
            case 'controller':
                $fileName .= $this->checkDirExistBeforeSetFileName(getConfig("paths")["controllers_folder"] . "/api/") . $tableName;
                break;
            case 'schema':
                $fileName .= $this->checkDirExistBeforeSetFileName(getConfig("paths")["schemas_folder"]) . $tableName . "_model_schema";
                break;
            default:
                break;
        }
        if (!file_exists($fileName . ".php")) {
            $fileStream = fopen($fileName . ".php", 'a+');
            fwrite($fileStream, $code);
            fclose($fileStream);
        }
    }
    private function checkDirExistBeforeSetFileName($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        return $dir;
    }
}
