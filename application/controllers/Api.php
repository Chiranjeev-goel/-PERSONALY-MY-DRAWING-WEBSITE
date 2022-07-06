<?php
class Api extends CI_Controller
{
Public function  testapi()
{
   $query=$this->db->query('select * from index1');
   if (issest($_GET['token']));
   $token=mysqli_read_escape_string($con,$_GET['token']);
   $checktokenres=mysqli_read_query($con,"select * from index1");
   echo json_encode($query->result());
}

}

?>

