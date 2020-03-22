<?php
session_start();
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

// If the user requested logout go back to logout then index.php
if ( isset($_POST['cancel']) ) {
    header('Location: view.php');
    return;
}

// $failure = false;  // If we have no POST data
// $success = false;  // If we have no POST data

if ( isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage']) && isset($_POST['model'])) {
    $sql = "INSERT INTO autos (make, year, mileage, model)
              VALUES (:make, :year, :mileage, :model)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':model' => $_POST['model']
      ));
}

if ( isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage']) && isset($_POST['model']))
{
    if ( strlen($_POST['make']) < 1 || strlen($_POST['year']) < 1
         || strlen($_POST['mileage']) < 1 || strlen($_POST['model']) < 1)
    {
        // $failure = "Make is required";
        $_SESSION['failure'] = "All fields are required";
        header("Location: add.php");
        return;
    } else
      {
          $check = $_POST['year'];
          $checkat = $_POST['mileage'];

          if (is_numeric($check) && is_numeric($checkat))
          {
              // Redirect the browser to auto.php
              // $success = "Record inserted";
              $_SESSION['success'] = "Record added";
              header("Location: view.php");
              return;
          }else
          {
                if ( ! is_numeric($checkat)){
                // Redirect the browser to auto.php
                // $failure = "Mileage and year must be numeric";
                $_SESSION['failure'] = "Year must be an integer";
                header("Location: add.php");
                return;
                }elseif(! is_numeric($check)) {
                        // Redirect the browser to auto.php
                        // $failure = "Mileage and year must be numeric";
                        $_SESSION['failure'] = "Mileage must be numeric";
                        header("Location: add.php");
                        return;
                      }

          }
      }
}

// $stmt = $pdo->query("SELECT make, year, mileage, auto_id FROM autos");
// $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<html>
<head>
<title>Robed Beauvil</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Tracking Autos for ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}
?>
</table>
<p>Add A New auto</p>
<?php
// if ( $failure !== false ) {
//     // Look closely at the use of single and double quotes
//     echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
// }

if ( isset($_SESSION['failure']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
}
// if ( $success !== false ) {
//     // Look closely at the use of single and double quotes
//     echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
// }
?>
<form method="post">
<p>make:
<input type="text" name="make" size="40"></p>
<p>year:
<input type="text" name="year"></p>
<p>mileage:
<input type="mileage" name="mileage"></p>
<p>model:
<input type="model" name="model"></p>
<p><input type="submit" value="Add New"/></p>
</form>

<form method="post">
<input type="submit" name="cancel" value="cancel">
</form>
</body>
