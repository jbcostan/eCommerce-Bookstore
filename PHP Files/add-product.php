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
echo "<form method='post' action='http://www.costanj.myweb.cs.uwindsor.ca/add-product-image/'>
  <div class='container'>
    <h1>Step 1: Product Information</h1>
    <p>Please fill in the product information below:</p>
    <hr>

    <label for='pname'><b>Product Name</b></label>
    <input type='text' placeholder='Enter Product Name' name='pname' required=''>

    <label for='price'><b>Price</b></label>
    <input type='text' placeholder='Enter Price' name='price' required=''>

    <label for='category'><b>Category</b></label>
    <select name='category'>
     <option value='Books'>Books</option>
     <option value='Clothing'>Clothing</option>
     <option value='Electronics'>Electronics</option>
     <option value='Supplies'>Supplies</option>
    </select>

    <br>
    <button type='submit' class='registerbtn'>Continue to Add Image</button>
  </div>

</form>";
?>
