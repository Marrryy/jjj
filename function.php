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

function validateUser(){
    if(!strpos($_POST['who'], '@')){
        return "Email must have an at-sign (@)";
      }
    return false;
}

function validateUser2($row){
    if($row === false){
      return "Incorrect password or not a member";
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

function validateEducation(){
    for($i=1; $i<=9;$i++){
        if(!isset($_POST["edu_school".$i]) && !isset($_POST["edu_year".$i]) )continue;

        $edu_school = $_POST["edu_school".$i]; 
        $edu_year = $_POST["edu_year".$i];

        if(strlen($edu_school) === 0 || $edu_year<0){
            return "All fields are required";
        }
        if(!is_numeric($edu_year)){
            return "Year must be numeric";
        }
    }
    return false;
}

function getProfile($pdo){
    $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id=:aid");
    $stmt->execute(array(':aid'=> $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function getInstitution($pdo, $edu_school){
    $stmt = $pdo->prepare("SELECT institution_id FROM Institution WHERE name=:name");
    $stmt->execute(array(':name'=> $edu_school));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row['institution_id'] === false ||$row['institution_id'] === null  ){
        return false;
    }
    return $row['institution_id'];
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

function insertInstitution($pdo, $name){
    $stmt = $pdo->prepare('INSERT INTO institution 
    (name) VALUES (:name)');

    $stmt->execute(array(':name' => $name));

    $institution_id = $pdo->lastInsertId();
    return $institution_id;
}

function insertEducation($pdo, $profile_id,$institution_id ,$rank,$year){
    $stmt = $pdo->prepare('INSERT INTO education 
    (profile_id, institution_id,rank, year ) 
    VALUES ( :pid,  :institution_id, :rank, :year)');

    $stmt->execute(array(
      ':pid' => $profile_id,
      ':institution_id' => $institution_id,
      ':rank' => $rank,
      ':year' => $year
      )
    );

}

function updateProfile($pdo){
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
}

function deleteProfile($pdo){
    $stmt = $pdo->prepare("DELETE FROM profile WHERE profile_id=:aid");
    $stmt->execute(array(':aid'=> $_POST['profile_id']));
}

function deletePosition($pdo){
    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_GET['profile_id']));
}

function deleteEducation($pdo){
    $stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_GET['profile_id']));
}

?>
