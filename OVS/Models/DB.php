<?php

class DB
{
    protected $servername = "localhost";
    protected $username = "root";
    public $password = "";
    public $dbname = "ovs";
    public $conn = null;
    const name = null;

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }




    public static function select($query)
    {
        // return 'hi'; 
        $conn = new mysqli('localhost', 'root', '', 'ovs');
        $res = null;
        if ($que =  $conn->query($query)) {
            while ($result = $que->fetch_assoc()) {
                $res[] = $result;
            }
            return $res;
        } else {
            return 'please check the query';
        }
    }

    public static function insert($query)
    {
        $conn = new mysqli('localhost', 'root', '', 'ovs');
        $res = null;
        if ($que =  $conn->query($query)) {

            return [true, 'message' => 'Data inserted successfully !', 'status' => 200];
        } else {
            return 'please check the query';
        }
    }

    public static function update($query)
    {
        $conn = new mysqli('localhost', 'root', '', 'ovs');
        $res = null;
        if ($que =  $conn->query($query)) {

            return [true, 'message' => 'Data inserted successfully !', 'status' => 200];
        } else {
            return 'please check the query';
        }
    }

    public static function delete($query)
    {
        $conn = new mysqli('localhost', 'root', '', 'ovs');
        $res = null;
        if ($que =  $conn->query($query)) {

            return ['message' => 'Data deleted successfully !', 'status' => 200];
        } else {
            return 'please check the query';
        }
    }
}
