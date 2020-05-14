<?php
session_start();
$currentuser = $_SESSION["username"];
$cartempty=FALSE;

if($currentuser == "Guest" || $currentuser == "" || $currentuser == NULL){
 echo "<p>Signed in as: Guest</p>";
 echo "<h5 align='center'>Please sign in/register to view order history!</h5>";
 echo "<p align='center'>Already have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/4150-login/'>Sign in</a>.</p>";
 echo "<p align='center'>Don't have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/registration/'>Register</a>.</p>";
}
 else{
   echo "<p>Signed in as: " . $currentuser . "</p><br>";
   // Connect to DB to get Cart Items

   // Create connection for VIEWING CART
   $conn = new mysqli($servername, $username, $password, $dbname);
   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   // Fetching customer's email by their username
   $sqlemail = "SELECT email FROM CUSTOMER WHERE username = '$currentuser' LIMIT 1";
   $result1 = $conn->query($sqlemail);
   // Fetching Customer.email
   if ($result1->num_rows > 0) {
    while($row = $result1->fetch_assoc()) {
      $email = $row["email"];
      //echo "Email: " . $email . "<br>";
    }
   }
   else {
     // echo "no results";
   }

   echo "<style>tr:hover {background-color: #f5f5f5;}</style>";
   echo "<style>button {background-color: Transparent; background-repeat:no-repeat;border: none; cursor:pointer;overflow: hidden; outline:none;}</style>";
   echo "<table> <tr><th> </th><th>Product ID</th> <th>Product Name</th> <th>Price</th> <th>Order ID</th> <th>Order Date</th> </tr>";

   $sqloid = "SELECT oid, odate FROM ORDERS WHERE email = '$email' AND amount <> 0";
   // Finding out OIDs after retrieving EMAIL
   $result2 = $conn->query($sqloid);
   // Fetching Orders.oid
   if ($result2->num_rows > 0) {
    while($row = $result2->fetch_assoc()) {
      $oid = $row["oid"];
      $odate = $row["odate"];

      $sqlpid = "SELECT pid, pname, price, image FROM HISTORY WHERE oid = '$oid'";
      $result3 = $conn->query($sqlpid);
      // Fetching History PIDs according to OID
      if ($result3->num_rows > 0) {
       while($row = $result3->fetch_assoc()) {
         $pid = $row["pid"];
         $pname = $row["pname"];
         $price = $row["price"];
         $image = $row["image"];

         echo "<tr><td><img src='" . $image . "'width='100' height='150'>" . "</td><td>" . $pid .  "</td><td>" . $pname . "</td><td>" . $price . "</td> <td>" . $oid . "</td><td>" . $odate . "</td></tr>";

       }
      }
      else {
        // echo "no results";
      }
    }
   }
   else {
     // echo "no results";
   }
   echo "</table>";

   $conn->close();

}


?>
