<?php
include_once "pdo.php";
session_start();

if(isset($_POST['who']) && isset($_POST['pass'])){
//   $_SESSION['error'] = "Email and password are required";
//   header("Location: login.php");
//   return;
// }else {
  if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
    $_SESSION['error'] = 'Email and password are required';
    header("Location: login.php");
    return;
}
  if(!strpos($_POST['who'], '@')){
    $_SESSION['error'] = "Email must have an at-sign (@)";
    header("Location: login.php");
    return;
    error_log("Login fail ".$_POST['who']." $check");
  }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=:user");
    $stmt->execute(array(':user'=> $_POST['who']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(count($row)< 1){
      $_SESSION['error'] = "Not a member";
      header("Location: login.php");
      return;
    }
      // print_r ($row);
      if($row['password']==$_POST['pass']){
        // echo "you are right";
        $_SESSION['name'] = $_POST['who'];
        // header("Location: autos.php?name=".urlencode($_POST['who']));
        // header("Location: view.php");
        header("Location: index.php");
        echo $_SESSION['name'];
        error_log("Login success ".$_POST['who']);
      }else{
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
        error_log("Login fail ".$_POST['who']." $check");
      }
    // }
  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Please Login</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
  unset($_SESSION['error']);
}
?>
<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="who" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<!-- <input type="submit" name="cancel" value="Cancel"> -->
<a href ="index.php">Cancel</a>
</form>
</body>
</html>