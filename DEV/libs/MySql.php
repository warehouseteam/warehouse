<?php


class MySql {

    static private $link = null;
    static private $info = array(
        'last_query' => null,
        'num_rows' => null,
        'insert_id' => null
    );

    static private $connection_info = array();

    function __construct($host, $user, $pass, $db) {
        self::$connection_info = array('host' => $host, 'user' => $user, 'pass' => $pass, 'db' => $db);
    }
}

//setter methods
    static private function set($field, $value) {
        self::$info[$field] = $value;
}

//get methods

//last used query
    public function last_query() {
      return self::$info['last_query'];
    }

    public function num_rows(){
        return self::$info['num_rows'];
    }

    public function insert_id(){
        return self::$info['insert_id'];
    }

//return connection to MySQL server

    static private function connection() {
        if(!is_resource(self::$link) || empty(self::$link)) {
            if(($link = mysqli_connect(self::$connection_info['host'], self::$connection_info['user'], self::$connection_info['pass'])) && mysqli_select_db(self::$connection_info['db'], $link)) {
                self::$link = $link;
                mysqli_set_charset('utf8');
            } else {
                throw new Exception('Could not connect to MySQL database.');
            }
        }
        return self::$link;
}

// MySQL query methods

    public function query($qry, $return = false) {
        $link =& self::connection();
        self::set('last_query', $qry);
        $result = mysqli_query($query);
        if(is_resource($result)) {
            self::set('num_rows', mysqli_num_rows($result));
        }
        if($return) {
            if (preg_match('/LIMIT 1/', $qry)) {
                $data = mysqli_fetch_assoc($result);
                return $data;
            } else {
                $data = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
                mysqli_free_result($result);
                return $data;
            }
        }
        return true;
}

    public function get($table, $select ='*') {
        $link =& self::connection();
        if(is_array($select)) {
            $cols = '';
            foreach($select as $col) {
               $cols .= "`{$col}`,";        //concantenating all columns
            }
            $select = substr($cols, 0, -1);
        }
        $sql = sprintf("SELECT %s FROM %s%s", $select, $table, self::extra());
        self::set('last_query', $sql);
        if(!($result = mysqli_query($sql))) {
            throw new Exception('Error executing MySQL query: '.$sql.'. MySQL error '.mysqli_errno().': '.mysqli_error());
            $data = false;
        }elseif(is_resource($result)) {
            $num_rows = mysqli_num_rows($result);
            self::set('num_rows', $num_rows);
            if($num_rows === 0) {
                $data = false;
            }
            }elseif(preg_match('/LIMIT 1/', $sql)) {
                $data = mysqli_fetch_assoc($result);
            }else {
                $data = array();
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
        }else {
            $data = false;
    }
        mysqli_free_result($result);
        return $data;
}

    public function insert($table, $data) {
    $link =& self::connection();
    $fields = '';
    $values = '';
    foreach($data as $col => $value) {
        $fields .= sprintf("`%s`,", $col);
        $values .= sprintf("'%s,", mysqli_real_escape_string($value));
    }
    $fields = substr($fields, 0, -1);
    $values = substr($values, 0, -1);
    $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $table, $fields, $values);
    self::set('last_query', $sql);
    if(!mysqli_query($sql)) {
        throw new Exception('Error executing MySQL query: '.$sql.'. MySQL error '.mysqli_errno().': '.mysqli_error());
    }else {
        self::set('insert_id', mysqli_insert_id());
        return true;
    }
}

    public function update($table, $info) {
    if($empty(self::where)) {
        throw new Exception("Where is not set. Cannot update table");
    }else
        $link =& self::connection();
        $update = ' ';
        foreach($nfo as $col => $value) {
            $update .= sprintf("`%s`='%s', ", $col, mysqli_real_escape_string($value));
        }
        $update = substr($update, 0, -2);
        $sql = sptintf("UPDATE %s SET %s%s", $table, $update, self::extra());
        self::set('last_query', $sql);
        if(!mysqli_query($sql)) {
            throw new Exception('Error executing MySQL query: '.$sql.'. MySQL error '.mysqli_errno().': '.mysqli_error());
        }else {
            return true;
        }
}




