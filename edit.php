<?php
require_once "pdo.php";
session_start();

// Demand a GET parameter
if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: view.php");
    return;
}

if ( isset($_POST['update']) && isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage']) && isset($_POST['model'])) {

    // Data validation
    if ( isset($_POST['make']) && isset($_POST['year'])
         && isset($_POST['mileage']) && isset($_POST['model'])
         && isset($_POST['auto_id']))
    {
        if ( isset($_POST['make']) < 1 || isset($_POST['year']) < 1
             || isset($_POST['mileage']) < 1 || isset($_POST['model']) < 1)
        {
            // $failure = "Make is required";
            $_SESSION['failure'] = "All fields are required";
        } else
          {
              $check = $_POST['year'];
              $checkat = $_POST['mileage'];

              if (is_numeric($check) && is_numeric($checkat))
              {
                  // Redirect the browser to auto.php
                  // $success = "Record inserted";
                  $sql = "UPDATE autos SET make = :make,
                          year = :year, mileage = :mileage,
                          model = :model
                          WHERE auto_id = :auto_id";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute(array(
                      ':make' => $_POST['make'],
                      ':year' => $_POST['year'],
                      ':mileage' => $_POST['mileage'],
                      ':model' => $_POST['model'],
                      ':auto_id' => $_POST['auto_id']));
                  $_SESSION['success'] = 'Record edited';
                  header( 'Location: view.php' ) ;
                  return;
              }else
              {
                    if ( ! is_numeric($checkat)){
                    // Redirect the browser to auto.php
                    // $failure = "Mileage and year must be numeric";
                    $_SESSION['failure'] = "Year must be numeric";
                    }elseif(! is_numeric($check)) {
                            // Redirect the browser to auto.php
                            // $failure = "Mileage and year must be numeric";
                            $_SESSION['failure'] = "Mileage must be numeric";
                          }

              }
          }
    }

}

// Guardian: Make sure that auto_id is present
if ( ! isset($_GET['auto_id']) ) {
  $_SESSION['error'] = "Missing auto_id";
  header('Location: view.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header( 'Location: index.php' ) ;
    return;
}
$n = htmlentities($row['make']);
$e = htmlentities($row['year']);
$p = htmlentities($row['mileage']);
$m = htmlentities($row['model']);
$auto_id = $row['auto_id'];
?>

<html>
<head>
<title>Robed Beauvil</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
</table>
<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Tracking Autos for ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}
?>
<?php
if ( isset($_SESSION['failure']) ) {
    echo '<p style="color:red">'.$_SESSION['failure']."</p>\n";
    unset($_SESSION['failure']);
}
?>
<p>Edit Auto</p>
<form method="post">
<p>make:
<input type="text" name="make" value="<?= $n ?>" size="40"></p>
<p>year:
<input type="text" name="year" value="<?= $e ?>"></p>
<p>mileage:
<input type="mileage" name="mileage" value="<?= $p ?>"></p>
<p>model:
<input type="model" name="model" value="<?= $m ?>"></p>
<input type="hidden" name="auto_id" value="<?= $auto_id ?>">
<p><input type="submit" value="Save" name="update"/></p>
<input type="submit" name="cancel" value="Cancel">
</form>
</body>
</html>
