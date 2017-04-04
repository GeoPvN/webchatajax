<?php 
include 'conf.php';

class  dbClass{
    
    public $mysqli;
    
    public $host;
    public $port;
    public $user;
    public $password;
    public $db;
    
    public $charset;
    
    public $query;   
    
    /**CONSTRUCTOR
     * 
     * @param string  $host
     * @param string  $user
     * @param string  $password
     * @param string  $db
     * @param integer $port
     * @param string  $charset
     * @throws Exception
     */
    function dbClass($host=SERVERGLOBALIP, $user=MYSQLUSER, $password=MYSQLPASS, $db=MYSQLDB, $port=MYSQLPORT, $charset=MYSQLCHARSET){
        
        $this->host     = $host;
        $this->port     = $port;
        $this->user     = $user;
        $this->password = $password;       
        $this->db       = $db;
        
        $this->charset  = $charset;
        
        if (empty($this->host) || empty($this->user) || empty($this->password) || empty($this->db)) {
            throw new Exception('Empty Parameters');
        }
        
        $this->mysqli = new mysqli($this->host, $this->user, $this->password, $this->db, $this->port);
        
        /* check connection */
        if ($this->mysqli->connect_error) {
            throw new Exception('Connect Error ' . $this->mysqli->connect_errno . ': ' . $this->mysqli->connect_error);
        }
        
        /* check charset use */
        if ($this->mysqli) {
            $this->mysqli->set_charset($this->charset);
        }else{
            throw new Exception('SET Charset ERROR');
        }
        
    }
    
    /**SET QUERY
     * 
     * @param string $query
     */
     function setQuery($query) {
        
        if ($query=='') {
            throw new Exception('MySQLi query empty');
        }else{
            $this->query = $query;
        }
    }
    
    /**Get Last INSERT Id
     *
     * 
     */
    function getLastId() {
    
        return $this->mysqli->insert_id;
    }
    
    /**EXECUTE MYSQL QUERY
     * 
     * @throws Exception
     */
    function execQuery() {
        
        $this->mysqli->query($this->query);
        
        if ($this->mysqli->error) {
            throw new Exception($this->mysqli->error);
        }else {
            return $this->mysqli->info;
        }
     }
     
    
    /**GET QUERY RESULT NUMERIC ARRAY
     *
     * @param INT $type
     * @throws Exception
     * @return array $fetchArray
     */
    function getResultArray($type=MYSQLI_ASSOC){
    
        $fetchArray = array();
        $result     = $this->mysqli->query($this->query);
    
        if ($result) {
    
            $start = microtime();
            $count = 0;
    
            $fetchArray['result'] = NULL;
            while($row = $result->fetch_array($type)){
                $fetchArray['result'][] = $row;
                $count++;
            }
    
            $fetchArray['time']  = round(microtime() - $start, 4);
            $fetchArray['count'] = $count;
    
            return $fetchArray;
    
        }else {
            throw new Exception($this->mysqli->error);
        }
    }
    
    /**GET JSON FROM ASSOC ARRAY RESULT
     *
     * @param INT $type
     * @return json $array
     */
    function getJson($type=ASSOC){
        
        if ($type == ASSOC) {
            $array = self::getResultArray(MYSQLI_ASSOC);
        }elseif ($type == NUM){
            $array = self::getResultArray(MYSQLI_NUM);
        }else{
            throw new Exception('Unknow type!');
        }
        
        $encodedArray   = json_encode($array, JSON_NUMERIC_CHECK);
        
        if ($encodedArray) {
            return $encodedArray;
        }else{
            throw new Exception('JSON ecode failed!');
        }
        
    }
    
    /**GET RESULT ROW NUMBERS
     * 
     * @throws Exception
     * @return integer $row
     */
    function getNumRow(){
        
        $result = $this->mysqli->query($this->query);
        
        if ($result) {
            $row = $result->num_rows;
            return $row;
        }else{
            throw new Exception($this->mysqli->error);
        }
    }
    
    /**GET TABLE CURRENT INCREMENT VALUE
     * 
     * @param  string  $table
     * @return integer $increment
     */
    function getIncrement($table){
    
        $result    = $this->mysqli->query("SHOW TABLE STATUS LIKE '$table'");
        $row       = $result->fetch_assoc();
        $increment = $row['Auto_increment'];
    
        return $increment;
    }
    
    /**SET TABLE INCREMENT VALUE
     * 
     * @param  string  $table
     * @return integer $increment
     */
    function setIncrement($table){
    
        $increment = self::getIncrement($table);
        $next_increment = $increment+1;
        $this->mysqli->query("ALTER TABLE $table AUTO_INCREMENT=$next_increment");
    
        return $increment;
    
    }
    
    /**GROUP RESULT BY COLUMN PARAMETER
     * 
     * @param  string $column
     * @return array  $groupArray
     */
    function groupBy($column){
        
        $groupArray = array();
        
        foreach (self::getFetchArray() as $row){
            
            $i   = 0;
            $key = 0;
            
            foreach ($groupArray as $grouprow){
                
                if ($row[$column] == $grouprow[$column]) {
                    $key = $i;
                }
                
                $i++;
            }
            
            if ( $key > 0 ) {
                $groupArray[$key]['count'] += 1;
             }else {
                $groupArray[] = $row;
            }
        }
        
        return $groupArray;
    }

    /**
     * disconnect mysql server
     */
    function disconnect(){
        
        $this->mysqli->close();
    }    
}

?>
