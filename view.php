<?php
include_once "pdo.php";
include_once "function.php";

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
$row = getProfile($pdo);
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

$stmt = $pdo->prepare("SELECT COUNT(*) FROM position WHERE profile_id=:aid");
$stmt->execute(array(':aid'=> $_GET['profile_id']));
$count = $stmt->fetch(PDO::FETCH_ASSOC);

if($count["COUNT(*)"] >0){
  echo "<p>Position";
  echo "<ul>";
  $stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id=:aid");
  $stmt->execute(array(':aid'=> $_GET['profile_id']));
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo '<li> '.htmlentities($row["year"]).' : '.htmlentities($row["description"]).'</li>';
  }
  echo "</ul>";
  echo "</p>";
}


$stmt = $pdo->prepare("SELECT COUNT(*) FROM education WHERE profile_id=:aid");
$stmt->execute(array(':aid'=> $_GET['profile_id']));
$count = $stmt->fetch(PDO::FETCH_ASSOC);


if($count["COUNT(*)"] >0){
  echo "<p>Education";
  echo "<ul>";
  $stmt = $pdo->prepare("SELECT e.year as yearS, i.name as nameS FROM Education AS e INNER JOIN Institution AS i ON e.institution_id = i.institution_id WHERE profile_id=:aid");
  $stmt->execute(array(':aid'=> $_GET['profile_id']));
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo '<li> '.htmlentities($row["yearS"]).' : '.htmlentities($row["nameS"]).'</li>';
  }
  echo "</ul>";
  echo "</p>";
}


?>
<br>
<a href="./index.php">Done</a>
</body>
</html>