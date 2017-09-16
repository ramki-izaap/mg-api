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
class Dashboard extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        
    }

    public function piechart_get()
    {
        $result = array();

        $current_month = date("Y-m");

        //Paid amount in this month
        $query1 = $this->db->query("SELECT sum(amount) as amount FROM payment WHERE DATE_FORMAT(paid_date,'%Y-%m')='".$current_month."'");
        $res1 = $query1->row_array();
        $result[] = array("color"=>"pieColor","description"=>date('M')." Paid","stats"=>number_format((float)$res1['amount']),"icon"=>"money");
       
        //total user count
        $query2 = $this->db->query("SELECT count(u.id) as count FROM users u JOIN user_membership um ON(u.id=um.user_id)  WHERE um.status='Y' ");
        $res2 = $query2->row_array();
        $result[] = array("color"=>"pieColor","description"=>"Total Users","stats"=>$res2['count'],"icon"=>"person");

        //Currrnt month usera count
        $query3 = $this->db->query("SELECT count(u.id) as count FROM users u JOIN user_membership um ON(u.id=um.user_id)  WHERE um.status='Y' AND DATE_FORMAT(created_on,'%Y-%m')='".$current_month."'");
        $res3 = $query3->row_array();
        $result[] = array("color"=>"pieColor","description"=>"Join this month","stats"=>$res3['count'],"icon"=>"person");

        //Income amount in this month
        $query4 = $this->db->query("SELECT sum(amount) as amount FROM membership_history WHERE DATE_FORMAT(created_date,'%Y-%m')='".$current_month."'");
        $res4 = $query4->row_array();
        $result[] = array("color"=>"pieColor","description"=>date('M')." Income","stats"=>number_format((float)$res4['amount']),"icon"=>"money");


        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function linechart_get()
    {
        $result = array();

        for($i=1;$i<=8;$i++){

            $current_month = date('Y-m', strtotime(date('Y-m')." -".$i." month"));;

            $query = $this->db->query("SELECT IF(sum(amount) is not NULL,ROUND(sum(amount)),'0') as amount FROM payment WHERE DATE_FORMAT(paid_date,'%Y-%m')='".$current_month."'");
            $res = $query->row_array();
            $result[] = array("month"=>date('m',strtotime($current_month)),"year"=>date('Y',strtotime($current_month)),"value"=>$res['amount']);
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function login_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'password' => $this->post('password'),
            'email' => $this->post('email')
        ];

        $email = $this->post('email');
        $password = $this->post('password');

        //md5
        $password = md5( $password );

        $this->db->select('*');
        $this->db->from('admin_user');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        
        $query = $this->db->get();
        $result = $query->row_array();

        $resp = array();
        if( count($result) )
        {
            $resp['status'] = 'SUCCESS';
            $resp['user_data'] = $result;
        }
        else
        {
            $this->db->select('*');
            $this->db->from('admin_user');
            $this->db->where('email', $email);
            $query = $this->db->get();
            $result = $query->row_array();

            if( count($result) )
            {
                $msg = 'Your password does not match our records, please try again.';
            }
            else
            {
                $msg = 'User does not exist, please check your spelling and try again.';
            }

            $resp['status']  = 'ERROR';
            $resp['msg']     = $msg;
        }

        $this->set_response($resp, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
    }

    public function add_post()
    {
        $user_id         = $this->post('id');

        $name = $this->post('name');
        $age = $this->post('age');
        $sex = $this->post('sex');
        $dob = $this->post('dob');
        $mobile_no = $this->post('mobile_no');
        $resident_no = $this->post('resident_no');
        $address = $this->post('address');
        $referred_by = $this->post('referred_by');
        $email = $this->post('email');
        $facebook = $this->post('facebook');


        $contact_name           = $this->post('contact_name');
        $contact_relationship   = $this->post('contact_relationship');
        $contact_mobile_no      = $this->post('contact_mobile_no');
        $contact_resident_no    = $this->post('contact_resident_no');


        $membership_id          = $this->post('membership_id');
        $membership_no          = $this->post('membership_no');
        $start_date             = $this->post('start_date');
        $end_date               = $this->post('end_date');

        $data = array(
                        'name' => $name, 
                        'age' => $age, 
                        'sex' => $sex, 
                        'dob' => $dob,
                        'mobile_no' => $mobile_no,
                        'resident_no' => $resident_no,
                        'address' => $address,
                        'referred_by' => $referred_by,
                        'email' => $email,
                        'facebook' => $facebook
                    );

        $contact_data = array(
                        'name' => $contact_name, 
                        'relationship' => $contact_relationship, 
                        'mobile_no' => $contact_mobile_no, 
                        'resident_no' => $contact_resident_no
                    );


        $mem_data = array(
                        'membership_id' => $membership_id, 
                        'membership_no' => $membership_no, 
                        'start_date' => $start_date, 
                        'end_date' => $end_date
                    );



        if( (int)$user_id )
        {
            $data['edited_on'] = date('Y-m-d H:i:s');


        }
        else
        {
            
            $data['created_on'] = date('Y-m-d H:i:s');

            $this->db->insert('users', $data);

            $user_id = $this->db->insert_id();

            if( $user_id )
            {
                $contact_data['user_id']    = $user_id;
                $mem_data['user_id']        = $user_id;

                $this->db->insert('user_membership', $mem_data);
                $this->db->insert('contact_details', $contact_data);
            }
        }

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    public function get_get( $id = 0 )
    {
        $query = $this->db->query("SELECT u.*, 
                                            um.id as um_id, 
                                            um.user_id, 
                                            um.membership_id, 
                                            um.membership_no,
                                            um.amount,
                                            um.start_date,
                                            um.end_date 
                                    FROM users u LEFT JOIN user_membership um ON(u.id=um.user_id) 
                                    WHERE u.id=".$id);
        $result = $query->row_array();
        

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    
}