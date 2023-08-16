<?php ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); ?>
<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin'])) {
  header("Location: index.php");
  exit();
}

// Require database connection
require_once "db_connect.php";

// Fetch the user's data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);

// Check if the query succeeded
if (!$result) {
  echo "Error: " . mysqli_error($conn);
  exit();
}

// Get the user's data from the query result
$user_data = mysqli_fetch_assoc($result);

// Store the user's data in the $_SESSION variable
$_SESSION['user_data'] = $user_data;

// Set the is_admin flag in the $_SESSION variable
$_SESSION['is_admin'] = $user_data['is_admin'];

$thelist = ""; // Initialize the $thelist variable

if ($handle = opendir('/var/www/html/download')) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {
        $thelist .= '<a href="../download/'.$file.'">'.$file.'</a><br>
                    ';
      }
    }
    closedir($handle);
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Cassandra@LochSRV - Files</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="css.css">
  <style type="text/css">
    body {
      font: 14px sans-serif;
    }
    .wrapper {
      width: 350px; padding: 20px;
    }
    a.popup-link {
      text-decoration: none;
      color: black;
      cursor: pointer;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Cassandra@LochSRV</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="files.php">Files</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        <p class="navbar-text navbar-right">LochSRV. All rights reserved.</p>
    </div>
</nav>
<h1>Lists of files the user can download:</h1>

<?php echo $thelist; ?>
</body>
</html>