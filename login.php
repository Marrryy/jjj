<?php
include_once "pdo.php";
session_start();

if(isset($_POST['who']) && isset($_POST['pass'])){
  // if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
  //   $_SESSION['error'] = 'Email and password are required';
  //   header("Location: login.php");
  //   return;
  // }
  if(!strpos($_POST['who'], '@')){
    $_SESSION['error'] = "Email must have an at-sign (@)";
    header("Location: login.php");
    return;
    error_log("Login fail ".$_POST['who']." $check");
  }
    $salt ='XyZzy12*_';
    $check = hash('md5', $salt.$_POST['pass']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:user AND password=:pass");
    $stmt->execute(array(':user'=> $_POST['who'], ':pass'=>$check ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(count($row)< 1){
      $_SESSION['error'] = "Not a member";
      header("Location: login.php");
      return;
    }
    if($row !== false){
        $_SESSION['name'] = $_POST['who'];
        $_SESSION['user_id'] = $row["user_id"];
        header("Location: index.php");
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
  <script type="text/javascript" src="doValidate.js"></script>
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
<input type="submit" onclick="return doValidate();" value="Log In">
<a href ="index.php">Cancel</a>
</form>
</body>
</html>