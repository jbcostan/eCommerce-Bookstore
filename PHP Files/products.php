<?php
echo " <style>tr:hover {background-color: #f5f5f5;}</style>";
echo " <style>button {background-color: Transparent; background-repeat:no-repeat;border: none; cursor:pointer;overflow: hidden; outline:none;}</style>";
echo "<style>
.zoom {
  transition: transform .2s;
  width: 100px;
  height: 150px;
  marigin: 0 auto;
}

.zoom:hover {
  -ms-transform: scale(1.5); /* IE 9 */
  -webkit-transform: scale(3); /* Safari 3-8 */
  transform: scale(3);
}
</style>";



$cat = ($_REQUEST["product"]);
$counter=0;
$tcolor="";
$productname="";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<table> <tr><th>  </th> <th>Product Name</th> <th>Price</th> <th></th> </tr>";

if($cat == null) {
  $sql = "SELECT pname, price, image FROM PRODUCT WHERE image IS NOT NULL GROUP BY pname, price, image";
}
else {
  echo "<h3 align='center'>" . $cat . "</h3>";
  $sql = "SELECT pname, price, image FROM PRODUCT WHERE category='$cat' AND image IS NOT NULL GROUP BY pname, price, image";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
                $productname=$row["pname"];
                $counter++;
		if($counter%2==0)
			$tcolor="#C0FFFF";
		else
			$tcolor="#ACFEF5";

    $product = $row["image"];

    echo "<tr bgcolor='$tcolor'><td><div class='zoom'><img style='vertical-algin: middle' src='$product' width='100' height='150'></div></td> <td>" . $row["pname"] . "</td>" . "<td>" . $row["price"] . "</td>" . "<td><a href='http://www.costanj.myweb.cs.uwindsor.ca/shopping-cart/?product=$productname'><img src='http://www.costanj.myweb.cs.uwindsor.ca/wp-content/uploads/2019/11/addtocart.png' width='100' height='150'></a></td> </tr>";

    }
} else {
    echo "0 results";
}
echo "</table>";
$conn->close();
?>
