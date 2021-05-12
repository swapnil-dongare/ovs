<?php

include 'DB.php';

class Voter extends DB
{

    public function getActiveElectionsForRegister()
    {
        date_default_timezone_set("Asia/Calcutta");

        $query = "SELECT * FROM elections WHERE status =1 AND  election_area =" . $_SESSION['userVoter']['area'];
        $data = DB::select($query);
        $electionsForRegister = null;
        $res = null;
        $RegToday = new DateTime();
        $RegToday = $RegToday->format('y-m-d');
        if ($data != null) {
            for ($i = 0; $i < count($data); $i++) {

                if (strtotime($RegToday) >= strtotime($data[$i]['reg_start_date']) && strtotime($RegToday) <= strtotime($data[$i]['reg_end_date'])) {
                    $res[] = $data[$i];
                }
            }
        }


        return $res;
    }

    public function getActiveElectionsForVote()
    {
        date_default_timezone_set("Asia/Calcutta");


        $query = "SELECT * FROM elections WHERE status =1 AND  election_area =" . $_SESSION['userVoter']['area'];
        $data = DB::select($query);
        $electionsForVote = null;
        $res = null;
        $electionToday =  date("Y-m-d H:i:s");
        if ($data != null) {
            for ($i = 0; $i < count($data); $i++) {


                if (strtotime($electionToday) >= strtotime($data[$i]['election_start_date']) && strtotime($electionToday) <= strtotime($data[$i]['election_end_date'])) {
                    $res[] = $data[$i];
                }
            }
        }

        return $res;
    }

    public function changePassword($currentPass, $newPass, $confNewPass)
    {
        if ($newPass != $confNewPass || $newPass == NULL || $currentPass == NULL || $confNewPass == NULL) {
            $_SESSION['error'] = 'new password and confirm password does not match all fields area mandatory !!';
            header("location:/OVS/views/Voter/settings.php?error");
        } else {

            $user = $_SESSION['userVoter'];
            $userPass = null;
            $password = $user['password'];
            if ($password == NULL) {
                $userPass = $user['dob'];
            } else {
                $userPass = $password;
            }
            if ($userPass != $currentPass) {
                $_SESSION['error'] = 'old password and current password does not match or try with date of birth if have not change password yet !';
                header("location:/OVS/views/Voter/settings.php?error");
            } else {
                $query = "UPDATE `voter` SET `password` = '" . $confNewPass . "' WHERE `id`=" . $user['id'];

                if (DB::update($query)) {
                    $_SESSION['error'] = 'Password changes Successfully !';
                    return '
                    <script>
                    window.location.href = "/OVS/views/Voter/settings.php?success";
                    </script>
                ';
                } else {
                    $_SESSION['error'] = "something went wrong !";
                    header("location:/OVS/views/Voter/settings.php?uknownerror");
                }
            }
        }
    }

    public static function getAllApplicationsForElection()
    {
        $id = $_SESSION['userVoter']['voter_id'];
        $query = "SELECT * FROM `candidate_election` WHERE voter_id = $id ORDER BY candidate_id DESC ;";
        $data = DB::select($query);
        return $data;
    }
    public static function getApplicationsForElection($id)
    {
        $query = "SELECT elections.election_id AS election_id , elections.election_title AS election_title, elections.election_type AS election_type, elections.election_area AS election_area, elections.status AS election_status, elections.reg_start_date AS reg_start, elections.reg_end_date AS reg_end, elections.election_start_date AS election_start, elections.election_end_date AS election_end, candidate_election.candidate_id AS candidate_id,candidate_election.is_election_done AS is_election_done, candidate_election.vote_count AS vote_count, candidate_election.is_winner AS is_winner, candidate_election.status AS candidate_election_status, candidate_election.comment AS comment FROM candidate_election,elections WHERE candidate_election.election_id = elections.election_id AND candidate_election.candidate_id=$id";
        $data = DB::select($query);
        return $data;
    }

    public static function isValidVoter($eleId, $voter)
    {
        $query = DB::select("SELECT id FROM votes  WHERE election_id = $eleId AND voter_id = $voter");
        if ($query) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public static function getCandidateListForEletion($id)
    {
        $query = DB::select("SELECT elections.election_id AS election_id , elections.election_title AS election_title, candidate_election.candidate_id AS candidate_id , voter.voter_name AS voter_name , voter.area AS voter_area , voter.gender AS voter_gender, voter.profile_url AS voter_profile  FROM voter, candidate_election,elections WHERE voter.voter_id = candidate_election.voter_id AND candidate_election.election_id = elections.election_id AND candidate_election.election_id=$id AND candidate_election.status = 1");
        return $query;
    }

    public function voteForElection($eleId, $canId)
    {
        $voteId = $_SESSION['userVoter']['voter_id'];

        // echo "$eleId , $canId , $voteId";
        $query = DB::insert("INSERT INTO `votes` (`id`, `election_id`, `candidate_id`, `voter_id`) VALUES (NULL, '$eleId', '$canId', '$voteId')");
        if ($query) {
            echo '<script>
        alert("voted succefully");
        window.location.href= "/OVS/views/Voter/profile.php";
        </script>';
        }else{
            echo '<script>
        alert("something went wrong");
        window.location.href= "/OVS/views/Voter/profile.php";
        </script>';
        }
    }
}
