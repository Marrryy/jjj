<?php
include_once "pdo.php";
session_start();

if ( !isset($_POST['profile_id']) ) return;
$stmt = $pdo->prepare("SELECT COUNT(*) FROM position WHERE profile_id=:aid");
$stmt->execute(array(':aid'=> $_POST['profile_id']));
$count = $stmt->fetch(PDO::FETCH_ASSOC);

$result = $count === false ? 0 : $count['COUNT(*)']; 

echo $result ;
