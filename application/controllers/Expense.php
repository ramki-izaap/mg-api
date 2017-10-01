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
class Expense extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        
    }

    public function list_get()
    {
        $sql = "SELECT *,DATE_FORMAT(created_date,'%Y-%m-%d') as created_date FROM expense";
        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }


    public function add_post()
    {
        $id         = $this->post('id');
        $data = array(
                        'name' => $this->post('name'), 
                        'description' => $this->post('description'), 
                        'amount' => $this->post('amount'), 
                        'paid_date' => $this->post('date')
                    );


        if( (int)$id )
        {
            $data['updated_date'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            $this->db->update('expense', $data);

        }
        else
        {
            
            $data['created_date'] = date('Y-m-d H:i:s');

            $this->db->insert('expense', $data);

        }

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    public function get_get( $id = 0 )
    {
        $query = $this->db->query("SELECT * FROM expense WHERE id=".$id);
        $result = $query->row_array();
        

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    
}
