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

if(isset($_POST['first_name']) && isset($_POST['last_name']) 
&& isset($_POST['email']) && isset($_POST['headline'])
&& isset($_POST['summary'])){

  // validating Profile
  $check = validateProfile();
  if($check){
    $_SESSION['error'] = $check;
    header("Location: edit.php?".$_GET['profile_id']);
    return;
  }

  $check = validatePosition();
  // print_r($check);
  if($check){
    $_SESSION['error'] = $check;
    header("Location: add.php");
    return;
  }    

  updateProfile($pdo);
  deletePosition($pdo);

  for($i=1; $i<=9;$i++){

    if ( ! isset($_POST['year'.$i]) ) continue;
    if ( ! isset($_POST['desc'.$i]) ) continue;

    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];
    insertPosition($pdo,$_GET['profile_id'],$i,$year,$desc);

  }

  $_SESSION['success'] = "Record inserted";
  header("Location:index.php");
  return;

  } 

$first = htmlentities($row['first_name']);
$last = htmlentities($row['last_name']);
$emai = htmlentities($row['email']);
$headl = htmlentities($row['headline']);
$summa = htmlentities($row['summary']);
$proid = $_GET['profile_id'];

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
  <!-- <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script> -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>

</head>
<body>
<div class="container">
<h1>Editing Profile for "
<?php 
echo htmlentities($_SESSION['name']);
?>
"</h1>

<?php
flashMessage();
?>

</p>
<form method="post" id="form">
<input type="hidden" name="proid" value=<?=$proid?>/></p>

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
</p>
<p>Position:<br/>
<input type="button" id="addPos" value="+">
<div id="position_fields">

<?php 
  $stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id=:aid");
  $stmt->execute(array(':aid'=> $_GET['profile_id']));

  // if($rowPos !== false){
  // var_dump($rowPos);
  while($rowPos = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo '<div id="position'.$rowPos["rank"].'">';
    echo '<p>Year : <input type="text" name="year'.$rowPos["rank"].'" value="'.$rowPos["year"].'" >';
    echo '<input type="button" value="-" onClick=$("#position'.$rowPos["rank"].'").remove(); return false; >';
    echo '</p>  ';
    echo '<textarea name="desc'.$rowPos["rank"].'" rows="8" cols="80">'.$rowPos["description"].'</textarea>';
    echo '</div>';
  }
// }

?>

</div>
<p>

<input type="submit" value="edit">
<input type="submit" name="cancel" value="cancel">
</form>
<script>
countpos = 0;

$(document).ready(function(){
  window.console && console.log("the page is ready");

  $("#addPos").click(function(event){
    event.preventDefault();
    if(countpos>=9){
      alert("Maximum of nine position entries exceeded");
      return;
    }
    window.console && console.log("make position " + countpos);

    countpos++;

    echo = '<div id="position'+countpos+'"> \
    <p>Year : <input type="text" name="year'+countpos+'" > \
    <input type="button" value="-" onClick=$("#position'+countpos+'").remove(); return false; > \
    </p>  \
    <textarea name="desc'+countpos+'" rows="8" cols="80"></textarea> \
    </div>';
    $('#position_fields').append(echo);
  }); 

  var proid = $('#form').find('input[name="proid"]').val();
  $.post('countPosition.php', { 'profile_id' : proid },
      function( data ) {
          window.console && console.log("the countpos is" + data);
          countpos = data;
      }
    ).error( function() { 
      alert("Failed");
	});
});

</script>

</body>
</html>