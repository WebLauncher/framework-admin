<?php



class currencies extends Base{
    function __construct(){
		$this->table=$this->tbl_currencies;
	}
	
	function AdminInit(){
		$this->admin_fields=array(
			'id'=>array(
				'table'=>array(
					'hidden'=>1
				)
			),
			'name'=>array(
				'table'=>array('name'=>'Name'),
				'form'=>array(
					'type'=>'text',
					'label'=>'Name',
					'validate'=>array('required'=>'Please fill in the name!')
				) 
			),
			'code'=>array(
				'table'=>array('name'=>'Code'),
				'form'=>array(
					'type'=>'text',
					'label'=>'Code',
					'validate'=>array('required'=>'Please fill in the code!')
				) 
			),
			'exchange_rate'=>array(
				'table'=>array('name'=>'Exchange Ratee'),
				'form'=>array(
					'type'=>'text',
					'label'=>'Exchange Rate'				
				) 
			),
			'is_active' => array(
					'table' => array(
						'name'=>'Active',
						'active'=>'1'
					),
					'form'=>array(
						'type'=>'radiolist',
						'label'=>'Is Active?',
						'options'=>array('Yes'=>1,'No'=>0)
					)
				),
			'is_default' => array(
					'table' => array(
						'name'=>'Default',
						'eval'=>'{if $o.is_defalut}Yes{else}No{/if}'
					),
					'form'=>array(
						'type'=>'radiolist',
						'label'=>'Is Default?',
						'options'=>array('Yes'=>1,'No'=>0)
					)
				)
		);
		global $page;
		$this -> add_action('Edit', '', $page -> paths['current'] . '?a=edit:{$o.id.value}', '', 0, 'icon-pencil', '');
		$this -> add_action("Sterge","","",'delete:{$o.id.value}',1,"icon-trash-o","Sunteti sigur ca doriti sa stergeti?");
	}
}

?>