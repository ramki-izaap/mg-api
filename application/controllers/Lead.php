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
class Lead extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        
    }

    public function list_get()
    {
        $sql = "SELECT u.*, m.name as membership_name 
                                    FROM leads u 
                                    LEFT JOIN memberships m ON(u.package=m.id) ORDER BY u.id DESC";
        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function add_post()
    {
        $user_id         = $this->post('id');

        //echo $this->post('id');die;

        $name = $this->post('name');
        $sex = $this->post('sex');
        $age = $this->post('age');
        $mobile_no = $this->post('mobile_no');
        $occupation = $this->post('occupation');
        $email = $this->post('email');
        $facebook = $this->post('facebook');
        $fitness_goal = $this->post('fitness_goal');
        $heard_from = $this->post('heard_from');
        $refference = $this->post('refference');
        $referred_by = $this->post('referred_by');
        
        $membership_id          = $this->post('membership_id');
        $amount                 = $this->post('amount');
        $expect_at              = $this->post('expect_at');

        $co_ordinator           = $this->post('co_ordinator');
        $co_ordinator_no        = $this->post('co_ordinator_no');

        $comments               = $this->post('comments');

        $data = array(
                        'name' => $name, 
                        'email' => $email,
                        'facebook' => $facebook,
                        'age' => $age, 
                        'sex' => $sex, 
                        'mobile_no' => $mobile_no,
                        'occupation' => $occupation,
                        'fitness_goal' => $fitness_goal,
                        'heard_from' => $heard_from,
                        'refference' => $refference,
                        'referred_by' => $referred_by,

                        'expect_at' => $expect_at,
                        'comments' => $comments,
                        'package' => $membership_id,
                        'price' => $amount,
                        'co_ordinator' => $co_ordinator,
                        'co_ordinator_no' => $co_ordinator_no
                        
                    );

        if( (int)$user_id )
        {
            $data['edited_on'] = date('Y-m-d H:i:s');

            $this->db->where('id', $user_id);
            $this->db->update('leads', $data);
        }
        else
        {
            
            $data['created_on'] = date('Y-m-d H:i:s');

            $this->db->insert('leads', $data);

            $user_id = $this->db->insert_id();

            //send Welcome SMS
            $message = getSMSContent('welcome', array('name' => $name));
            sendSMS(array($mobile_no), $message);
        }

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    public function get_get( $id = 0 )
    {
        $sql = "SELECT u.*, m.name as membership_name 
                                    FROM leads u 
                                    LEFT JOIN memberships m ON(u.package=m.id) WHERE u.id=".$id;

        $query = $this->db->query($sql);
        $result = $query->row_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function delete_post()
    {
        $id = $this->post('id');

        $this->db->where('id', $id);
        $this->db->delete('leads');

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    public function followup_post()
    {
        print_r($_POST);die;
        $user_id         = $this->post('id');

        //echo $this->post('id');die;

        $name = $this->post('name');
        $sex = $this->post('sex');
        $age = $this->post('age');
        $mobile_no = $this->post('mobile_no');
        $occupation = $this->post('occupation');
        $email = $this->post('email');
        $facebook = $this->post('facebook');
        $fitness_goal = $this->post('fitness_goal');
        $heard_from = $this->post('heard_from');
        $refference = $this->post('refference');
        $referred_by = $this->post('referred_by');
        
        $membership_id          = $this->post('membership_id');
        $amount                 = $this->post('amount');
        $expect_at              = $this->post('expect_at');

        $co_ordinator           = $this->post('co_ordinator');
        $co_ordinator_no        = $this->post('co_ordinator_no');

        $comments               = $this->post('comments');

        $data = array(
                        'name' => $name, 
                        'email' => $email,
                        'facebook' => $facebook,
                        'age' => $age, 
                        'sex' => $sex, 
                        'mobile_no' => $mobile_no,
                        'occupation' => $occupation,
                        'fitness_goal' => $fitness_goal,
                        'heard_from' => $heard_from,
                        'refference' => $refference,
                        'referred_by' => $referred_by,

                        'expect_at' => $expect_at,
                        'comments' => $comments,
                        'package' => $membership_id,
                        'price' => $amount,
                        'co_ordinator' => $co_ordinator,
                        'co_ordinator_no' => $co_ordinator_no
                        
                    );

        if( (int)$user_id )
        {
            $data['edited_on'] = date('Y-m-d H:i:s');

            $this->db->where('id', $user_id);
            $this->db->update('leads', $data);
        }
        else
        {
            
            $data['created_on'] = date('Y-m-d H:i:s');

            $this->db->insert('leads', $data);

            $user_id = $this->db->insert_id();

            //send Welcome SMS
            $message = getSMSContent('welcome', array('name' => $name));
            sendSMS(array($mobile_no), $message);
        }

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }
    
}
