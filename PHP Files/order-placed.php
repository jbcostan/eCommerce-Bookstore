<?php

session_start();
$currentuser = $_SESSION["username"];

// Information from Redirect Link
$oid = ($_REQUEST["oid"]);

// Form Information Extraction
$fullname = ($_POST["fullname"]);
$cnum = ($_POST["cnum"]);
$cexp = ($_POST["cexp"]);
$cvc = ($_POST["cvc"]);
$address = ($_POST["address"]);
$city = ($_POST["city"]);
$province = ($_POST["province"]);
$pcode = ($_POST["pcode"]);

// MySQL Connection Information
if($currentuser == "Guest" || $currentuser == "" || $currentuser == NULL){
  echo "<p>Signed in as: Guest</p>";
  echo "<h5 align='center'>Access Denied: Please sign in/register to access this page.</h5>";
  echo "<p align='center'>Already have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/4150-login/'>Sign in</a>.</p>";
  echo "<p align='center'>Don't have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/registration/'>Register</a>.</p>";
}
else{
  echo "<p>Signed in as: " . $currentuser . "</p><br>";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sqlamt = "SELECT amount FROM ORDERS WHERE oid='$oid'";

  $result = $conn->query($sqlamt);

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
         $amount = $row["amount"];
      }
  }
  else {
      echo "0 results";
  }

  // prepare and bind for CUSTOMER Insertion
  try{
      $stmt = $conn->prepare("INSERT INTO PAYMENT (`payid`, `oid`, `amount`, `payee`, `credit_num`, `credit_exp`, `credit_cvc`) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssss", $sqlpayid, $sqloid, $sqlamount, $sqlpayee, $sqlcrnum, $sqlcrexp, $sqlcrcvc);

      // set parameters and execute
      $sqlpayid = NULL;
      $sqloid = $oid;
      $sqlamount = $amount;
      $sqlpayee = $fullname;
      $sqlcrnum = $cnum;
      $sqlcrexp = $cexp;
      $sqlcrcvc = $cvc;

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

  // prepare and bind for CUSTOMER Insertion
  try{
      $stmt2 = $conn->prepare("INSERT INTO SHIPMENT (`oid`, `address`, `city`, `province`, `pcode`) VALUES (?, ?, ?, ?, ?)");
      $stmt2->bind_param("sssss", $sqloid1, $sqladdress, $sqlcity, $sqlprov, $sqlpcode);

      // set parameters and execute
      $sqloid1 = $oid;
      $sqladdress = $address;
      $sqlcity = $city;
      $sqlprov = $province;
      $sqlpcode = $pcode;

      $stmt2->execute();
      //echo "New records created successfully";
      $input_date = date("Y-m-d");
      $date = new DateTime($input_date);
      $date->modify('+3 day');
      $shipdate = $date->format('l, F N, Y');
      echo "<h4 align='center'>Great! Your order has been placed and a confirmation email has been sent to your email.</h4>";
      echo "<h4 align='center'>Your guaranteed delivery date is " . $shipdate . ".</h4>";

  }
  catch (Exception $e) {
      echo $e->getMessage();
  }
  catch (InvalidArgumentException $e) {
      echo $e->getMessage();
  }

  $stmt2->close();


  // Fetching current Customer's email
  $sqlemail = "SELECT email FROM CUSTOMER WHERE username = '$currentuser' LIMIT 1";
  $result2 = $conn->query($sqlemail);
  // Fetching Customer.email
  if ($result2->num_rows > 0) {
   while($row = $result2->fetch_assoc()) {
     $email = $row["email"];
     //echo "Email: " . $email . "<br>";
   }
  }
  else {
    echo "no results";
  }

  // used for new ORDER insertion
  $date = date("Y-m-d");
  $defaultamount = 0.00;

  // prepare and bind for ORDER Insertion
  try{
      $stmt3 = $conn->prepare("INSERT INTO ORDERS (`oid`, `email`, `odate`, `amount`) VALUES (?, ?, ?, ?)");
      $stmt3->bind_param("ssss", $sqloid, $sqlemail, $sqlodate, $sqlamount);
      // set parameters and execute

      $sqloid = NULL;
      $sqlemail = $email;
      $sqlodate = $date;
      $sqlamount = $defaultamount;

      $stmt3->execute();
     // echo "New records created successfully";
  }
  catch (Exception $e) {
      echo $e->getMessage();
  }
  catch (InvalidArgumentException $e) {
      echo $e->getMessage();
  }
  $stmt3->close();
/*
  // Email order confirmation to customer
  $to = "josephelghaname@gmail.com";
  $subject = "eCommerce Bookstore: Order #". $oid . " Confirmed";
  $txt = "Hello " . $fullname . " ! Your order has been confirmed and will deliver by " . $shipdate;
  $headers = "From: orders@eCB.com";
  mail($to,$subject,$txt,$headers);
*/
  // Fetching current Customer's email
  $sqlitems = "SELECT PRODUCT.pid, pname, PRODUCT.price, category, image FROM PRODUCT, CART WHERE PRODUCT.pid = CART.pid AND CART.oid = '$oid'";
  $result3 = $conn->query($sqlitems);
  // Fetching Customer.email
  if ($result3->num_rows > 0) {
   while($row = $result3->fetch_assoc()) {
     $pidbought = $row["pid"];
     $pnamebought = $row["pname"];
     $pricebought = $row["price"];
     $categorybought = $row["category"];
     $imagebought = $row["image"];

     // prepare and bind for HISTORY table insertion
     try{
         $stmt4 = $conn->prepare("INSERT INTO HISTORY (`pid`, `pname`, `price`, `category`, `image`, `oid`) VALUES (?, ?, ?, ?, ?, ?)");
         $stmt4->bind_param("ssssss", $sqlpid, $sqlpname, $sqlprice, $sqlcategory, $sqlimage, $sqloid2);
         // set parameters and execute

         $sqlpid = $pidbought;
         $sqlpname = $pnamebought;
         $sqlprice = $pricebought;
         $sqlcategory = $categorybought;
         $sqlimage = $imagebought;
         $sqloid2 = $oid;

         $stmt4->execute();
        // echo "New records created successfully";
     }
     catch (Exception $e) {
         echo $e->getMessage();
     }
     catch (InvalidArgumentException $e) {
         echo $e->getMessage();
     }
     $stmt4->close();

     $sqlremove = "DELETE FROM CART WHERE pid = '$pidbought'";

     if ($conn->query($sqlremove) === TRUE) {
       // echo "Record deleted successfully";
     }
     else {
        echo "Error deleting record from CART: " . $conn->error;
     }

     $sqlremove2 = "DELETE FROM PRODUCT WHERE pid = '$pidbought'";

     if ($conn->query($sqlremove2) === TRUE) {
       // echo "Record deleted successfully";
     }
     else {
        echo "Error deleting record from PRODUCT: " . $conn->error;
     }

   }
  }
  else {
    echo "no results from cart";
  }

  $conn->close();
}
?>
