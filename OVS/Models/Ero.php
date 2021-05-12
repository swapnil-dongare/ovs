<?php

include 'DB.php';

class Ero
{

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "ovs";
    private $conn = null;

    public function __construct()
    {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        } catch (Exception $th) {
            echo "Connection failed :" . $th->getMessage;
        }
    }

    public function getAllVoterRecords()
    {
        try {
            $voterRecord = null;
            $ero = $_SESSION['userEro']['id'];
            $query = "SELECT * FROM voter WHERE ero_id = $ero";
            if ($sql = $this->conn->query($query)) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $voterRecord[] = $row;
                }
            }
            return $voterRecord;
        } catch (\Throwable $e) {
        }
    }


    public function addVoter($name, $dob, $address, $gender, $area, $profile)
    {
        try {
            $bday = new DateTime($dob);
            $today = new Datetime(date('y.m.d'));
            $diff = $today->diff($bday);
            $age = $diff->y;
            $voterId = rand(10000, 10000000000);
            if ($age < 18) {
                return "age is not 18 !";
            }



            $imageName = $profile['name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/OVS/storage/images/voter/profile/$imageName";
            if (move_uploaded_file($profile['tmp_name'], $targetPath)) {
                $profile_url = "http://localhost/OVS/storage/images/voter/profile/$imageName";
                echo "<Script>alert('uploaded profile !');</script>";
            } else {
                echo "<Script>alert('not uploaded profile !');</script>";
            }

            $eroId = $_SESSION['userEro']['id'];

            $query = "INSERT INTO `voter` (`id`, `voter_id`, `voter_name`, `dob`, `address`, `gender`, `area`, `profile_url`,`ero_id`) VALUES (NULL, '$voterId', '$name', '$dob', '$address', '$gender', '$area', '$profile_url','$eroId');";
            if ($res = $this->conn->query($query)) {

                echo '<script>alert("successfulyy registered Voter !");
                    window.location.href = "/OVS/views/ERO/voterlist.php";
                </script>';
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    //function to be removed !
    public function getAllElectionRecords()
    {
        try {
            $electionRecord = null;
            $eroId = $_SESSION['userEro']['id'];
            $query = "SELECT * FROM elections WHERE ero_id = $eroId ORDER BY status DESC";
            if ($sql = $this->conn->query($query)) {
                while ($row = mysqli_fetch_assoc($sql)) {
                    $electionRecord[] = $row;
                }
            }
            return $electionRecord;
        } catch (\Throwable $e) {
        }
    }

    public static function getElectionRecord($id)
    {
        $data = DB::select("SELECT  * FROM elections WHERE election_id= $id");
        return $data[0];
    }

    public static function getElectionCandidateRecord($id)
    {
        $data = DB::select("SELECT * FROM candidate_election WHERE election_id = $id ORDER BY is_winner DESC");
        return $data;
    }

    public static function getCandidateRecord($id)
    {

        $query = "SELECT voter.id AS id , voter.voter_id AS voter_id , voter.voter_name AS voter_name , voter.dob AS dob , voter.address AS address , voter.gender AS gender , voter.area AS area , voter.profile_url AS profile , candidate_election.candidate_id AS candidate_id , candidate_election.election_id AS election_id, candidate_election.vote_count AS vote_count , candidate_election.is_winner AS is_winner , candidate_election.status AS candidate_status, candidate_election.comment AS candidate_comment, elections.election_title AS election_title, elections.election_type AS election_type, elections.election_area AS election_area, elections.status AS election_status, elections.reg_start_date AS election_reg_start_date, elections.reg_end_date AS election_reg_end_date, elections.election_start_date AS election_start_date, elections.election_end_date AS election_end_date  FROM voter,candidate_election,elections WHERE candidate_election.voter_id=voter.voter_id AND candidate_election.candidate_id=$id AND elections.election_id=candidate_election.election_id;";

        $data = DB::select($query);

        return $data[0];
    }

    public function candidateApplicationStatus($comment, $canId, $accept)
    {
        $query = "UPDATE `candidate_election` SET `status` = '$accept', `comment` = '$comment' WHERE `candidate_election`.`candidate_id` = $canId";
        $data = DB::insert($query);
        if ($accept) {
            $_SESSION['error'] = "Application accepted successfully";
        } else {
            $_SESSION['error'] = "Application rejected ";
        }
        echo '
            <script>
                window.location.href ="/OVS/views/ERO/elections.php";
                
            </script>
           ';
    }

    public function addElection($title, $type, $area, $reg_start, $reg_end, $ele_start, $ele_end)
    {
        try {
            echo "$title <br> $type <br> $area <br> $reg_start <br> $reg_end <br> $ele_start <br> $ele_end";
            if ($reg_start > $reg_end) {
                $_SESSION['error'] = 'Invalid Registration dates';
                echo "<script>
                window.location.href='/OVS/views/ERO/addElection.php?error';
            </script>";
            } elseif ($ele_start > $ele_end) {
                $_SESSION['error'] = 'Invalid Election dates';
                echo "<script>
                window.location.href='/OVS/views/ERO/addElection.php?error2';
            </script>";
            } else {
                $ero_id = $_SESSION['userEro']['id'];
                $query = "INSERT INTO `elections` (`election_id`, `election_title`, `ero_id`, `election_type`, `election_area`, `status`, `reg_start_date`, `reg_end_date`, `election_start_date`, `election_end_date`) VALUES (NULL, '$title', '$ero_id', '$type', '$area', 1, '$reg_start', '$reg_end', '$ele_start', '$ele_end');";
                $res = $this->conn->query($query);
                echo "<script>
                window.location.href='/OVS/views/ERO/elections.php';
            </script>";
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function ElectionStatusManage($id)
    {
        $selectQuery = "SELECT status FROM elections WHERE election_id = $id";
        $sqlSelect = $this->conn->query($selectQuery);
        $sqlSelectStatusRes = mysqli_fetch_assoc($sqlSelect);

        $resToBeUpdate  = null;
        if ($sqlSelectStatusRes['status']) {
            $resToBeUpdate = 0;
        } else {
            $resToBeUpdate = 1;
        }

        $updateQuery = "UPDATE elections SET status=$resToBeUpdate WHERE election_id=$id";
        $updateRes = $this->conn->query($updateQuery);
        echo "<script>
                window.location.href='/OVS/views/ERO/elections.php';
            </script>";
    }

    public function resetVoterPassword($id)
    {
        $query = "UPDATE `voter` SET `password` = NULL WHERE `voter`.`voter_id` = $id";
        $sqlRun = $this->conn->query($query);
        print_r($sqlRun);
    }

    public function makeInactiveAllOutdatedElection()
    {
        $date = date('Y-m-d H:i:s');
        $selectQuery = " SELECT * FROM elections WHERE election_end_date < '$date'";

        $selectData = DB::select($selectQuery);

        foreach ($selectData as $eleData) {
            $updateQuery = "UPDATE elections SET status = 0 WHERE election_id = " . $eleData['election_id'];
            $updateCandi = "UPDATE candidate_election SET is_election_done = 1 WHERE election_id = " . $eleData['election_id'];

            $dataUpdateFirst = DB::update($updateQuery);
            $dataUpdateSec = DB::update($updateCandi);
        }

        $_SESSION['error'] = "Inactivated all outdated elections !";
        echo '
            <script>
                window.location.href = "/OVS/views/ERO/elections.php";
            </script>
        ';
    }
    public function getOngoingElections()
    {
        $date = date('Y-m-d H:i:s');

        $ero = $_SESSION['userEro']['id'];
        $query = "SELECT * FROM elections WHERE election_start_date >= '$date' AND election_end_date <= '$date' AND ero_id = $ero";
        $data = DB::select($query);
        return $data;
    }

    public function getUpcomingElections()
    {
        $date = date('Y-m-d H:i:s');
        $ero = $_SESSION['userEro']['id'];
        $query = "SELECT * FROM elections WHERE election_start_date >'$date' AND election_end_date > '$date' AND ero_id = $ero";
        $data = DB::select($query);
        return $data;
    }

    public function getDoneElection()
    {
        $date = date('Y-m-d H:i:s');
        $ero = $_SESSION['userEro']['id'];
        $query = "SELECT * FROM elections WHERE election_start_date < '$date' AND election_end_date <  '$date' AND ero_id=$ero";
        $data = DB::select($query);
        return $data;
    }

    public  function isResultGenerated($id)
    {

        $date = date('Y-m-d H:i:s');
        // $validEle = DB::select("SELECT * FROM elections WHERE election_start_date < '$date' AND election_end_date <  '$date' AND election_id = $id");
        $data = DB::select("SELECT * FROM results WHERE election_id = $id");

        if ($data != NULL) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function declareWinner($id)
    {
        //unable to handle if there is two same vote count 
        $winner = DB::select("SELECT * FROM candidate_election WHERE vote_count IN (SELECT MAX(vote_count) FROM candidate_election WHERE election_id= $id)");
        $winCandiId = $winner[0]['candidate_id'];

        print_r($winner);
        $updateWin = DB::update("UPDATE candidate_election SET is_winner=1 WHERE candidate_id = $winCandiId");
        return TRUE;
    }

    public function generateResult($id)
    {
        $isResultGen =   $this->isResultGenerated($id);

        if ($isResultGen) {
            $_SESSION['error'] = "Result Genereted successfully !";
            echo "Rsult is already generated";
        } else {
            $candidate = DB::select("SELECT * FROM candidate_election WHERE election_id= $id");
            foreach ($candidate as $candi) {
                $candiId = $candi['candidate_id'];
                $vote_count = DB::select("SELECT COUNT(id) AS vcount FROM votes WHERE election_id = $id AND candidate_id = $candiId");


                $vote_count = $vote_count[0]['vcount'];
                $updateCandi = DB::update("UPDATE candidate_election SET vote_count = $vote_count WHERE candidate_id =  $candiId");

                DB::update("UPDATE candidate_election SET is_election_done = 1 WHERE candidate_id = $candiId");

                $eroId = $_SESSION['userEro']['id'];
                $create_result = DB::insert("INSERT INTO `results` (`id`, `election_id`, `candidate_id`, `ero_id`, `no_of_votes`) VALUES (NULL, '$id', '$candiId', '$eroId', '$vote_count')");

                $_SESSION['error'] = "Result Genereted successfully !";
            }
            $this->declareWinner($id);
            echo "
                <script>
                    window.location.href = '/OVS/views/ERO/electiondetails.php?eleID=$id';
                </script>
            ";
        }
    }
}

/*

<form action="" method="post" enctype="multipart/form-data">
  <table border="1px">
    <tr><td><input type="file" name="image" ></td></tr>
    <tr><td> <input type="submit" value="upload" name="btn"></td></tr>
  </table>
</form>

 <?php
   if(isset($_POST['btn'])){
     $image=$_FILES['image']['name']; 
     $imageArr=explode('.',$image); //first index is file name and second index file type
     $rand=rand(10000,99999);
     $newImageName=$imageArr[0].$rand.'.'.$imageArr[1];
     $uploadPath="uploads/".$newImageName;
     $isUploaded=move_uploaded_file($_FILES["image"]["tmp_name"],$uploadPath);
     if($isUploaded)
       echo 'successfully file uploaded';
     else
       echo 'something went wrong'; 
   }

 ?>

 */