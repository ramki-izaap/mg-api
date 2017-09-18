<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Payment extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        
    }

    public function list_get()
    {
        $sql = "SELECT p.id, p.amount, p.paid_date, u.name as user_name, u.email, m.name as membership_name
                                    FROM payment p 
                                    LEFT JOIN users u ON(u.id=p.user_id) 
                                    LEFT JOIN membership_history mh ON(mh.id=p.mh_id) 
                                    LEFT JOIN memberships m ON(mh.membership_id=m.id) ORDER BY p.id DESC";
        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function get_get( $id = 0 )
    {

        $sql = " SELECT p.id, p.user_id, p.mh_id, p.amount, p.paid_date, 
                        u.name as user_name, u.email, m.name as membership_name, mh.amount as mh_amount
                    FROM payment p 
                        LEFT JOIN users u ON(u.id=p.user_id) 
                        LEFT JOIN membership_history mh ON(mh.id=p.mh_id) 
                        LEFT JOIN memberships m ON(mh.membership_id=m.id) 
                        WHERE p.id=".$id;

        $query = $this->db->query( $sql);
        $result = $query->row_array();
        

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function unpaid_get()
    {
        $sql = "SELECT * FROM (SELECT u.id, u.name as user_name,u.email,
                        um.mh_id,
                        m.name as membership_name,
                        mh.amount as membership_amount,
                        IF(SUM(p.amount),SUM(p.amount),0) as paid_amount,
                        (mh.amount-IF(SUM(p.amount),SUM(p.amount),0)) as balance_amount
                            FROM `users` u 
                            LEFT JOIN user_membership um ON(um.user_id=u.id) 
                            LEFT JOIN membership_history mh ON(mh.id=um.mh_id) 
                            LEFT JOIN memberships m ON(mh.membership_id=m.id)
                            LEFT JOIN payment p ON(p.mh_id=mh.id)
                            GROUP BY mh.id) t 
                    WHERE t.balance_amount>0";


        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code


    }

    public function ending_get()
    {
        $sql = "SELECT u.id, u.name as user_name, u.email, 
                    m.name as membership_name, mh.amount as membership_amount,
                    mh.end_date, DATEDIFF(mh.end_date, CURDATE()) as ends_in
                    FROM users u 
                    LEFT JOIN user_membership um ON(um.user_id=u.id) 
                    LEFT JOIN membership_history mh ON(mh.id=um.mh_id)
                    LEFT JOIN memberships m ON(m.id=mh.membership_id)
                    WHERE DATEDIFF(CURDATE(), DATE_SUB(mh.end_date,INTERVAL 7 DAY)) > 0";


        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code


    }
    
}
