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
if(isset($_SESSION['name'])){

  if(isset($_SESSION['success'])){
    echo '<p style="color:green;">'.$_SESSION['success'].'</p>';
    unset($_SESSION['success']);
  }
  if(isset($_SESSION['error'])){
    echo '<p stye="color:red;">'.$_SESSION['error'].'</p>';
    unset($_SESSION['error']);
  }

  $stmt = $pdo->query("SELECT COUNT(*) FROM autos");
  $count = $stmt->fetchColumn();
  // print_r($count);
  // echo"<br><br>";
  // var_dump($count);
  // echo"<br><br>";
  // echo count($row["auto_id"]);

  if($count<1){
    // if(count($row["auto_id"])<1){
  echo "<br>No rows found";
  }else{
    echo "<br><table> <tr><td>Make</td><td>Model</td><td>Year</td><td>Mileage</td><td>Action</td></tr>";
  
    $stmt = $pdo->query("SELECT * FROM autos");
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
      echo "<tr>";
      echo "<td>".htmlentities($row['make'])."</td>";
      echo "<td>".htmlentities($row['model'])."</td>";
      echo "<td>".htmlentities($row['year'])."</td>";
      echo "<td>".htmlentities($row['mileage'])."</td>";
      echo '<td><a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> / ';
      echo '<a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a></td>';
      echo "</tr>";
    } 

  }
  echo '<p><a href="./add.php">Add New Entry</a></p>';
  echo '<p><a href="./logout.php">Logout</a></p>';

}else{
  echo '<a href="./login.php">Please Log in</a>
  <!-- <p>Attempt to go <a href="./view.php">view.php</a> without logging - it should fail with an error message.</p> -->
  <p>Attempt to go <a href="./add.php">add.php</a> without logging - it should fail with an error message.</p>
  <!-- <p>Note: Your implementation should retain data across multiple logout/login sessions. This sample implementation clears all its data on logout - which you should not do in your implementation.</p> -->';
}

?>

<p> Note: Your implementation should retain data across multiple logout/login sessions. This sample implementation clears all its data on logout - which you should not do in your implementation.</p>
</body>
</html>