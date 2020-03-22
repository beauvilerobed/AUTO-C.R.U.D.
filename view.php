<?php
session_start();
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}

// If the auto requested logout go back to logout then index.php
if ( isset($_POST['logout']) ) {
    header('Location: logout.php');
    return;
}

// If the auto requested logout go back to logout then index.php
if ( isset($_POST['Add New']) ) {
    header('Location: add.php');
    return;
}

if ( isset($_POST['make']) && isset($_POST['year'])
     && isset($_POST['mileage']) && isset($_POST['model'])) {
    $sql = "INSERT INTO autos (make, year, mileage, model)
              VALUES (:make, :year, :mileage, :model)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':model' => $_POST['model']));
}

$stmt = $pdo->query("SELECT make, year, mileage, model, auto_id FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>
<a href="add.php">Add New Entry</a></b>
<a href="Logout.php">Logout</a>

<h1>Automobiles</h1>
<table border="1">
<?php
if ($rows == false)
{
          echo "No rows found";
}else{
foreach ( $rows as $row )
{
    echo "<tr><td>";
    echo(htmlentities($row['make']));
    echo("</td><td>");
    echo(htmlentities($row['year']));
    echo("</td><td>");
    echo(htmlentities($row['mileage']));
    echo("</td><td>");
    echo(htmlentities($row['model']));
    echo("</td><td>");
    echo('<form method="post"><input type="hidden" ');
    echo('make="auto_id" value="'.$row['auto_id'].'">'."\n");
    echo('<a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> / ');
    echo('<a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a>');
    echo("</td></tr>\n");
    echo("\n</form>\n");
    echo("</td></tr>\n");
}
}
?>
</table>
</body>
