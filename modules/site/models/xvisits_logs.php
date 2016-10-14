<?php

class xvisits_logs extends Base{

	function AdminInit()
	{
		$this->admin_fields=array(
			'user_id'=>array(
				'table'=>array(
					'name'=>'System ID',
				),
			),
			'firstname'=>array(
				'table'=>array(
					'name'=>'First Name',
					'strong'=>1
				),
			),
			'lastname'=>array(
				'table'=>array(
					'name'=>'Last Name',
					'strong'=>1
				),
			),
			'regdate'=>array(
				'table'=>array(
					'name'=>'Registration Date'
				),
			),
			'last_login'=>array(
				'table'=>array(
					'name'=>'Last Log In'
				),
			),
			'no_visits'=>array(
				'table'=>array(
					'name'=>'Number of Visits',
				),
			),
			'duration'=>array(
				'table'=>array(
					'name'=>'Total Time (hh:mm:ss)',
				),
			),

		);
		global $page;
		$this->add_action('View statistics','',$page->paths['root_content'].'statistics/?a=user:{$o.id.value}','',0,'icon-report','');
	}

    function __construct(){
		$this->table=$this->tbl_xvisits_logs;
	}

	function count_for_user_type($type,$cond='')
	{
		if(!$cond)
			$cond='1=1';
		$query='select count(t.id) from (select id, user_id from x_conf_visits_logs where user_type="'.$type.'" and '.$cond.' group by user_id) as t';
		return $this->db->getOne($query);
	}

	function GetLogedNumbers($start_date='',$end_date='')
	{
		$start_date=$this->_processStartDate($start_date);
		$end_date=$this->_processEndDate($end_date);

		$dates=$this->GetDatesArray($start_date,$end_date,1);

		$result=array();
		foreach($dates as $date)
		{
			$query='select count(user_id) from (select user_id from `'.$this->table.'` '.' where user_type="user" and date(login_datetime)="'.$date.'" group by user_id) as t';
			$result[]=$this->db->getOne($query);
		}
		return $result;
	}

	function GetDatesArray($start_date,$end_date,$step=1)
	{
		$start_date=$this->_processStartDate($start_date);
		$end_date=$this->_processEndDate($end_date);

		$result=array();

		while($start_date!=$end_date)
		{
			$result[]=$start_date;
			$start_date=date('Y-m-d',strtotime($start_date)+$step*24*60*60);

		}
		$result[]=$end_date;
		return $result;
	}

	function _processStartDate($start_date)
	{
		if(!$start_date)
			$start_date=date('Y-m-d',time()-7*24*60*60);
		return date('Y-m-d',strtotime($start_date));
	}

	function _processEndDate($end_date)
	{
		if(!$end_date)
			$end_date=date('Y-m-d',time());
		return date('Y-m-d',strtotime($end_date));
	}
	public function get_all($skip='',$nr_rows='',$order_by='',$order_dir='',$cond='',$calc_rows=true,$search_fields='',$keyword='')
	{
		$cond_text='';
		$order_text='';
		$skip_text='';
		$builder=$this->builder();
		if($calc_rows)$builder->calculate();
		$fields = array(
						"0"=>"DISTINCT user_id",
						"1"=>"x_conf_visits_logs.id",
						"2"=>"user_type",
						"3"=>"users_states_coordinators.firstname as firstname",
						"4"=>"users_states_coordinators.lastname as lastname",
						"5"=>"users_states_coordinators.Email as Email",
						"6"=>"users_states_coordinators.company_id as company_id",
						"7"=>"users_states_coordinators.regdate",
						"8"=>"users_states_coordinators.last_login",
						"9"=>"SEC_TO_TIME(sum(Duration)) as duration",
						"10"=>"count(x_conf_visits_logs.id)as no_visits",
						"11"=>"users_states_coordinators.coordinator",
						"12"=>"users_states_coordinators.coordinator_email",
						"13"=>"users_states_coordinators.owner",
						"14"=>"users_states_coordinators.owner_email"
						);
		$builder->select($fields)->join('users_states_coordinators',$this->table.'.user_id = users_states_coordinators.id','left');

		$cond_s='';
		if($search_fields && $keyword)
			$cond_s=$this->_searchLikeCond($search_fields,$keyword);

		if($cond!='')
			$cond_text='('.$cond.')'.($cond_s?' and ('.$cond_s.')':'');
		else
			$cond_text=($cond_s?'('.$cond_s.')':'');

		$builder->where($cond_text)->group(array("0"=>"user_id"));
		if($order_by!=''){
			$builder->order(array($order_by),array($order_dir));
		} else {
			$builder->order(array('last_login'),array('DESC'));
		}
		if($skip>=0 && $nr_rows>0)
			$builder->limit($skip,$nr_rows);
		//echopre($builder);
		if($this->table)
		{
			$arr=$builder->execute();
		}

		if($calc_rows)
			$this->total_rows=$this->db->countTotalRows();
		return $arr;
	}
	function count_company_data($order_by='',$order_dir='',$cond='',$calc_rows=true,$search_fields='',$keyword='')
	{
			$builder=$this->builder();
			$builder->calculate();
			$fields = array(
							"0"=>"SUM(duration) AS Duration",
							"1"=>"COUNT(x_conf_visits_logs.id) AS no_visits"
							);
			$on			= $this->table.'.user_id = users_states_coordinators.id';
			$group		= array("0"=>"user_id");
			$order_by	= array("0"=>"Email");
			$order_dir	= array("0"=>"ASC");
			$builder->select($fields)->join('users_states_coordinators',$on,'left');
			$cond_s='';
			if($search_fields && $keyword)
				$cond_s=$this->_searchLikeCond($search_fields,$keyword);

			if($cond!='')
				$cond_text='('.$cond.')'.($cond_s?' and ('.$cond_s.')':'');
			else
				$cond_text=($cond_s?'('.$cond_s.')':'');

			$builder->where($cond_text)->group(array("0"=>"user_id"));
			if($order_by!='')
				$builder->order($order_by,$order_dir);

			$arr_company_data = $builder->execute();
			$arr_result 				= array();
			$arr_result['duration']		= 0;
			$arr_result['no_visits']	= 0;
			foreach ($arr_company_data as $key=>$value){
				$arr_result['duration']		= $arr_result['duration'] + $value['Duration'];
				$arr_result['no_visits']	= $arr_result['no_visits'] + $value['no_visits'];
			}
			$arr_result['duration'] = gmdate("H:i:s", $arr_result['duration']);
			return $arr_result;
	}
		public function get_all2($skip='',$nr_rows='',$order_by='',$order_dir='',$cond='',$calc_rows=true,$search_fields='',$keyword='')
	{
		$cond_text='';
		$order_text='';
		$skip_text='';
		$builder=$this->builder();
		if($calc_rows)$builder->calculate();
		$fields = array(
						"0"=>"DISTINCT user_id",
						"1"=>"x_conf_visits_logs.id",
						"2"=>"user_type",
						"3"=>"users_states_coordinators.firstname as firstname",
						"4"=>"users_states_coordinators.lastname as lastname",
						"5"=>"users_states_coordinators.Email as Email",
						"6"=>"users_states_coordinators.company_id as company_id",
						"7"=>"users_states_coordinators.regdate",
						"8"=>"users_states_coordinators.last_login",
						"9"=>"SEC_TO_TIME(sum(Duration)) as duration",
						"10"=>"count(x_conf_visits_logs.id)as no_visits",
						"11"=>"users_states_coordinators.coordinator",
						"12"=>"users_states_coordinators.coordinator_email",
						"13"=>"users_states_coordinators.owner",
						"14"=>"users_states_coordinators.owner_email"
						);
		$builder->select($fields)->join('users_states_coordinators',$this->table.'.user_id = users_states_coordinators.id','left');

		$cond_s='';
		if($search_fields && $keyword)
			$cond_s=$this->_searchLikeCond($search_fields,$keyword);

		if($cond!='')
			$cond_text='('.$cond.')'.($cond_s?' and ('.$cond_s.')':'');
		else
			$cond_text=($cond_s?'('.$cond_s.')':'');

		$builder->where($cond_text)->group(array("0"=>"user_id"));
		if($order_by!='')
			$builder->order(array($order_by),array($order_dir));

		if($skip>=0 && $nr_rows>0)
			$builder->limit($skip,$nr_rows);
		//echopre($builder);
		if($this->table)
		{
			$arr=$builder->execute();
		}

		if($calc_rows)
			$this->total_rows=$this->db->countTotalRows();
		return $arr;
	}
	function create_users_states_coordinators() {
		$sql = 'CALL create_users_states_coordinators()';
		$this->db->query($sql);
	}
}

?>