<?php
/**
* Class locations_page
* @author BuildManager
*
*/
class locations_page extends Page
{
		function on_init()
		{
			$this->set_model('countries');
		}

		function on_no_action()
		{
			$this -> assign('table', $this -> get_model_table());
		}

		function on_load()
		{
		}

		function action_update()
		{
			$this->get_model_table(true);
		}
		
		function action_edit($id){
			$this->view='form';
			$this->assign('form',$this->model->get_admin_form('Edit country','',$this->paths['current'],'save:'.$id,$id));
		}
		
		function action_add(){
			$this->view='form';
			$this->assign('form',$this->model->get_admin_form('Add country','',$this->paths['current'],'save'));
		}
		
		function action_save($id=''){			
			if(!$id)
			{
				$id=$this->model->insert_from_admin_form('save');				
			}
			else
			{
				$this->model->update_from_admin_form($id,'save:'.$id);
			}
			if(isset_or($_POST['return']))
				$this->redirect($this->paths['current']);
			$this->redirect($this->paths['current'].'?a=edit:'.$id);
		}
		
		function action_delete($id){
			$this->model->delete($id);
			die;
		}
}
?>