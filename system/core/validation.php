<?php
/* 


*/

namespace system\core;

use DateTime;
use Exception;

class Validation
{
    /**
     * @var array
     */
    protected $rules = [];
    /**
     * @var null
     */
    protected $selectedRulesGroup = null;
    /**
     * @var array
     */
    protected $errors = [];
    public function __construct()
    {
        # code...
    }

    /**
     * @return array
     */
    public function getAllErrors()
    {
        return $this->errors;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getErrors($name)
    {
        if (isset($this->errors[$name])) {
            return $this->errors[$name];
        }
        return null;
    }

    /**
     * @param $name
     * @param $error
     * @return void
     */
    public function setError($name, $error)
    {
        if (is_null($this->getErrors($name))) {
            $this->errors[$name] = [];
        }
        array_push($this->errors[$name], $error);
    }

    /**
     * @return mixed|null
     */
    public function getSelectedRulesGroupName()
    {
        return $this->selectedRulesGroup;
    }

    /**
     * @return mixed|null
     */
    public function getSelectedRulesGroup()
    {
        if (!is_null($this->getSelectedRulesGroupName())) {
            return $this->rules[$this->selectedRulesGroup];
        }
        return null;
    }

    /**
     * @param $key
     * @return void
     */
    public function setSelectedRulesGroup($key = "")
    {
        if (!empty($key) && $key != null) {
            $this->selectedRulesGroup = $key;
        }
    }

    /**
     * @param $RulesGroupName
     * @return mixed|null
     */
    public function getRulesGroup($RulesGroupName)
    {
        if (isset($this->rules[$RulesGroupName])) {
            return $this->rules[$RulesGroupName];
        }
        return null;
    }

    /**
     * @param $RulesGroupName
     * @return void
     */
    public function setRulesGroup($RulesGroupName)
    {
        if (is_null($this->getRulesGroup($RulesGroupName))) {
            $this->rules[$RulesGroupName] = [];
            $this->setSelectedRulesGroup($RulesGroupName);
        }
    }

    /**
     * @param $ruleName
     * @return mixed|null
     */
    public function getRule($ruleName)
    {
        if (!is_null($this->getSelectedRulesGroup())) {
            if (isset($this->getSelectedRulesGroup()[$ruleName])) {
                return $this->getSelectedRulesGroup()[$ruleName];
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $rules
     * @return void
     */
    public function setRule(string $name = "", $label = null, string|callable $rules = "")
    {

        if (!is_null($this->getSelectedRulesGroupName())) {
            if (!empty($name) && is_string($name) && (is_string($label) || is_null($label)) && !empty($rules) && (is_string($rules) || is_callable($rules))) {
                if (is_null($this->getRule($name))) {
                    $this->rules[$this->selectedRulesGroup][$name] = ["rules" => $rules, "label" => $label];
                }
            }
        }
    }

    /**
     * @param array $rules
     * @return void
     */
    public function setRules(array $rules = [])
    {
        if (is_array($rules)) {
            foreach ($rules as $key => $rule) {
                # code...
                switch (true) {
                    case is_array($rule):
                        if (isset($rule["label"]) && isset($rule["rules"])) {
                            $this->setRule($key, $rule["label"], $rule["rules"]);
                        } else {
                            if (isset($rule["rules"])) {
                                $this->setRule($key, null, $rule["rules"]);
                            }
                        }
                        break;

                    case is_string($rule):
                    case is_callable($rule):
                        $this->setRule($key, null, $rule);
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }
    }

    /**
     * @return bool
     */
    public function run(): bool
    {
        $ret = true;
        foreach ($this->getSelectedRulesGroup() as $name => $LabelAndRulesArray) {
            if (isset($_POST[$name])) {
                switch (true) {
                    case is_string($LabelAndRulesArray["rules"]):
                        $rule_list = explode("|", $LabelAndRulesArray["rules"]);
                        foreach ($rule_list as $key1 => $ruleItem) {
                            try {
                                $this->eval($_POST[$name], $ruleItem, $LabelAndRulesArray["label"] ?? $name);
                            } catch (Exception $error) {
                                $ret = false;
                                $this->setError($name, $error->getMessage());
                            }
                        }
                        break;
                    case is_callable($LabelAndRulesArray["rules"]):
                        try {
                            $this->eval($_POST[$name], $LabelAndRulesArray["rules"], $LabelAndRulesArray["label"] ?? $name);
                        } catch (Exception $error) {
                            $ret = false;
                            $this->setError($name, $error->getMessage());
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            } else {
                $ret = false;
                $this->setError($name, lang("is_required", ["name" => $name]));
            }
            // var_dump($this->getAllErrors());
            setErrors($this->getAllErrors());
        }
        return $ret;
    }

    /**
     * @param $value
     * @param $ruleItem
     * @param $label
     * @return void
     * @throws Exception
     */
    public function eval($value, $ruleItem, $label)
    {
        $analyseResult = $this->analyse($ruleItem);
        switch ($analyseResult["rule"]) {
            case 'required':
                if (empty($value)) {
                    throw new Exception(lang("is_required", ["name" => $label]));
                }
                break;
            case 'char':
            case 'varchar':
            case 'mediumtext':
                if (!is_string($value)) {
                    throw new Exception(lang("type_error", ["name" => $label, "type" => lang("string")]));
                }
                break;
            case 'double':
                if (!is_double($value)) {
                    throw new Exception(lang("type_error", ["name" => $label, "type" => lang("dooble")]));
                }
                break;
            case 'real':
                if (!is_float($value)) {
                    throw new Exception(lang("type_error", ["name" => $label, "type" => lang("real")]));
                }
                break;
            case 'datetime':
            case 'date':
                if (!is_date($value)) {
                    throw new Exception(lang("type_error", ["name" => $label, "type" => lang("date")]));
                }
                break;
            case 'integer':
            case 'bigint':
                if (!is_numeric($value)) {
                    throw new Exception(lang("type_error", ["name" => $label, "type" => lang("integer")]));
                }
                break;
            case 'matches':
                if (isset($_POST[$analyseResult["param"]])) {
                    if ($value != $_POST[$analyseResult["param"]]) {
                        throw new Exception(lang("doesnt_match") . " " . $analyseResult["param"]);
                    }
                } else {
                    throw new Exception(lang("doesnt_match") . " " . $analyseResult["param"] . ":<b>" . $analyseResult["param"] . " " . lang("doesnt_exist") . "</b>");
                }
                break;
            case 'min':
                if (isset($analyseResult["param"]) && is_numeric($analyseResult["param"])) {
                    if (is_numeric($value)) {
                        if ($value < intval($analyseResult["param"])) {
                            throw new Exception(lang("minimum_value") . " " . $analyseResult["param"]);
                        }
                    } else {
                        throw new Exception(lang("must_be_a") . " " . lang("numeric"));
                    }
                }
                break;
            case 'max':
                if (isset($analyseResult["param"]) && is_numeric($analyseResult["param"])) {
                    if (is_numeric($value)) {
                        if ($value > intval($analyseResult["param"])) {
                            throw new Exception(lang("maximum_value") . " " . $analyseResult["param"]);
                        }
                    } else {
                        throw new Exception(lang("must_be_a") . " " . lang("numeric"));
                    }
                }
                break;
            case 'min_length':
                if (isset($analyseResult["param"]) && is_numeric($analyseResult["param"])) {
                    if (is_string($value)) {
                        if (strlen($value) < intval($analyseResult["param"])) {
                            throw new Exception(lang("minimum_length") . " " . $analyseResult["param"]);
                        }
                    } else {
                        throw new Exception(lang("must_be_a") . " " . lang("string"));
                    }
                }
                break;
            case 'max_length':
                if (isset($analyseResult["param"]) && is_numeric($analyseResult["param"])) {
                    if (is_string($value)) {
                        if (strlen($value) > intval($analyseResult["param"])) {
                            throw new Exception(lang("maximum_length") . " " . $analyseResult["param"]);
                        }
                    } else {
                        throw new Exception(lang("must_be_a") . " " . lang("string"));
                    }
                }
                break;
            case "callable":
                $analyseResult["param"]();
                break;
            case "now":
            case "after":
            case "before":
                if (strtotime($value)) {

                    if (strtotime($_POST[$analyseResult["param"]])) {
                        // 
                        $timeValue = strtotime($value);
                        $timeParam  = strtotime($_POST[$analyseResult["param"]]);
                        switch ($analyseResult["rule"]) {
                            case 'now':
                                $timeValue = strtotime(date("Y-m-d"));
                                break;
                            case 'after':
                                if ($timeValue < $timeParam) {
                                    throw new Exception(lang("time_error", ["name" => $label, "operand" => lang("upper"), "param" => $analyseResult["param"]]));
                                }
                                break;
                            case 'before':
                                if ($timeValue > $timeParam) {
                                    throw new Exception(lang("time_error", ["name" => $label, "operand" => lang("lower"), "param" => $analyseResult["param"]]));
                                }
                                break;
                            default:
                                break;
                        }
                    } else {
                        throw new Exception(lang("type_error", ["name" => $analyseResult["param"], "type" => lang("date")]));
                    }
                } else {
                    throw new Exception(lang("type_error", ["name" => $label, "type" => lang("date")]));
                }

                break;
            default:
                # code...
                break;
        }
    }

    /**
     * @param $ruleItem
     * @return string[]
     */
    public function analyse($ruleItem): array
    {
        $r_p = ["rule" => "", "param" => ""];

        switch (true) {
            case is_string($ruleItem):
                $param = false;
                for ($i = 0; $i < strlen($ruleItem); $i++) {
                    if (array_search($ruleItem[$i], ["[", ":"]) != false) {
                        $param = true;
                        $i++;
                    } else {
                        if ($ruleItem[$i] == "]") {
                            $param = false;
                            $i++;
                        }
                    }

                    if ($i < strlen($ruleItem)) {
                        if (!$param) {
                            $r_p["rule"] .= $ruleItem[$i];
                        } else {
                            $r_p["param"] .= $ruleItem[$i];
                        }
                    }
                }
                break;
            case is_callable($ruleItem):
                # code...
                $r_p["rule"] = "callable";
                $r_p["param"] = $ruleItem;

                break;
            default:
                # code...
                break;
        }
        return $r_p;
    }
}
