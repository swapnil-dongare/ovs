<?php
session_start();
if(!isset($_SESSION['userEro']))
{
    header("location:/OVS/views/Login/eroLogin.php");
}
