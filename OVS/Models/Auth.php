<?php

class Auth{
    
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

    public  function user()
    {
        $conn = new mysqli("localhost", "root", "","ovs");
        if(isset($_SESSION['userAdmin']))
        {
            $query = "SELECT * FROM admin WHERE id = ".$_SESSION['userAdmin'][0]['id'];
            $select = $conn->query($query);
            $res = $select->fetch_assoc();

            return $res;
        }elseif(isset($_SESSION['userEro']))
        {
            $query = "SELECT * FROM ero WHERE id = ".$_SESSION['userEro']['id'];
            $select = $conn->query($query);
            $res = $select->fetch_assoc();

            return $res; 
        }elseif(isset($_SESSION['userVoter']))
        {
            $query = "SELECT * FROM voter WHERE id = ".$_SESSION['userVoter']['id'];
            $select = $conn->query($query);
            $res = $select->fetch_assoc();

            
            return $res; 
        }else{
            return "Unauthorized user or user not logged in !";
        }
    }
    
    public function AuthAdmin($user,$pass){
        $query = "SELECT * FROM admin WHERE username = '$user' AND password = '$pass'";

        $result = $this->conn->query($query);
        $userData =null;

        if($result->num_rows > 0)
        {

            session_start();
            while($row = $result->fetch_assoc())
            {
                $userData[]=$row;
                $_SESSION['userAdmin'] = $userData;
                header("location:/OVS/views/Admin/dashboard.php");

            }
        }else{

                session_start();
                $_SESSION['error'] = 'Please Check Credentials !';
                header("location:/OVS/views/Login/adminLogin.php");
        }

    }

    public function AuthEro($user,$pass)
    {
        
        try {
            $query = "SELECT * FROM ero WHERE username = '$user' AND password = '$pass'";

            $result = $this->conn->query($query);
            $eroData = null;

            if($result->num_rows > 0)
            {

                session_start();
                while($row = $result->fetch_assoc())
                {
                    $eroData = $row;
                    $_SESSION['userEro'] = $eroData;
                    header("location:/OVS/views/ERO/dashboard.php");
                }
            }else{

                    session_start();
                    $_SESSION['error'] = 'Please Check Credentials !';
                    header("location:/OVS/views/Login/eroLogin.php");
            }

        } catch (Exception $e) {
            return "Failed :". $e->getMessage();
        }

    }
    public function authVoter($voterId,$password)
    {
        try {

            $selectVoter = "SELECT * FROM voter WHERE voter_id = '$voterId'";
            $selectVoterRes = $this->conn->query($selectVoter);
            $selectVoterDetails = $selectVoterRes->fetch_assoc();

            $query = null;


            if($selectVoterDetails['password'] == null)
            {
                $query = "SELECT * FROM voter WHERE voter_id = '$voterId' AND dob = '$password'";                
            }else{
                $query = "SELECT * FROM voter WHERE voter_id = '$voterId' AND password = '$password'";                
            }

            
            $result = $this->conn->query($query);
            $voterData = null;
            if($result->num_rows > 0)
            {
                session_start();
                while($row = $result->fetch_assoc())
                {
                    $voterData = $row;
                    $_SESSION['userVoter'] = $voterData;
                    
                    header("location:/OVS/views/Voter/profile.php");
                }
            }else{
                $_SESSION['error']="please check voter id and password / dob (yyyy-mm-dd)";
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

  
}
