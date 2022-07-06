<?php

class loginmodel extends CI_Model
{
    public function isvalidate($username,$password)
{
    $q=$this->db->where(['Username'=>$username,'password'=>$password])
    
    // ->from(Index1')
                ->get('index1');

                if ($q->num_rows())
                {
                            return $q->row()->id;
                }
                        else
                        {
                             return false;
                        }
}


}


?>