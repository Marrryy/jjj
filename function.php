<?php
include_once "pdo.php";
// session_start();
function flashMessage(){
    if ( isset($_SESSION['error']) ) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
      }
    if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
    }
}


function validatePosition(){
    for($i=1; $i<=9;$i++){
        if(!isset($_POST["desc".$i]) && !isset($_POST["year".$i]) )continue;

        $desc = $_POST["desc".$i]; 
        $year = $_POST["year".$i];

        if(strlen($desc) === 0 || $year<0){
            return "All fields are required";
        }
        if(!is_numeric($year)){
            return "Year must be numeric";
        }
    }
    return false;
}

function validateProfile(){

    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 
    || strlen($_POST['email'] ) < 1 || strlen($_POST['headline'] ) < 1
    || strlen($_POST['summary'] ) < 1 ) {
        return 'All fields are required';
    }

    if(!strpos($_POST['email'], "@")){
        return "Email address must contain @";
    }

    return false;
}

function insertProfile($pdo){
    $stmt = $pdo->prepare('INSERT INTO Profile
    (user_id, first_name, last_name, email, headline, summary)
    VALUES ( :uid, :fn, :ln, :em, :he, :su)');

    $stmt->execute(array(
    ':uid' => $_SESSION['user_id'],
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':he' => $_POST['headline'],
    ':su' => $_POST['summary'])
    ); 
     $profile_id = $pdo->lastInsertId();
     return $profile_id;
}

function insertPosition($pdo, $profile_id,$rank,$year,$desc){
    $stmt = $pdo->prepare('INSERT INTO Position 
    (profile_id, rank, year, description) 
    VALUES ( :pid, :rank, :year, :desc)');

    $stmt->execute(array(
      ':pid' => $profile_id,
      ':rank' => $rank,
      ':year' => $year,
      ':desc' => $desc)
    );

}

function deletePosition(){
    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
}


?>
