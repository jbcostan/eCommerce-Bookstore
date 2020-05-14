<?php

session_start();
$currentuser = $_SESSION["username"];
$oid = ($_REQUEST["oid"]);

if($currentuser == "Guest" || $currentuser == "" || $currentuser == NULL){
  echo "<p>Signed in as: Guest</p>";
  echo "<h5 align='center'>Access Denied: Please sign in/register to access this page.</h5>";
  echo "<p align='center'>Already have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/4150-login/'>Sign in</a>.</p>";
  echo "<p align='center'>Don't have an account? <a href='http://www.costanj.myweb.cs.uwindsor.ca/registration/'>Register</a>.</p>";
}
else{
  echo "<p>Signed in as: " . $currentuser . "</p><br>";
  echo "<h4 align='center'>Payment for Order #" . $oid . "</h4>";
}

echo "<div align='center'><img src='http://www.costanj.myweb.cs.uwindsor.ca/wp-content/uploads/2019/11/paymentoptions.png' width='150' height='50'></div>";

// Form Section
echo "<form method='post' action='http://www.costanj.myweb.cs.uwindsor.ca/order-placed/?oid=$oid'>
  <div class='container'>
    <h1>Payment Information</h1>
    <p>Please fill in your payment information.</p>
    <hr>

    <label for='fullname'><b>Full Name (as on card)</b></label>
    <input type='text' placeholder='Enter Full Name' name='fullname' required=''>

    <label for='cnum'><b>Card Number</b></label>
    <input type='text' placeholder='Enter Card Number' name='cnum' required=''>

    <label for='cexp'><b>Card Expiry</b></label>
    <input type='text' placeholder='Enter Card Expiry (MM/YY)' name='cexp' required=''>

    <label for='cvc'><b>Card CVC</b></label>
    <input type='text' placeholder='Enter Card CVC' name='cvc' required=''>

    <h1>Shipping Information</h1>
    <p>Please fill in your shipping information.</p>
    <hr>

    <label for='address'><b>Shipping Address</b></label>
    <input type='text' placeholder='Enter Address' name='address' required=''>

    <label for='city'><b>City</b></label>
    <input type='text' placeholder='Enter City' name='city' required=''>

    <label for='province'><b>Province</b></label>
    <input type='text' placeholder='Enter Province' name='province' required=''>

    <label for='pcode'><b>Postal Code</b></label>
    <input type='text' placeholder='Enter Postal Code' name='pcode' required=''>

    <button type='submit' class='registerbtn'>Place Order</button>
  </div>

</form>";

?>
