<?php
include_once "pdo.php";
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mary Franklin - Autos Database</title>
</head>
<body>
  <h1>Mary Franklin's Resume Registry</h1>

<?php
$stmt = $pdo->query("SELECT COUNT(*) FROM profile");
$count = $stmt->fetchColumn();

if(isset($_SESSION['name'])){
  echo '<p><a href="./logout.php">Logout</a></p>';
  if(isset($_SESSION['success'])){
    echo '<p style="color:green;">'.$_SESSION['success'].'</p>';
    unset($_SESSION['success']);
  }

  if(isset($_SESSION['error'])){
    echo '<p stye="color:red;">'.$_SESSION['error'].'</p>';
    unset($_SESSION['error']);
  }
  
  if($count>0){
    echo "<br><table> <tr><td>Name</td><td>Headline</td><td>Action</td></tr>";
  
    $stmt = $pdo->query("SELECT * FROM profile");
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      echo "<tr>";
      echo '<td><a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a></td>';
      echo "<td>".htmlentities($row['headline'])."</td>";
      echo '<td><a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ';
      echo '<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a></td>';
      echo "</tr>";
    }
  }else{
    echo "No rows found";  
  }
  echo '<p><a href="./add.php">Add New Entry</a></p>';
  
}else{
  echo '<a href="./login.php">Please Log in</a>';
  if($count>0){
    echo "<br><table> <tr><td>Name</td><td>Headline</td></tr>";
    
    $stmt = $pdo->query("SELECT * FROM profile");
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      echo "<tr>";
      echo '<td><a href="view.php?profile_id="'.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</td>';
      echo "<td>".htmlentities($row['headline'])."</td>";
      echo "</tr>";
      echo '<p><a href="./add.php">Add New Entry</a></p>'; 
    }
  }
}
?>
<p> Note: Your implementation should retain data across multiple logout/login sessions. This sample implementation clears all its data on logout - which you should not do in your implementation.</p>
</body>
</html>