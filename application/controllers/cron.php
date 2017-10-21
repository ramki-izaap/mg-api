<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function index()
	{
		echo 'YYYYYYYY';
	}

	function membership_expired( $days = 7 )
	{
		$sql = "SELECT u.id, u.name as user_name, u.mobile_no, u.email, 
                    m.name as membership_name, mh.amount as membership_amount,
                    mh.end_date, DATEDIFF(mh.end_date, CURDATE()) as ends_in
                    FROM users u 
                    LEFT JOIN user_membership um ON(um.user_id=u.id) 
                    LEFT JOIN membership_history mh ON(mh.id=um.mh_id)
                    LEFT JOIN memberships m ON(m.id=mh.membership_id)
                    WHERE DATEDIFF(CURDATE(), DATE_SUB(mh.end_date,INTERVAL $days DAY)) > 0";


        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach ($result as $row) 
        {
        	echo '</pre>';
        	$sms_data = array(
        					'name' => $row['user_name'],
        					'expired_at' => $row['end_date']);
        	echo getSMSContent('membership_expiry_confirmation', $sms_data);
        }


	}
}
