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

$targetDir = "/var/www/html/download";
$uploadOk = 1;

if (isset($_POST["submit"])) {
    $uploadedFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);

    // Check if the file already exists
    if (file_exists($uploadedFile)) {
        echo "Sorry, the file already exists.<br>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.<br>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $uploadedFile)) {
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.<br>";
        } else {
            echo "Sorry, there was an error uploading your file.<br>";
        }
    }
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
            <li><a href="files.php">Files</a></li>
            <li class="active"><a href="upload.php">Upload</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        <p class="navbar-text navbar-right">LochSRV. All rights reserved.</p>
    </div>
</nav>
<h1>Please upload the file:</h1>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>
<div id="progress" style="display: none;">
    <div id="progress-bar" style="width: 0%;">0%</div>
</div>

<script>
var form = document.querySelector('form');
var progressBar = document.getElementById('progress-bar');
var progressContainer = document.getElementById('progress');
form.addEventListener('submit', function (event) {
    event.preventDefault();
    var formData = new FormData(form);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);
    xhr.upload.addEventListener('progress', function (e) {
        var percent = Math.round((e.loaded / e.total) * 100);
        progressBar.style.width = percent + '%';
        progressBar.innerHTML = percent + '%';
    });
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                progressBar.style.width = '100%';
                progressBar.innerHTML = '100%';
                progressContainer.style.display = 'none';
                alert('File uploaded successfully.');
            } else {
                alert('Error uploading file.');
            }
        }
    };
    xhr.send(formData);
    progressContainer.style.display = 'block';
});
</sript>
</body>
</html>