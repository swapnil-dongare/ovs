<?php 
include 'DB.php';
    class Admin{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname ="ovs";
    private $conn = null;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password,$this->dbname);

        } catch (Exception $th) {
            echo "Connection failed :". $th->getMessage;
        }
    }

    public function registerEro($user,$pass,$taluka,$admin)
    {
        try {
            $query = DB::insert( "INSERT INTO ero (username,password,taluka,admin_id) VALUES ('$user','$pass','$taluka','$admin')");
                       
            if($query)
            {

              header("location:/OVS/views/Admin/ero.php");
              echo "<script>alert('successfulyy registered ERO !')</script>"; 
            }else{                
              echo "<script>alert('Please check records  !')</script>"; 
            }
        } catch (Exception $e) {
            echo "Failed to register". $e->getMessage();
        }

    }

    //function to be removed !
    public function getAllEroRecords()
    {
        
        try {
            $eroRecords = null;
            $query = "SELECT * FROM ero";
            if($sql = $this->conn->query($query))
            {
                while($row = mysqli_fetch_assoc($sql))
                {
                    $eroRecords[] =$row; 
                }
            }
            return $eroRecords;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    }


?>