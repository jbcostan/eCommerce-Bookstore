<?php

session_start();
$currentuser = $_SESSION["username"];

if($currentuser == "Guest" || $currentuser == "" || $currentuser == NULL){
  echo "<p>Signed in as: Guest</p>";
  echo "<h5 align='center'>Access Denied: Please sign in/register to access this page.</h5>";
  echo "<p align='center'>Already have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/4150-login/'>Sign in</a>.</p>";
  echo "<p align='center'>Don't have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/registration/'>Register</a>.</p>";
}
else{
  echo "<p>Signed in as: " . $currentuser . "</p><br><hr>";

  // Connect to DB to get Cart Items

  // Create connection for VIEWING ORDER ITEMS -> "CART"
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Fetching current Customer's email
  $sqlemail = "SELECT email FROM CUSTOMER WHERE username = '$currentuser' LIMIT 1";
  $result = $conn->query($sqlemail);
  // Fetching Customer.email
  if ($result->num_rows > 0) {
   while($row = $result->fetch_assoc()) {
     $email = $row["email"];
     //echo "Email: " . $email . "<br>";
   }
  }
  else {
    echo "no results";
  }

  // Fetching current Customer's most recent OID
  $sqloid = "SELECT MAX(oid) FROM ORDERS WHERE email = '$email' LIMIT 1";
  // Finding out OID after retrieving EMAIL
  $result1 = $conn->query($sqloid);
  // Fetching Orders.oid
  if ($result1->num_rows > 0) {
   while($row = $result1->fetch_assoc()) {
     $oid = $row["MAX(oid)"];
   }
  }
  else {
    echo "no results";
  }

  $sqlcart = "SELECT PRODUCT.pid AS pid, pname, PRODUCT.price AS price, image FROM CART, PRODUCT WHERE CART.pid = PRODUCT.pid AND CART.oid = '$oid'";
  $sqltotal = "SELECT SUM(price) AS total FROM CART WHERE CART.oid = '$oid' AND EXISTS ( SELECT PRODUCT.pid AS pid, pname, PRODUCT.price AS price, image FROM CART, PRODUCT WHERE CART.pid = PRODUCT.pid AND CART.oid = '$oid')";

  $result2 = $conn->query($sqlcart);

  echo "<style>tr:hover {background-color: #f5f5f5;}</style>";
  echo "<style>button {background-color: Transparent; background-repeat:no-repeat;border: none; cursor:pointer;overflow: hidden; outline:none;}</style>";
  echo "<table> <tr><th> </th><th>Product ID</th> <th>Product Name</th> <th>Price</th></tr>";

  // Fetching Cart items
  if ($result2->num_rows > 0) {
   while($row = $result2->fetch_assoc()) {
     $pid = $row["pid"];
     $pname = $row["pname"];
     $price = $row["price"];
     $image = $row["image"];
     $cancelimg = "http://www.costanj.myweb.cs.uwindsor.ca/wp-content/uploads/2019/11/x.png";
     echo "<tr bgcolor='$tcolor'><td><img src='" . $image . "'width='100' height='150'>" . "</td><td>" . $pid .  "</td><td>" . $pname . "</td><td>" . $price . "</td></tr>";
   }
  }
  else {
    echo "<p align='center'>No Items in Cart</p>";
  }

  echo "</table>";

   // Fetching Cart total
   $result3 = $conn->query($sqltotal);
   if ($result3->num_rows > 0) {
     while($row = $result3->fetch_assoc()) {
       $subtotal = $row["total"];
       // echo "<p align='right'><b>Total</b> = $" . $total . "</p>";
     }
   }
   else {
     echo "<p align='right'><b>Total</b> = $0.00</p>";
   }


 echo "<p align='right'><b>Sub-Total</b> = $" . $subtotal . "</p>";
 $shipping = 5.00;
 echo "<p align='right'><b>Shipping</b> = $" . $shipping . "</p>";
 $tax = round((($subtotal+$shipping) * 0.13), 2);
 echo "<p align='right'><b>Tax (13% HST)</b> = $" . $tax . "</p>";
 $finaltotal = round(($subtotal + $shipping + $tax), 2);
 echo "<hr><p align='right'><b>Final Total</b> = $" . $finaltotal . "</p><hr>";

 $sqlamt = "UPDATE ORDERS SET amount='$finaltotal' WHERE oid = '$oid'";
 if($conn->query($sqlamt) === true){
     // echo "Records was updated successfully.";
 } else{
     // echo "ERROR: Could not able to execute $sql. " . $conn->error;
 }

 $orderdate = date("Y-m-d");

 $sqldate = "UPDATE ORDERS SET odate='$orderdate' WHERE oid = '$oid'";
 if($conn->query($sqldate) === true){
     // echo "Records was updated successfully.";
 } else{
     // echo "ERROR: Could not able to execute $sql. " . $conn->error;
 }

 $conn->close();


 echo "<a style='text-align: center; display: block;' href='http://www.costanj.myweb.cs.uwindsor.ca/4150-payment/?oid=" . $oid . "'><button style='background-color: #4CAF50;'>Confirm & Proceed to Payment</button></a>";

 }

?>
