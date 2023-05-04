<?php
/* 


*/
namespace system\core;

class Validation{
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
    protected $errors=[];
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
    public function setError($name,$error)
    {
       if (is_null($this->getErrors($name))) {
        $this->errors[$name]=[];
       }
       array_push($this->errors[$name],$error);
    }

    /**
     * @return mixed|null
     */
    public function getSelectedRulesGroupName(){
        return $this->selectedRulesGroup;
    }

    /**
     * @return mixed|null
     */
    public function getSelectedRulesGroup(){
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
        if (!empty($key) && $key!=null) {
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
    public function setRule(string $name="", string $label="", string $rules=""){
        if (!is_null($this->getSelectedRulesGroupName())) {
            if (!empty($name) && is_string($name) && is_string($label) && !empty($rules) && is_string($rules)) {
                if (is_null($this->getRule($name))) {

                    $this->rules[$this->selectedRulesGroup][$name] = ["rules"=>$rules,"label"=>$label];
                }
            }
        }
        
    }

    /**
     * @param array $rules
     * @return void
     */
    public function setRules(array $rules=[])
    {
        if (is_array($rules)) {
            foreach ($rules as $key => $rule) {
                # code...
                if (is_array($rule)) {
                    # code...
                    if(isset($rule["label"]) && isset($rule["rules"])){
                        $this->setRule($key,isset($rule["label"]),$rule["rules"]);
                    }else{
                        if (isset($rule["rules"])) {
                            $this->setRule($key,"",$rule["rules"]);
                        }

                    }
                }else{
                        if (is_string($rule)) {
                            $this->setRule($key,"",$rule);
                        }
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
                $rule_list = explode("|",$LabelAndRulesArray["rules"]);
                foreach ($rule_list as $key1 => $ruleItem) {
                    try {
                        $this->eval($_POST[$name],$ruleItem,$LabelAndRulesArray["label"]);                    
                        
                    } catch (Exception $error) {
                        $ret = false;
                        $this->setError($name,$error->getMessage());
                    }
                }
            }else{
                $ret = false;
                //$this->setError($name,lang("undefined_field")." ".$name);
            }
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
    public function eval($value,$ruleItem,$label)
    {
        $analyseResult = $this->analyse($ruleItem);
        switch ($analyseResult["rule"]) {
            case 'required':
                if (empty($value)) {
                    throw new Exception(lang("is_required"));
                }
                break;
            case 'integer':
                if (!is_numeric($value)) {
                    throw new Exception(lang("must_be_an")." ".lang("integer"));
                }
                break;
            case 'matches':
                if (isset($_POST[$analyseResult["param"]])) {
                    if ($value != $_POST[$analyseResult["param"]]) {
                        throw new Exception(lang("doesnt_match")." ".$analyseResult["param"]);
                    }
                }else{
                    throw new Exception(lang("doesnt_match")." ".$analyseResult["param"].":<b>".$analyseResult["param"]." ".lang("doesnt_exist")."</b>");
                }
                break;
            case 'min':
                if (isset($analyseResult["param"]) && is_numeric($analyseResult["param"])) {
                    if (is_numeric($value)) {
                        if ($value < intval($analyseResult["param"])) {
                            throw new Exception(lang("minimum_value")." ".$analyseResult["param"]);
                        }
                    }else{
                        throw new Exception(lang("must_be_a")." ".lang("numeric"));
                    }
                }
                break;
            case 'max':
                if (isset($analyseResult["param"]) && is_numeric($analyseResult["param"])) {
                    if (is_numeric($value)) {
                        if ($value > intval($analyseResult["param"])) {
                            throw new Exception(lang("maximum_value")." ".$analyseResult["param"]);
                        }
                    }else{
                        throw new Exception(lang("must_be_a")." ".lang("numeric"));
                    }
                }
                break;
            case 'min_length':
                if (isset($analyseResult["param"]) && is_numeric($analyseResult["param"])) {
                    if (is_string($value)) {
                        if (strlen($value) < intval($analyseResult["param"])) {
                            throw new Exception(lang("minimum_length")." ".$analyseResult["param"]);
                        }
                    }else{
                        throw new Exception(lang("must_be_a")." ".lang("string"));
                    }

                }
                break;
            case 'max_length':
                if (isset($analyseResult["param"]) && is_numeric($analyseResult["param"])) {
                    if (is_string($value)) {
                        if (strlen($value) > intval($analyseResult["param"])) {
                            throw new Exception(lang("maximum_length")." ".$analyseResult["param"]);
                        }
                    }else{
                        throw new Exception(lang("must_be_a")." ".lang("string"));   
                    }

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
        $r_p = [ "rule"=> "", "param"=> "" ];
        $param = false;
        for ($i = 0; $i < strlen($ruleItem); $i++) {
          if ($ruleItem[$i] == "[") {
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

        return $r_p;
    }

}