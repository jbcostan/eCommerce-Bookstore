<?php

if (($_FILES['fileToUpload']['name']!="")){
// Where the file is going to be stored
 $target_dir = "./wp-content/uploads/2019/11/";
 $file = $_FILES['fileToUpload']['name'];
 $path = pathinfo($file);
 $filename = $path['filename'];
 $ext = $path['extension'];
 $temp_name = $_FILES['fileToUpload']['tmp_name'];
 $path_filename_ext = $target_dir.$filename.".".$ext;

// Check if file already exists
if (file_exists($path_filename_ext)) {
 echo "File already exists. Existing photo will be used.";
 }else{
 if(move_uploaded_file($temp_name,$path_filename_ext)){
   echo "Congratulations! File Uploaded Successfully."; // File uploaded
 }
 else {
   echo "ERROR: File not uploaded."; // File not uploaded
 }
 }
}

// MySQL Connection Information

// Create connection for VIEWING ORDER ITEMS -> "CART"
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching current Product table's most recent PID
$sqlpid1 = "SELECT MAX(pid) FROM PRODUCT LIMIT 1";
$result = $conn->query($sqlpid1);
// Fetching Orders.oid
if ($result->num_rows > 0) {
 while($row = $result->fetch_assoc()) {
   $pid = $row["MAX(pid)"];
 }
}
else {
  echo "no pid results";
  $pid = NULL;
}

$path_filename_ext2 = substr($path_filename_ext, 1);

$finalpath = "http://www.costanj.myweb.cs.uwindsor.ca" . $path_filename_ext2;

$sqlimg = "UPDATE PRODUCT SET image='$finalpath' WHERE pid = '$pid'";
if($conn->query($sqlimg) === true){
     // echo "Records was updated successfully.";
} else{
     // echo "ERROR: Could not able to execute $sql. " . $conn->error;
  }

 $conn->close();




?>
