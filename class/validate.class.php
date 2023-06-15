<meta content ="text/html; charset = utd-8" http-equiv ="content-Type">
<?php
class Validate{
    
    private  $errors  = array();
    private  $sources = array();   
    private  $rules   = array();  
    private  $result  = array();
    
    //Contructor
    public function  __construct(array $source){
       $this->sources = $source; 
    }
  
    public function setRules(array $rules){
        $this->rules = array_merge($this->rules, $rules);
        
    }
    
    public function setRule($element ,$type ,$min = 0 ,$max = 0){
        $this->rules[$element] = array('type'=>$type,'min' =>$min, 'max' => $max);
        return $this;
    }
    
    public function getError(){
        return $this->errors;
    }
    
    public function getResult(){
        return $this->result;
    }
   
    
    public function run(){
        foreach ($this->rules as $element => $value){    
            switch ($value['type']) {
                case 'numeric':
                    $this->validateInt($element,$value['min'],$value['max']);
                    break;
                case 'string':
                    $this->validateString($element,$value['min'],$value['max']);
                    break;
                case 'url':
                    $this->validateUrl($element);
                    break;
                case 'email':
                    $this->validateEmail($element);
                    break;
                case 'password':
                    $this->validatePassword($element);
                    break;
                case 'date':
                    $this->validateDate($element);
                    break;
            } 
        }
        $valueNotValidate = array_diff_key($this->sources, $this->errors);
        $this->result = array_merge($this->result,$valueNotValidate);
    }
    
    //validete integer
    private  function  validateInt($element, $min = 0, $max = 0){
        if($this->sources[$element] ==""){
            $this->errors[$element] ="'$element' Không được để trống!";
        }else{
            if(!filter_var($this->sources[$element],FILTER_VALIDATE_INT,array("options" =>array("min_range" => $min,"max_range" =>$max)))){
                $this->errors[$element] ="'" . $this->sources[$element] . "'" . " phải nằm trong đoạn $min đến $max";
            }
        }
    }
    // check string
    private  function  validateString($element, $min = 0, $max = 0){
        $leng = strlen($this->sources[$element]);
        if($leng == 0){
            $this->errors[$element] ="'$element' Không được để trống!";
        }else{
            if ($leng < $min){
                $this->errors[$element] ="'" . $this->sources[$element] . "'" . "  quá ngắn.";
            }elseif ($leng > $max){
                $this->errors[$element] ="'" . $this->sources[$element] . "'" . " quá dài.";
            }  elseif (!is_string($this->sources[$element])){
                $this->errors[$element] = "'" . $this->sources[$element] . "'" . " không phải chuỗi";
            }
        }   
    }
    // check url
    private  function  validateUrl($element){
        if(!filter_var($this->sources[$element],FILTER_VALIDATE_URL)){
            $this->errors[$element] = "không phải url";
        } 
    }
    //check email
    private  function  validateEmail($element){
        if(!filter_var($this->sources[$element],FILTER_VALIDATE_EMAIL)){
            $this->errors[$element] ="không phải email";
        } 
    } 
    private  function  validatePassword($element){
        if($this->sources[$element] ==""){
            $this->errors[$element] ="'$element' Không được để trống!";
        }
        else{
            $pattern ='#^[a-z][a-zA-Z0-9]{1,8}$#';
            if(preg_match($pattern, $this->sources[$element])==0){
                $this->errors[$element] ="'$element' không hợp lệ.([a-z][a-zA-Z0-9]{1,8} and unsigned).";
            }
        }
    }
    private  function  validateDate($element){
        if($this->sources[$element] ==""){
            $this->errors[$element] ="'$element' Không được để trống!";
        }
    }
    public  function showError(){
        $html ="";
        if(!empty($this->errors)){
            $html .='<ul class ="error">';
            foreach ($this->errors as $keys =>$values){
                $html .='<li><b>'. ucfirst($keys) .'</b>:' . $values .'</li>';
            }
            $html .='</ul>';
        }
        return $html;
    }
    
}