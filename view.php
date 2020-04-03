<?php
include_once "pdo.php";
session_start();

if(!isset($_GET['profile_id'])){
 $_SESSION['error'] = "Missing profile_id";
 header("Location: index.php");
 return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mary Franklin - Automobile Tracker</title>
</head>
<body>
<div class="container">
<h1>Profile information</h1>
<?php
$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id=:aid");
$stmt->execute(array(':aid'=> $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row === false){
    $_SESSION['error']="Could not load profile";
    header('Location: index.php');
    return;
}
echo "First Name: ".htmlentities($row['first_name']);
echo "<br><br>Last Name: ".htmlentities($row['last_name']);
echo "<br><br>Email: ".htmlentities($row['email']);
echo "<br><br>Headline: ".htmlentities($row['headline']);
echo "<br><br>Summary: ".htmlentities($row['summary']);
?>
<br>
<a href="./index.php">Done</a>
</body>
</html>