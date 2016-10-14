<?php

class users extends Base{
	public $uses=array('users_friends');
    
	function __construct()
	{
		$this->table=$this->tbl_users;
	}
	
	function AdminInit()
	{
		$this->admin_fields=array(
			'id'=>array(
				'table'=>array(
					'name'=>'System ID'
				)
			),
			'main_shop_id'=>array(
				'table'=>array(
					'name'=>'Shop',
					'eval'=>'{if $o.main_shop_id.value}<a href="{$root_module}sales/shops/?a=edit:{$o.main_shop_id.value}">{bind table=$p.tables.tbl_shops get_field="name"}{$o.main_shop_id.value}{/bind}</a>{else}No shop added. <a href="{$root_module}sales/shops/?a=add:{$o.id.value}">Add shop.</a>{/if}'
				)
			),
			'first_name'=>array(
				'table'=>array(
					'name'=>'First Name',
					'strong'=>1
				),
				'import'=>array('col'=>3)
			),
			'last_name'=>array(
				'table'=>array(
					'name'=>'Last Name',
					'strong'=>1
				),
				'import'=>array('col'=>4)
			),			
			'email'=>array(
				'table'=>array(
					'name'=>'E-mail',
				),
				'import'=>array('col'=>5,'unique'=>1)
			),
			'last_login'=>array(
				'table'=>array(
					'name'=>'Last login',
				)
			)
		);
		global $page;
		$this->add_action('Sterge','','','delete:{$o.id.value}',1,'icon-trash-o','Sunteti sigur ca doriti sa stergeti?');
		$this->add_action('Modifica','',$page->paths['current'].'?a=edit:{$o.id.value}','',0,'icon-pencil');
	}

	function ProcessIfFriends($user_id,$list)
	{
		foreach($list as $k=>$v)
		{
			$list[$k]['is_friend']=$this->IsFriend($v['id'],$user_id);
			if(!$list[$k]['is_friend'])
				$list[$k]['is_requested']=$this->IsRequested($v['id'],$user_id);
		}
		
		return $list;
	}
	
	function GetIfFreinds($user_id,$row)
	{
		$row['is_friend']=$this->IsFriend($row['id'],$user_id);
		if(!$row['is_friend'])
			$row['is_requested']=$this->IsRequested($row['id'],$user_id);
		
		return $row;
	}
	
	function IsFriend($user_id,$friend_id)
	{
		return $this->users_friends->IsFriend($user_id,$friend_id);
	}
	
	function IsRequested($user_id,$friend_id)
	{
		return $this->users_friends->IsRequested($user_id,$friend_id);
	}

	function export($file_name='export.xls',$user_fields=array(),$udata_fields=array(),$header=1,$where="")
	{
		if(!$udata_fields)$udata_fields=array();
		if(!$user_fields)$user_fields=array();
		global $page;
		global $dal;
		$dal->import('udata');
		$page->import('class','SimpleExcel');
		
		$user_txt="";
		if(count($user_fields))
		{
			foreach($user_fields as $k=>$v)
				$user_txt.='`'.$this->table.'`.`'.$k.'`, ';
			$user_txt=substr($user_txt,0,strlen($user_txt)-2);
		}
		$join_text='';
		$udata_txt='';
		//echopre($udata_fields);
		if(count($udata_fields))
		{
			foreach($udata_fields as $udata=>$fields)
			{
				$join_text.=' left join `'.$dal->$udata->table.'` on `'.$this->table.'`.`id`=`'.$dal->$udata->table.'`.`user_id`';
				foreach($fields as $k=>$v)
				{
					$udata_txt.='`'.$dal->$udata->table.'`.`'.$k.'`, ';
				}
			}
			$udata_txt=substr($udata_txt,0,strlen($udata_txt)-2);
		}
		
		if($udata_txt)
			$query='select '.($user_txt?$user_txt.', ':'').' '.$udata_txt.' from `'.$this->table.'` '.$join_text;
		else
			$query='select '.$user_txt.' from `'.$this->table.'`';
		
		if ($where){
			$query.= ' WHERE'.$where;
		}
		//echopre($query);
		$output=$this->db->getAll($query);
		foreach($output as $key=>$value){
			unset($output[$key]['user_id']);
		}
		if($header)
		{
			if(count($udata_fields))
			{
				foreach($udata_fields as $udata=>$fields)
				{
					foreach($fields as $k=>$v)
					{
						$user_fields[$k]=$v;
					}
				}
			}
			$header_arr=array_keys($user_fields);
			foreach($header_arr as $key=>$value){
				if ($value == 'user_id'){
					unset($header_arr[$key]);
				}
			}
			$output=array_merge(array($header_arr),$output);
		}
		global $page;
		$page->import('class','objects.SimpleExcel');
		SimpleExcel::export($file_name,$output);
		die();
	}

	function delete($id)
	{
		global $dal;
		$user=$this->get($id);
		if($user['company_id'])
			$dal->companies->employee_add($user['company_id']);
		$dal->udata_fzone->delete_cond('user_id='.$id);
		$dal->udata_flbreak->delete_cond('user_id='.$id);
		$dal->udata_iolib->delete_cond('user_id='.$id);
		parent::delete($id);
	}

	function ValidateEmail($email,$user_id=0)
	{
		if($email && $this->exists_cond('(lower(email)="'.strtolower($email).'") and id!='.$user_id))
			return 0;
		return 1;
	}
	
	function ValidateUsername($username, $user_id = 0)
	{
		if ($username && $this -> exists_cond('lower(display_name)=lower("' . trim($username) . '") and id!=' . $user_id))
			return 0;
		return 1;
	}

	function get_admin_table($id='ajax_table',$data=0,$cond='',$update_action='update',$edit_link='none',$sort_col_no=0,$admin_init_function='AdminInit')
	{
		global $page;
		if(method_exists($this,$admin_init_function))
		{
			$this->$admin_init_function();
		}
		else
		{
			$trace = debug_backtrace();
        	trigger_error(
            	'Undefined function '.get_class($this).'->'.$admin_init_function.':'.
            	' in ' . $trace[0]['file'] .
            	' on line ' . $trace[0]['line'],
           	 E_USER_NOTICE);
        	return null;
		}
			$table=new AjaxTable();
			$table->id=$id;
			if($data)
			{
				foreach($this->admin_fields as $k=>$v)
					if(isset($v['table']))
						$table->header[]=array_merge(array('col'=>$k),$v['table']);
			
				$table->process_request();
			
				$table->process_content($this->get_all($page->page_skip,$page->page_offset,$table->sort_by,$table->sort_dir,$cond,true,$table->get_search_fields(),$table->search_keyword));
				$page->no_total_rows=$this->total_rows;
				
				foreach($this->admin_actions as $v)
					$table->add_action(isset($v['title'])?$v['title']:'',isset($v['text'])?$v['text']:'',isset($v['link'])?$v['link']:'',isset($v['onclick'])?$v['onclick']:'',isset($v['refresh'])?$v['refresh']:1,isset($v['icon'])?$v['icon']:'',isset($v['confirm'])?$v['confirm']:'');
			}
			$table->update_action=$update_action;
			$table->edit_link=$edit_link;
			$table->sort_col_no=$sort_col_no;
			$table->total=$page->no_total_rows;
		
		if($data)
			$table->display_data();
		else
			return $table->get_array();
	}
	
 	/* Returns the rows from the current table limited and sorted using the parameters
     * @return
     * @param object $skip[optional]
     * @param object $nr_rows[optional]
     * @param object $order_by[optional]
     * @param object $order_dir[optional]
     * @param object $cond[optional]
     * @param object $calc_rows[optional]
     * @param object $search_fields[optional]
     * @param object $keyword[optional]
     */
	public function get_all($skip='',$nr_rows='',$order_by='',$order_dir='',$cond='',$calc_rows=true,$search_fields='',$keyword='')
	{
		$cond_text='';
		$order_text='';
		$skip_text='';
		
		$cond_s='';
		if($search_fields && $keyword)
			$cond_s=$this->_searchLikeCond($search_fields,$keyword);
		
		if($cond!='')
			$cond_text='where ('.$cond.')'.($cond_s?' and ('.$cond_s.')':'');
		else
			$cond_text=($cond_s?'where ('.$cond_s.')':'');

		$skip_text=(($skip>=0 && $nr_rows>0)?' limit '.$skip.','.$nr_rows:'' );
		
		if(is_array($order_by))
		{
			$order_text=' order by ';
			foreach($order_by as $k=>$v)
			{
				$order_text.='`'.$k.'` ';
				if($v=='-')
					$order_text.=' DESC, ';
				else
					$order_text.=', ';
			}
			$order_text=substr($order_text,0,strlen($order_text)-2);
		}
		elseif($order_by!='')
			$order_text=' order by `'.$this->table.'`.`'.$order_by.'` '.$order_dir;
			
		$calc_text='';
		if($calc_rows)$calc_text='SQL_CALC_FOUND_ROWS';
		
		if($this->table)
		{
			$query='select '.$calc_text.' `'.$this->table.'`.*from '.$this->table.' '.$cond_text.' '.$order_text.$skip_text;
			$arr=$this->db->getAll($query);
		}
		
		if($calc_rows)
			$this->total_rows=$this->db->countTotalRows();
		if(method_exists($this,'ProcessRow'))
		{
			return $this->ProcessArray($arr);
		}
		return $arr;
	}

	function GetByEmail($email)
	{
		return $this->get_cond('lower(Email)=lower('.sat($email).') or lower(WorkEmail)=lower('.sat($email).') or lower(HomeEmail)=lower('.sat($email).')');
	}
}

?>