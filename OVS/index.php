<?php
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="./assets/images/logo.png">
  <title>OVS | Home</title>
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <script src="./bootstrap/js/jquery.slim.min.js"></script>
  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./bootstrap/js/bootstrap.min.js"></script>
  <script src="./bootstrap/js/popper.min.js"></script>
</head>

<body>

  <!-- Start Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <img src="./assets/images/logo.png" width="50" height="50" class="d-inline-block align-top ml-1" style="border:2px solid white;border-radius:10px;" alt="" loading="lazy">
    <a class="navbar-brand ml-2" href="#">Online Voting</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="/OVS">Home <span class="sr-only">(current)</span></a>
        </li>
        <?php
        session_start();
        if (isset($_SESSION['userAdmin']) || isset($_SESSION['userEro']) || isset($_SESSION['userVoter'])) {
        ?>
          <li class="nav-item active">
            <?php
            if (isset($_SESSION['userEro'])) {
              echo ' <a class="nav-link" href="/OVS/views/ERO/dashboard.php">Dashboard <span class="sr-only">(current)</span></a>';
            } elseif (isset($_SESSION['userAdmin'])) {
              echo ' <a class="nav-link" href="/OVS/views/Admin/dashboard.php">Dashboard <span class="sr-only">(current)</span></a>';
            }elseif(isset($_SESSION['userVoter'])){
              echo '<a class="nav-link" href="/OVS/views/Voter/profile.php">Profile <span class="sr-only">(current)</span></a>';
            } else {
              echo "null";
            }
            ?>
          </li>
        <?php
        } else {

        ?>
          <li class="nav-item active">
            <a class="nav-link" href="./views/Login/voterLogin.php">Voter Login </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="./views/Login/eroLogin.php">ERO Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./views/Login/candidateLogin.php">Candidate Login</a>
          </li>
        <?php
        }
        ?>
      </ul>
      <?php
      if (!isset($_SESSION['userAdmin']) && !isset($_SESSION['userEro']) && !isset($_SESSION['userVoter'])) {
      ?>
        <div style="float:right">
          <a href="./views/Login/adminLogin.php" class="btn btn-outline-secondary">admin</a>
        </div>
      <?php
      } else {
      ?>
        <div style="float:right">
          <a href="./Models/Logout.php?logout  " class="btn btn-outline-light">Logout</a>
        </div>
      <?php
      }
      ?>
    </div>
  </nav>

  <!-- End Navigation -->

  <!-- Start Carousol -->
  <div class="container mt-3" style="opacity:0.6">
    <div class="row">
      <div class="col">
        <div id="carouselExampleInterval" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <div class="carousel-item active" data-interval="10000">
              <img src="./assets/images/1.jpg" class="d-block w-100" height="500px" alt="...">
            </div>
            <div class="carousel-item" data-interval="2000">
              <img src="./assets/images/2.jpg" class="d-block w-100" height="500px" alt="...">
            </div>
            <div class="carousel-item">
              <img src="./assets/images/3.jpg" class="d-block w-100" height="500px" alt="...">
            </div>
            <div class="carousel-item">
              <img src="./assets/images/4.jpg" class="d-block w-100" height="500px" alt="...">
            </div>
            <div class="carousel-item">
              <img src="./assets/images/5.jpg" class="d-block w-100" height="500px" alt="...">
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </div>
  <!-- End Carousol -->
  <!-- OVS info -->
  <div class="container-fluid mt-5 mb-3">
    <div class="row">
      <div class="col-md-4 mt-5">
        <img src="./assets/images/OVS.png" class="img-fluid" alt="" srcset="">
      </div>
      <div class="col-md-8">
        <p>
          <strong style="font-size:30px">
            Online Voting System
          </strong>
        </p>

        <p> Online voting systems are software platforms used to securely conduct votes and elections. As a digital platform, they eliminate the need to cast your votes using paper or having to gather in person. </p>

        <p>They also protect the integrity of your vote by preventing voters from being able to vote multiple times.</p>

        <p>Many secure voting platform vendors provide supportive vote management consulting services that help organizations design and implement their voting procedures.</p>

        <p>These services help organizations save time, stick to best practices, and meet internal requirements and/or external regulations, such as third-party vote administration needs .</p>

      </div>
    </div>
  </div>
  <!-- OVS info End -->


</body>

</html>
