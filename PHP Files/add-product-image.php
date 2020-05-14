<?php

session_start();
$currentuser = $_SESSION["username"];

if($currentuser == null || $currentuser == ""){
 echo "<p> Signed in as: Guest </p>";
}
else{
 echo "<p>Signed in as: " . $currentuser . "</p>";

}
?>

<?php

// MySQL Connection Information
$servername = "";
$username = "";
$password = "";
$dbname = "";

// POST variables
$pname = ($_POST["pname"]);
$price = ($_POST["price"]);
$category = ($_POST["category"]);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// prepare and bind for Product Insertion
try{
    $stmt = $conn->prepare("INSERT INTO PRODUCT (`pid`, `pname`, `price`, `category`, `image`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $sqlpid, $sqlpname, $sqlprice, $sqlcat, $sqlimage);

    // set parameters and execute
    $sqlpid = NULL;
    $sqlpname = $pname;
    $sqlprice = $price;
    $sqlcat = $category;
    $sqlimage = "";

    $stmt->execute();
    //echo "New records created successfully";
}
catch (Exception $e) {
    echo $e->getMessage();
}
catch (InvalidArgumentException $e) {
    echo $e->getMessage();
}
$stmt->close();


// Put image upload element here
echo "<h1>Step 2: Adding an Image</h1>
   <form action='http://www.costanj.myweb.cs.uwindsor.ca/added-product-successfully' method='post' enctype='multipart/form-data'>
    Select image to upload:
    <input type='file' name='fileToUpload' id='fileToUpload'>
    <input type='submit' value='Upload Image & Add Product' name='submit'>
    </form>";

$conn->close();


?>
