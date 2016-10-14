<?php
	set_time_limit(1000000);
	ini_set("memory_limit","128M");

	$this->multi_language=true;
	$this->admin=1;
	$this->seo_enabled=true;
	$this->set_module_user_type("admin");
	$this->secure_request_enabled=false;
	$this->logins_logs_enabled=true;
	$this->check_cookies=true;
	$this->settings_enabled=true;
	
	$this->pagination[0]=10;

	$this->add_css_file('{$skin_styles}screen.css');
	$this->add_css_file('{$skin_styles}ui.css');
	$this->add_css_file('{$skin_styles}ie.css','text/css','screen, projection','if IE');
	$this->add_css_file('{$skin_styles}font-awesome.min.css');

	$this->add_js_file('{$skin_scripts}jquery/jquery.js');
	$this->add_js_file('{$skin_scripts}jquery/jquery-ui/jquery-ui.js');
	$this->add_js_file('{$skin_scripts}form/jquery.form.js');
	$this->add_js_file('{$skin_scripts}jquery-ui-ext/gridview.js');
	$this->add_js_file('{$skin_scripts}jquery-ui-ext/spinner.js');
	$this->add_js_file('{$skin_scripts}jquery-ui-ext/timepicker.js');
	$this->add_js_file('{$skin_scripts}floatingbox/stayontop.js');
	$this->add_js_file('{$skin_scripts}timer/jquery.timer.js');
	$this->add_js_file('{$skin_scripts}infieldlabel/jquery.infieldlabel.min.js');
	$this->add_js_file('{$skin_scripts}treeview/jquery.treeview.min.js');
	$this->add_js_file('{$skin_scripts}validation/jquery.validate.js');
	$this->add_js_file('{$skin_scripts}validation/jquery.validate-ext.js');
	$this->add_js_file('{$skin_scripts}tooltip/jquery.tooltip.min.js');
	$this->add_js_file('{$skin_scripts}private.js');
	$this->add_js_file('{$skin_scripts}swfobject/swfobject.js');
	$this->add_js_file('{$skin_scripts}admin.js');
	
	$this->hocks->add('before_session_init',function(){
        session_set_cookie_params(864000);
    });
	
	$this->hocks->add('after_session_init',function(){
		$_SESSION['expire']=time()+864000;
	})
?>