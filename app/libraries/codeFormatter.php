<?php
namespace app\libraries;
class codeFormatter
{
    private $spacing=0;
    private $code;
    public function __construct(){

    }
    public function format($code){
        $word ="";
        $this->code = $code;
        $formattedCode = "";
        for ($i = 0; $i< strlen($this->code); $i++){
            switch ($this->code[$i]){
                case "{":
                    $formattedCode.=$this->code[$i].$this->space("ret",1);
                    $this->spacing++;
                    $formattedCode.=$this->space("tab",$this->spacing);
                    break;
                case "}":
                    $this->spacing--;
                    if(isset($this->code[$i+1]) && $this->code[$i+1]!="}"){
                        $formattedCode.=$this->space("tab",$this->spacing,$this->code[$i].$this->space("ret",1).$this->space("tab",$this->spacing));
                    }else{
                        $formattedCode.=$this->space("tab",$this->spacing,$this->code[$i].$this->space("ret",1));
                    }

                    break;
                case ";":
                case ",":
                    $formattedCode.=$this->code[$i].$this->space("ret",1);
                    if ($this->code[$i+1]!="}"){
                    $formattedCode.=$this->space("tab",$this->spacing);
                    }
                    break;
                case"[":
                    if (isset($this->code[$i+1]) && $this->code[$i+1]!="]"){
                        $formattedCode.=$this->space("ret",1,$this->space("tab",$this->spacing,$this->code[$i].$this->space("ret",1)));
                        $this->spacing++;
                        $formattedCode.=$this->space("tab",$this->spacing);
                    }else{
                        $formattedCode.=$this->code[$i];
                    }
                    break;
                case "]":
                    if ($this->code[$i-1]!="["){
                        $this->spacing--;
                        $formattedCode.=$this->space("ret",1,$this->space("tab",$this->spacing,$this->code[$i]));
                    }else{
                        $formattedCode.=$this->code[$i];
                    }
                    break;
                default:
                    if ($this->code[$i]!=" "){
                        $word.=$this->code[$i];
                        $formattedCode.=$this->code[$i];

                    }else{
                        $formattedCode.=$this->code[$i];
                        if (count(explode("php",$word))>1){
                            $formattedCode.=$this->space("ret",1);
                            $word="";
                        }
                    }
                    break;
            }

        }
        return $formattedCode;
    }
    private function space($type,int $number,string $text = null){
        $i = 0;
        $tabulate="";
        while ($i<$number){
            $tabulate.=($type=="tab")?"\t":($type=="ret"?"\n":'');
            $i++;
        }
        return !is_null($text)?$tabulate.$text:$tabulate;
    }

}