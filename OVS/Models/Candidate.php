<?php

include 'DB.php';

class Candidate extends DB
{ 

    public function registerAsCandidate($voId, $eleId)
    {
        $select = DB::select("SELECT * FROM candidate_election WHERE voter_id = $voId AND election_id = $eleId");
        if ($select) {
            echo "<script>alert('already applied !');
            window.location.href='/OVS/views/Voter/profile.php';
            </script>";
            $query = "INSERT INTO `candidate_election` (`candidate_id`, `voter_id`, `election_id`, `vote_count`, `is_winner`, `status`, `comment`) VALUES (NULL, '$voId', '$eleId', NULL, NULL, NULL, NULL);";
        } else {
            $query = "INSERT INTO `candidate_election` (`candidate_id`, `voter_id`, `election_id`, `vote_count`, `is_winner`, `status`, `comment`) VALUES (NULL, '$voId', '$eleId', NULL, NULL, NULL, NULL);";
            $data = DB::insert($query);
            if ($data) {
                $_SESSION['error'] = "successfully apply !";
                echo "<script>
                    window.location.href='/OVS/views/Voter/profile.php';
                </script>";
            } else {
                $_SESSION['error'] = "unable to apply ! <br> Something went wrong please try again";
                echo "<script>
                    window.location.href='/OVS/views/Voter/profile.php';
                </script>";
            }
        }
    }
}
