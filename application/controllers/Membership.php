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
class Membership extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        
    }

    public function list_get()
    {
        $query = $this->db->query("select * from memberships");
        $result = $query->result_array();
        

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function get_get( $id = 0 )
    {
        $query = $this->db->query("select * from memberships WHERE id=".$id);
        $result = $query->row_array();
        

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function delete_post(  )
    {
        $this->db->delete('memberships', array('id' => $this->post('id'))); 
        
        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function add_post()
    {
        $id             = $this->post('id');
        $name           = $this->post('name');
        $description    = $this->post('description');
        $duration       = $this->post('duration');
        $amount         = $this->post('amount');

        if( (int)$id )
        {
            $this->db->set('name', $name);
            $this->db->set('description', $description);
            $this->db->set('duration', $duration);
            $this->db->set('amount', $amount);

            $this->db->where('id', $id);
            $this->db->update('memberships');
        }
        else
        {
            $data = array('name' => $name, 'description' => $description, 'duration' => $duration, 'amount' => $amount);
            $this->db->insert('memberships', $data);
        }
        

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    
}
