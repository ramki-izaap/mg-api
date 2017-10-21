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
        	$message = getSMSContent('membership_expiry_confirmation', $sms_data);

        	sendSMS(array($row['mobile_no']),$message);
        }


	}

	function unpaid( $days = 15 )
	{
		$sql = "SELECT * FROM (SELECT u.id, u.name as user_name, u.mobile_no, u.email,
                        um.mh_id,
                        m.name as membership_name,
                        mh.amount as membership_amount,
                        mh.end_date,
                        IF(SUM(p.amount),SUM(p.amount),0) as paid_amount,
                        (mh.amount-IF(SUM(p.amount),SUM(p.amount),0)) as balance_amount,
                        DATEDIFF(NOW(), mh.start_date) as diff
                            FROM `users` u 
                            LEFT JOIN user_membership um ON(um.user_id=u.id) 
                            LEFT JOIN membership_history mh ON(mh.id=um.mh_id) 
                            LEFT JOIN memberships m ON(mh.membership_id=m.id)
                            LEFT JOIN payment p ON(p.mh_id=mh.id) 
                            GROUP BY mh.id ) t 
                    WHERE t.balance_amount>0 AND  t.diff=$days ";


        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        foreach ($result as $row) 
        {
        	echo '</pre>';
        	$sms_data = array( 'name' => $row['user_name'] );
        	//print_r($sms_data);

        	$message = getSMSContent('payment_due', $sms_data);
        	sendSMS(array($row['mobile_no']),$message);
        }
	}
}
