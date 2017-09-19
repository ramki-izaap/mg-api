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
class Schedule extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        
    }

    public function list_get( $user_id = 0 )
    {
        $sql = "SELECT s.*, u.name as user_name 
                                    FROM schedule s 
                                    LEFT JOIN users u ON(u.id=s.user_id) ";

        if( $user_id )
        {
            $sql .= " WHERE u.id=".$user_id; 
        }

        $sql .= " ORDER BY s.id DESC";
        //echo $sql;die;
        $query = $this->db->query($sql);
        $result = $query->result_array();

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    

    public function add_post()
    {
        $sh_id         = $this->post('id');

        //echo $this->post('id');die;
        $user_id    = $this->post('user_id');
        $height     = $this->post('height');
        $weight     = $this->post('weight');
        $bmi        = $this->post('bmi');
        $start_date = $this->post('start_date');
        $end_date   = $this->post('end_date');
        $goal       = $this->post('goal');
        $specification  = $this->post('specification');
        $precaution     = $this->post('precaution');
        $advice         = $this->post('advice');


        $data = array(
                        'user_id' => $user_id,
                        'height' => $height, 
                        'weight' => $weight, 
                        'bmi' => $bmi, 
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'goal' => $goal,
                        'specification' => $specification,
                        'precaution' => $precaution,
                        'advice' => $advice
                    );

        if( (int)$sh_id )
        {
            $this->db->where('id', $sh_id);
            $this->db->update('schedule', $data);
        }
        else
        {
            
            $this->db->insert('schedule', $data);            
        }

        $result = array('status' => 'SUCCESS');

        $this->set_response($result, REST_Controller::HTTP_OK); 
    }

    public function get_get( $id = 0 )
    {
        $query = $this->db->query("SELECT sh.*, 
                                            u.name as user_name, 
                                            u.email
                                    FROM schedule sh 
                                    LEFT JOIN users u ON(u.id=sh.user_id)  
                                    WHERE sh.id=".$id);
        $result = $query->row_array();
        

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    
}
