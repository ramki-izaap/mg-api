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

        $result = array('status' => 'SUCCESS', 'message' => $message, 'mn' => $mobile_no);

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
        
        $comments              = $this->post('comments');
        $next_followup_date     = $this->post('next_followup_date');
        $lead_id                = $this->post('lead_id');


        $data = array(
                        'lead_id' => $lead_id, 
                        'comments' => $comments                        
                    );

        if( $next_followup_date != '' )
            $data['next_followup_date'] = $next_followup_date;

        {
            
            $data['created_on'] = date('Y-m-d H:i:s');

            $this->db->insert('leads_followup', $data);

            $user_id = $this->db->insert_id();
        }

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }
    

    public function listFollowup_get( $lid = 0 )
    {
        $sql = "SELECT lf.*,DATE_FORMAT(lf.created_on, '%Y-%m-%d') as created_on FROM leads_followup lf WHERE lead_id=$lid ORDER BY lf.id DESC";
        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function leadFollowups_get()
    {
        $sql = "SELECT u.*, m.name as membership_name 
                                    FROM leads u 
                                    LEFT JOIN memberships m ON(u.package=m.id) ORDER BY u.id DESC";

        $sql = "SELECT 
                    l.id,l.name,l.email,l.mobile_no,l.expect_at,m.name as membership_name,
                    t1.*,
                    l.id,
                    IF(t1.next_followup_date,t1.next_followup_date,l.expect_at) as next_followup_date 
                FROM leads l 
                LEFT JOIN 
                    (SELECT * FROM (SELECT MAX(id) as t_id FROM `leads_followup` GROUP BY lead_id) t 
                        JOIN leads_followup lf ON(lf.id=t.t_id)) t1 
                ON(t1.lead_id=l.id) 
                LEFT JOIN memberships m ON(l.package=m.id)
                WHERE (t1.next_followup_date != 'NULL' AND DATEDIFF(t1.next_followup_date, NOW()) <= 0) 
                    OR (
                            (t1.next_followup_date IS NULL OR t1.next_followup_date = '') 
                            AND 
                            DATEDIFF(l.expect_at, NOW()) <= 0
                        ) 
                ORDER BY next_followup_date ASC";
        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }
}
