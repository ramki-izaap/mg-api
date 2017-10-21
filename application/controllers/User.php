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
class User extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->helper(array('form', 'url'));

        
    }

    public function list_get()
    {
        $sql = "SELECT u.*, m.name as membership_name, mh.amount, mh.end_date, um.mh_id 
                                    FROM users u 
                                    LEFT JOIN user_membership um ON(u.id=um.user_id AND um.status='1') 
                                    LEFT JOIN membership_history mh ON(mh.id=um.mh_id) 
                                    LEFT JOIN memberships m ON(mh.membership_id=m.id) ORDER BY u.id DESC";
        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

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

        //echo $this->post('id');die;

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
        $profile_image = $this->post('profile_image');
        $anniversary_date = $this->post('anniversary_date');
        $martial_status = $this->post('martial_status');


        $contact_name           = $this->post('contact_name');
        $contact_relationship   = $this->post('contact_relationship');
        $contact_mobile_no      = $this->post('contact_mobile_no');
        $contact_resident_no    = $this->post('contact_resident_no');


        $membership_id          = $this->post('membership_id');
        $membership_no          = $this->post('membership_no');
        $start_date             = $this->post('start_date');
        $end_date               = $this->post('end_date');
        $amount                 = $this->post('amount');

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
                        'facebook' => $facebook,
                        'profile_image' => $profile_image,
                        'martial_status' => $martial_status,
                        'anniversary_date' => $anniversary_date
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
                        'amount' => $amount,
                        'start_date' => $start_date, 
                        'end_date' => $end_date
                    );



        if( (int)$user_id )
        {
            $data['edited_on'] = date('Y-m-d H:i:s');

            $this->db->where('id', $user_id);
            $this->db->update('users', $data);

            //$this->db->where('user_id', $user_id);
            //$this->db->update('contact_details', $contact_data);

            $this->db->where('user_id', $user_id);
            $this->db->update('contact_details', $contact_data);


        }
        else
        {
            
            $data['created_on'] = date('Y-m-d H:i:s');

            $this->db->insert('users', $data);

            $user_id = $this->db->insert_id();

            if( $user_id )
            {
                //insert contact info
                $contact_data['user_id']    = $user_id;
                $this->db->insert('contact_details', $contact_data);

                //insert membership info
                $mem_data['user_id']        = $user_id;
                $this->db->insert('membership_history', $mem_data);

                //insert mapping info
                $mh_id = $this->db->insert_id();
                $membership_map_data = array();
                $membership_map_data['user_id'] = $user_id;
                $membership_map_data['mh_id']   = $mh_id;
                $membership_map_data['status']  = '1';
                $this->db->insert('user_membership', $membership_map_data);

                //send confirmation SMS
                $sms_data = array(
                                    'membership_name' => $name,
                                    'amount' => 'Rs.'.$amount,
                                    'expired_at' => $end_date
                                    );

                $message = getSMSContent('membership_confirmation', $sms_data);
                sendSMS(array($mobile_no), $message);
                
                
            }
        }

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    public function get_get( $id = 0 )
    {
        $query = $this->db->query("SELECT u.*, 
                                            um.id as um_id, 
                                            mh.membership_id, 
                                            mh.membership_no,
                                            mh.amount,
                                            mh.start_date,
                                            mh.end_date,
                                            cd.name as contact_name,
                                            cd.relationship as contact_relationship,
                                            cd.mobile_no as contact_mobile_no,
                                            cd.resident_no as contact_resident_no 
                                    FROM users u 
                                    LEFT JOIN user_membership um ON(u.id=um.user_id ) 
                                    LEFT JOIN membership_history mh ON(mh.id=um.mh_id) 
                                    LEFT JOIN memberships m ON(mh.membership_id=m.id) 
                                    LEFT JOIN contact_details cd ON(cd.user_id=u.id)  
                                    WHERE u.id=".$id);
        $result = $query->row_array();

        //payment info
        $sql = "SELECT u.id, u.name as user_name,u.email,
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
                            WHERE u.id=".$id." 
                            GROUP BY mh.id";


        //echo $sql;die;
        $query = $this->db->query($sql);
        $result['payment_info'] = $query->row_array();

        

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function delete_post()
    {
        $id = $this->post('id');

        $this->db->where('id', $id);
        $this->db->delete('users');

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    public function upload_post()
    {
        $result = $this->do_upload('file');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    public function do_upload( $fname )
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload( $fname ))
        {
            $data = array('error' => $this->upload->display_errors());
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
        }

        return $data;


    }

    public function test_get()
    {
        echo getSMSContent('welcome', array('name' => 'Ram'));
        sendSMS(array('7904949930'),'My test message 0001');
    }


    
}
