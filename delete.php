<?php
if(isset($_POST['data'])) $data = $_POST['data'];
$conn =mysqli_connect("localhost","id18025047_ssipmbs1234","Satria123&&&&","id18025047_ssipmbs123");
$sql = "delete from mbs_quiz1 where id='$data' ";
mysqli_query($conn,$sql);

echo "data has been deleted";
?>