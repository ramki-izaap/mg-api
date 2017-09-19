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
        $query2 = $this->db->query("SELECT count(u.id) as count FROM users u JOIN user_membership um ON(u.id=um.user_id)  WHERE um.status='1' ");
        $res2 = $query2->row_array();
        $result[] = array("color"=>"pieColor","description"=>"Total Users","stats"=>$res2['count'],"icon"=>"person");

        //Currrnt month usera count
        $query3 = $this->db->query("SELECT count(u.id) as count FROM users u JOIN user_membership um ON(u.id=um.user_id)  WHERE um.status='1' AND DATE_FORMAT(created_on,'%Y-%m')='".$current_month."'");
        $res3 = $query3->row_array();
        $result[] = array("color"=>"pieColor","description"=>"Join this month","stats"=>$res3['count'],"icon"=>"person");

        //Income amount in this month
        $sql = "SELECT sum(p.amount) as amount 
                    FROM membership_history mh
                    LEFT JOIN payment p ON(p.mh_id=mh.id)
                    WHERE DATE_FORMAT(p.paid_date,'%Y-%m')='".$current_month."'";

        $query4 = $this->db->query($sql);
        $res4 = $query4->row_array();
        $result[] = array("color"=>"pieColor","description"=>date('M')." Income","stats"=>number_format((float)$res4['amount']),"icon"=>"money");


        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function linechart_get()
    {
        $result = array();

        for($i=5;$i>=0;$i--){

            $current_month = date('Y-m', strtotime(date('Y-m')." -".$i." month"));

            $query = $this->db->query("SELECT IF(sum(amount) is not NULL,ROUND(sum(amount)),'0') as amount FROM payment WHERE DATE_FORMAT(paid_date,'%Y-%m')='".$current_month."'");
            $res = $query->row_array();
            $result[] = array("month"=>date('m',strtotime($current_month)),"year"=>date('Y',strtotime($current_month)),"value"=>$res['amount']);
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function barchart_get()
    {
        $result = array();

        for($i=1;$i<=5;$i++){

            $current_month = date('Y-m', strtotime(date('Y-m')." -".$i." month"));

            $query = $this->db->query("SELECT IF(sum(amount) is not NULL,ROUND(sum(amount)),'0') as amount FROM expense WHERE DATE_FORMAT(paid_date,'%Y-%m')='".$current_month."'");
            $res = $query->row_array();
            $result[] = array("month"=>date('F',strtotime($current_month)),"value"=>$res['amount']);
        }

        $this->set_response($result, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    
}
