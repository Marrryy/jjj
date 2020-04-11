<?php
include_once "pdo.php";
include_once "function.php";

session_start();
if(!isset($_SESSION['name'])){
  die("Not logged in");
}

if(isset($_POST['first_name']) && isset($_POST['last_name']) 
&& isset($_POST['email']) && isset($_POST['headline'])
&& isset($_POST['summary'])){
  // if(isset($_POST['first_name'])){
  
  $check = validateProfile();
  if($check){
    $_SESSION['error'] = $check;
    header("Location: add.php");
    return;
  }    
  // print_r($_POST);

  $check = validatePosition();
  // print_r($check);
  if($check){
    $_SESSION['error'] = $check;
    header("Location: add.php");
    return;
  }    
  
  $check = validateEducation();
  if($check){
    $_SESSION['error'] = $check;
    header("Location: add.php");
    return;
  }    

  $profile_id = insertProfile($pdo);

  if(is_numeric($profile_id)){
    for($i=1; $i<=9;$i++){

      if ( ! isset($_POST['year'.$i]) ) continue;
      if ( ! isset($_POST['desc'.$i]) ) continue;

      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];
      insertPosition($pdo,$profile_id,$i,$year,$desc);

    }

    for($i=1; $i<=9;$i++){ 
      if ( ! isset($_POST['edu_year'.$i]) ) continue;
      if ( ! isset($_POST['edu_school'.$i]) ) continue;

      $year = $_POST['edu_year'.$i];
      $school = $_POST['edu_school'.$i];

      $institution_id = getInstitution($pdo, $school);

      if($institution_id === false){
        $institution_id = insertInstitution($pdo, $school);
      }
      insertEducation($pdo, $profile_id,$institution_id ,$i,$year);
    }


    $_SESSION["success"]="Record Added";
    header("Location: index.php");
    return;
  }
}


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
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 
  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
  </head>
<body>
<div class="container">
<h1>Adding Profile for "
<?php 
echo htmlentities($_SESSION['name']);
?>
"</h1>

<?php
flashMessage();
?>

</p>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea></p>
<p>Education:<br/>
  <input type="button" id="addEdu" value="+">
  <div id="education_fields">
  </div>
</p>


<p>Position:<br/>
  <input type="button" id="addPos" value="+">
  <div id="position_fields">
  </div>
</p>

<input type="submit" value="Add">
<input type="submit" name="cancel" value="cancel">
</form>

<script type="text/javascript">
countpos = 0;
countedu = 0;

$(document).ready(function(){
  window.console && console.log("the page is ready");
  $('.school').autocomplete({source : "school.php"});

  $("#addPos").click(function(event){
    event.preventDefault();
    if(countpos>=9){
      alert("Maximum of nine position entries exceeded");
      return;
    }
    window.console && console.log("make position");

    countpos++;

    echo = '<div id="position'+countpos+'"> \
    <p>Year : <input type="text" name="year'+countpos+'" > \
    <input type="button" value="-" onClick=$("#position'+countpos+'").remove(); return false; > \
    </p>  \
    <textarea name="desc'+countpos+'" rows="8" cols="80"></textarea> \
    </div>';
    $('#position_fields').append(echo);
  });

  $("#addEdu").click(function(event){
    event.preventDefault();
    if(countedu>=9){
      alert("Maximum of nine position entries exceeded");
      return;
    }
    window.console && console.log("make education");

    countedu++;

    echo = '<div id="education'+countedu+'"> \
    <p>Year : <input type="text" name="edu_year'+countedu+'" > \
    <input type="button" value="-" onClick=$("#education'+countedu+'").remove(); return false; > \
    </p>  \
    <p>School: \
    <input type="text" class="school" name="edu_school'+countedu+'" \
    size ="80" value="" /></p>\
    </div>';
    $('#education_fields').append(echo);
    $('.school').autocomplete({source : "school.php"});

  });

});

</script>

</body>
</html>