<?php
include_once "pdo.php";
session_start();
if(!isset($_SESSION['name'])){
  die("Not logged in");
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
<h1>Tracking Autos for "
<?php 
echo htmlentities($_SESSION['name']);
?>
"</h1>
<?php
if(isset($_SESSION['success'])){
    echo '<p style="color: green;">'.$_SESSION['success'].'</p>';
    unset($_SESSION['success']);
}
?>
<h2>Automobiles</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT * FROM autos");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  echo "<li>".$row['year']." ".$row['make']." / ".$row['mileage']."</li>";
}

?>
</ul>
<a href="./add.php">Add New</a> | 
<a href="./logout.php">Logout</a> 
</body>
</html>