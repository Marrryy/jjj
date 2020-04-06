<?php
include_once "pdo.php";
include_once "function.php";

session_start();
if(!isset($_SESSION['name'])){
    die("ACCESS DENIED");
}

if(!isset($_GET['profile_id'])){
    $_SESSION['error']="Bad value for id";
    header('Location: index.php');
    return;
}

$row = getProfile($pdo);
if($row === false){
    $_SESSION['error']="Bad value for id";
    header('Location: index.php');
    return;
}

if(isset($_POST['delete'])&& isset($_POST['profile_id'])){
    deleteProfile($pdo);
    $_SESSION['success']="Record deleted";
    header('Location: index.php');
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete</title>
</head>
<body>
<h1>Deleteing Profile</h1>
<p> First Name : <?=htmlentities($row["first_name"])?> </p>
<p> Last Name : <?=htmlentities($row["last_name"])?> </p>


<form method="post">
<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
<input type="submit" value="Delete" name="delete">
<a href="index.php">Cancel</a>
</form>


</body>
</html>


