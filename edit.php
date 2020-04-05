<?php
include_once "pdo.php";
session_start();
if(!isset($_SESSION['name'])){
    die("ACCESS DENIED");
}

if(!isset($_GET['profile_id'])){
    $_SESSION['error']="Bad value for id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id=:aid");
$stmt->execute(array(':aid'=> $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row === false){
    $_SESSION['error']="Bad value for id";
    header('Location: index.php');
    return;
}



if(isset($_POST['first_name']) && isset($_POST['last_name']) 
&& isset($_POST['email']) && isset($_POST['headline'])){

  if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1) {
    $_SESSION['error'] = 'All fields are required';
    header("Location: edit.php?".$_GET['profile_id']);
    return;
  }
      $stmt = $pdo->prepare('UPDATE profile SET first_name=:fn,
      last_name=:ln, email=:em, headline=:he, summary=:su
      WHERE profile_id = :id');
      $stmt->execute(array(
        ':id' => $_GET['profile_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
      );
      print_r($stmt);
      $_SESSION['success'] = "Record inserted";
      header("Location:index.php");
      return;
  } 

$first = htmlentities($row['first_name']);
$last = htmlentities($row['last_name']);
$emai = htmlentities($row['email']);
$headl = htmlentities($row['headline']);
$summa = htmlentities($row['summary']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Mary Franklin - Automobile Tracker</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
<h1>Editing Profile for "
<?php 
echo htmlentities($_SESSION['name']);
?>
"</h1>

<?php
if ( isset($_SESSION['error']) ) {
  echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
  unset($_SESSION['error']);
}
?>

</p>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value=<?=$first?>/></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value=<?=$last?>/></p>
<p>Email:
<input type="text" name="email" size="30" value=<?=$emai?>/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value=<?php echo $headl;?>/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80" ><?=$summa?></textarea>
<p>
<input type="submit" value="edit">
<input type="submit" name="cancel" value="cancel">
</form>


</body>
</html>