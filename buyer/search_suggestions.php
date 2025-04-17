<?php
require_once('../config/db.php');

$search=$_POST['search'];

$sql="select name from products where name like '%".$search."%'";

$result=mysqli_query($conn,$sql);

$output='';

while($data=mysqli_fetch_array($result))
{
    $output.="<li onclick='putdata(this.innerHTML)'>".$data['name']."</li>";
}

echo $output;
?>

