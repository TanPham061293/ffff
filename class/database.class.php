<meta content ="text/html; charset = utd-8" http-equiv ="content-Type">
<?php
class Database{
    protected $connect;
    protected $database;
    protected $table;
    protected $result; 
    
    public function __construct(){  
       $link = mysqli_connect("localhost","root","");
       if (!$link){
           echo "Unable to access the database.";
           die();
       }else {
           $this->connect =$link;   
       }
    }
    public  function setConnect(array $connect){
        $this->connect =$connect;
    }
    public  function setTable(string $table = null){
        $this->table =$table;
    }
    public  function setDatabase(string $database =null){
        if ($database != null){
            $this->database = $database;
        }
        mysqli_select_db( $this->connect,$this->database);
    }  
    
    public  function destruct(){
        mysqli_close($this->connect);
    }
    
    public  function insertDatabase($data, $type = 'single'){
        if ($type =='single'){
            $newQuery =$this->createInsertQuery($data);
            $query = "INSERT INTO $this->table (".$newQuery['cols'] .") VALUES (".$newQuery['vals'] .")";
            mysqli_query($this->connect, $query);
           // echo $query;
        }  
    }
   
    public function createInsertQuery($data){
        $newQuery =array();
        $columns="";
        $vals ="";
        if (!empty($data)){
            foreach ($data as $keys => $values){
                $columns .=", `$keys`";
                $vals    .=", '$values'";
            }
        }
        $newQuery['cols'] = ltrim($columns,',');
        $newQuery['vals'] = ltrim($vals,',');
        return  $newQuery;
    }
    public function updateDatabase($data ,$where){
        $setQuery = $this->createUpdate_Query($data);
        $whereQuery =$this->create_Where($where);
        $query ="UPDATE "."$this->table"." SET $setQuery WHERE $whereQuery";
        mysqli_query($this->connect, $query);
      // echo $query;
    }
    public function createUpdate_Query($data){
        $newQuery =""; 
        if (!empty($data)){
            foreach ($data as $keys => $values){
                $newQuery .= ",`$keys`  = '$values'";
            }
        }
        $newQuery = ltrim($newQuery,',');
        return  $newQuery;
    }
    public function create_Where($data){
        $newQuery ="";
        if (is_array($data)){
            foreach ($data as $keys => $values){
                if ($newQuery != ""){
                    $newQuery .= ' AND ';
                }
                $newQuery .="`$keys` = '$values'";
            }
        }elseif (is_string($data)){
            $newQuery .= "$data";
            //id IN ('5','6') 
        }
        
        return $newQuery;
    }
   
    public function deleteQuery($where){
        $whereQuery =$this->create_Where($where);
        $query = "DELETE FROM $this->table WHERE $whereQuery";
        mysqli_query($this->connect, $query);
        //echo $query;
    }
    public function selectQuery($query){
        $this->result = mysqli_query($this->connect , $query);
        $result = $this->listRecord($this->result);
        return $result;
    }
    public function listRecord($resultQuery){
        $result =array();
        while ($row = mysqli_fetch_assoc($resultQuery)){
            $result[] =$row;
        }
        mysqli_free_result($resultQuery); // giáº£i phÃ³ng
        return $result;
    }
    
    public function affectRow(){
        $row = mysqli_affected_rows($this->connect);
        return $row;
    }
}