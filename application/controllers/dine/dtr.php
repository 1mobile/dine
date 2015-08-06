<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dtr extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dine/dtr_model');
        $this->load->helper('dine/dtr_helper');
    }
	public function index(){
        $this->load->model('dine/menu_model');
        $this->load->helper('dine/menu_helper');
        $data = $this->syter->spawn('dtr');
        $data['page_subtitle'] = "Shifts";
        $menus = $this->menu_model->get_menus();
        $data['code'] = menuListPage($menus);
        $this->load->view('page',$data);
    }

    ////////////////////////////////Schedules Jed///////////
    ////////////////////////////////////////////////////////

    public function dtr_schedules(){
        $this->load->model('dine/dtr_model');
        $this->load->helper('site/site_forms_helper');
        $dtr_schedules = $this->dtr_model->get_dtr_schedules();
        $data = $this->syter->spawn('dtr');
        $data['page_subtitle'] = "Schedules";
        $data['code'] = site_list_form("dtr/dtr_schedules_form","dtr_schedules_form","Schedules",$dtr_schedules,'desc',"dtr_sched_id");

        $data['add_js'] = 'js/site_list_forms.js';

        $this->load->view('page',$data);
    }
    public function dtr_schedules_form($ref=null){
        $this->load->helper('dine/dtr_helper');
        $this->load->model('dine/dtr_model');
        $sch = array();
        // if($ref == null)    $ref = $this->input->post('menu_sched_id');
        if($ref != null){
            $schs = $this->dtr_model->get_dtr_schedules($ref);
            // echo 'REF :: '.$ref;
            $sch = $schs[0];
        }
        $dets = $this->dtr_model->get_dtr_schedule_details($ref);

        $data['code'] = makeDtrSchedulesForm($sch,$dets);
        $data['load_js'] = 'dine/dtr.php';
        $data['use_js'] = 'scheduleJs';
        $this->load->view('load',$data);
    }
    public function dtr_sched_db(){
        $this->load->model('dine/dtr_model');
        $items = array();
        $items = array("desc"=>$this->input->post('desc'),
                        "inactive"=>(int)$this->input->post('inactive')
            );
        $id = $this->input->post('dtr_sched_id');
        $add = "add";
        if($id != ''){    
            $this->dtr_model->update_dtr_schedules($items,$id);
            $add = "upd";
        }
        else{            
            $id = $this->dtr_model->add_dtr_schedules($items);
        }

        echo json_encode(array("id"=>$id,"act"=>$add,"desc"=>$this->input->post('desc')));
    }
     public function dtr_sched_details_db(){
        //$this->load->model('dine/dtr_model');
        $items = array();
        $items = array("day"=>$this->input->post('day'),
                        "time_on"=>date('H:i:s',strtotime($this->input->post('time_on'))),
                        "time_off"=>date('H:i:s',strtotime($this->input->post('time_off'))),
                        "dtr_sched_id"=>$this->input->post('sched_id')
                        );
        // $id = $this->input->post('sched_id');
        $day = $this->input->post('day');
        //var_dump($items);
        $count = $this->dtr_model->validate_dtr_schedule_details($this->input->post('sched_id'),$day);
        if($count == 0){
            // if($id != '')    $this->menu_model->update_menu_schedule_details($items,$id);
            // else             $this->menu_model->add_menu_schedule_details($items);
            $id = $this->dtr_model->add_dtr_schedule_details($items);
            //echo $this->dtr_model->db->last_query();
            // echo json_encode(array("msg"=>'success'));
            echo json_encode(array("msg"=>'Successfully Added',"id"=>$this->input->post('sched_id')));
        }else{
            echo json_encode(array("msg"=>'error',"id"=>$this->input->post('sched_id')));
            // echo json_encode(array("msg"=>$count));
            // echo json_encode(array("msg"=>$this->db->last_query()));
        }
    }
    public function remove_schedule_promo_details(){
        $id = $this->input->post('pr_sched_id');
        $this->dtr_model->delete_dtr_schedule_details($id);
        echo json_encode(array("msg"=>'Successfully Deleted'));
    }

    ///////////////////////shifts//////////////////////////////
    ///////////////////////////////////////////////////////////

    public function dtr_shifts(){
        $this->load->model('dine/dtr_model');
        $this->load->helper('dine/dtr_helper');
        $data = $this->syter->spawn('dtr');
        $data['page_subtitle'] = "Shifts";
        $shifts = $this->dtr_model->get_shifts();
        $data['code'] = shiftsListPage($shifts);
        $this->load->view('page',$data);
    }
    public function form_shift($shift_id=null){
        $this->load->model('dine/dtr_model');
        $this->load->helper('dine/dtr_helper');
        $data = $this->syter->spawn('dtr');
        $data['page_subtitle'] = "Shifts";
        $data['code'] = shiftFormPage($shift_id);
        $data['add_css'] = 'js/plugins/typeaheadmap/typeaheadmap.css';
        $data['add_js'] = array('js/plugins/typeaheadmap/typeaheadmap.js');
        $data['load_js'] = 'dine/dtr.php';
        $data['use_js'] = 'shiftFormJs';
        $this->load->view('page',$data);
    }
    public function details_load($shift_id=null){
        $this->load->helper('dine/dtr_helper');
        $this->load->model('dine/dtr_model');
        $shift=array();
        if($shift_id != null){
            $shifts = $this->dtr_model->get_shifts($shift_id);
            $shift=$shifts[0];
        }
        $data['code'] = shiftsDetailsLoad($shift,$shift_id);
        $data['load_js'] = 'dine/dtr.php';
        $data['use_js'] = 'shiftFormJs2';
        $this->load->view('load',$data);
    }
    public function shift_details_db(){
        $this->load->model('dine/dtr_model');

        $fmt_time_in = date('H:i:s', strtotime($this->input->post('time_in')));
        $fmt_time_out = date('H:i:s', strtotime($this->input->post('time_out')));
        
        $fmt_break_out = date('H:i:s', strtotime($this->input->post('break_out')));
        $fmt_break_in = date('H:i:s', strtotime($this->input->post('break_in')));
        
        $get_hours = $this->dtr_model->get_datetime_difference($fmt_time_in, $fmt_time_out);
        //echo $fmt_time_in;

        $items = array(
            "code"=>$this->input->post('shift_code'),
            "description"=>$this->input->post('shift_desc'),
            "time_in"=>$fmt_time_in,
            "break_out"=>$this->input->post('break_out'),
            "break_in"=>$this->input->post('break_in'),
            "time_out"=>$this->input->post('time_out'),
            "break_hours"=>$this->input->post('break_hours'),
            "grace_period"=>$this->input->post('grace_period'),
            "timein_grace_period"=>$this->input->post('timein_grace_period'),
            "work_hours"=>$get_hours
        );

        //var_dump($items);

        if($this->input->post('form_shift_id')){
            $this->dtr_model->update_shift($items,$this->input->post('form_shift_id'));
            $id = $this->input->post('form_shift_id');
            $act = 'update';
            $msg = 'Updated shift '.$this->input->post('shift_code');
        }else{
            $id = $this->dtr_model->add_shift($items);
            $act = 'add';
            $msg = 'Added new shift '.$this->input->post('shift_code');
        }

        echo json_encode(array("id"=>$id,"desc"=>$this->input->post('shift_desc'),"act"=>$act,'msg'=>$msg));
    }

    /////////////////////////////////scheduler////////////////
    //////////////////////////////////////////////////////////
    public function scheduler(){
        $data = $this->syter->spawn('dtr');
         $data['page_subtitle'] = "Scheduler";
        //$this->load->helper('dine/dtr_helper');
        $data['code'] = schedulerPage();
        $data['load_js'] = 'dine/dtr';
        $data['use_js'] = 'attendanceJs';
        $data['add_css'] = 'css/attendance.css';
        $this->load->view('page',$data);
    }

    public function table(){
        //$this->load->helper('school/attendance_helper');
        // $this->load->model('dine/dtr_model');
        // $batch = iSet($_GET,'batch-drop');
        // $level = iSet($_GET,'grade-drop');
        $user = iSet($_GET,'user-drop');
        $from = iSet($_GET,'date-from');
        $to = iSet($_GET,'date-to');
        // if($batch != null && $level != null && $section != null){
        //     $this->load->model('school/student_model');
            $users = $this->dtr_model->get_users($user);
            $scheduler = $this->dtr_model->get_schedules($from,$to,$user);
            $sch = array();
            foreach ($scheduler as $res) {
               $sch[$res->user_id." ".sql2Date($res->date)] = $res->date;   
            }
            $data['code'] = scheduleTable($users,$from,$to,$sch);
        // }
        // else
            //$data['code'] = "<center><h3>SEARCH FIRST</h3></center>";
        $data['load_js'] = 'dine/dtr';
        $data['use_js'] = 'schedTableJs';
        $this->load->view('load',$data);
    }

    public function save_sched(){
        
        $user_id = $this->input->post('user_id');
        $date = $this->input->post('date');
        $val = $this->input->post('val');

        $date = date('Y-m-d',strtotime($date));

        //echo $user_id.'--'.$date.'--'.$val;

        $this->dtr_model->del_sched($user_id,$date);

        if($val != ""){
            $items = array(
                        "user_id"=>$user_id,
                        "date"=>$date,
                        "dtr_id"=>$val
                );
            $this->dtr_model->insert_sched($items);
        }
    }      
}