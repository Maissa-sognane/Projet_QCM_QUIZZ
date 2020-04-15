<?php
session_start();
unset($_SESSION['connecte_joueur']);
header('location: login.php');
