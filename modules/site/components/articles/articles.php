<?php

class articles_page extends Page {
	var $table = "";

	function on_init() {
		$this->system->enable_404=false;
		if (!isset($this -> session['articole']['display_type'])) {
			$this -> session['articole']['display_type'] = 'months';
		}
		if (isset($this -> system -> subquery[2]))
			$this->view='articles_'.$this -> system -> subquery[2];
		if (isset($_REQUEST['type']) && in_array($_REQUEST['type'], array('main','events','newpage')))
			$this->view='articles_'.$_REQUEST['type'];
	}

	function on_load() {
		$this -> session['articles']['parent_id'] = isset_or($_REQUEST['article'], 0) ? $_REQUEST['article'] : 0;

		if (isset_or($_REQUEST['type'],isset_or($this -> system -> subquery[2]))) {
			$this -> session['articles_type'] = isset_or($_REQUEST['type'],isset_or($this -> system -> subquery[2]));
			$this -> init_table();

			$folder = '';

			if ($this -> session['articles']['parent_id']) {
				$folder = $this -> models -> articles -> get($this -> session['articles']['parent_id']);

			} else {
				$folder = array('title' => 'Root Directory', 'no_directories' => $this -> models -> articles -> count_all('parent_id=0 and is_directory=1 and type="' . $this -> session['articles_type'] . '"'), 'no_articles' => $this -> models -> articles -> count_all('parent_id=0 and is_directory=0 and type="' . $this -> session['articles_type'] . '"'), 'is_directory' => 1);
			}

			$this -> assign('folder', $folder);
			$this -> assign('cpath', array_reverse($this -> models -> articles -> get_path($this -> session['articles']['parent_id'])));
		}
	}

	function action_save($id = 0) {
		if ($id) {
			$article = $this -> models -> articles -> get($id);

			$pars = array();
			$pars['title'] = $_POST['_title'];
			$pars['summary'] = isset_or($_POST['summary']) ? $_POST['summary'] : $this -> models -> articles -> get_summary($_POST['_content']);
			$pars['content'] = html_entity_decode(stripslashes($_POST['_content']));
			$pars['language_id'] = $_POST['language_id'];
			$pars['has_kids'] = (isset($_POST['has_kids']) ? $_POST['has_kids'] : 0);
			$pars['parent_id'] = (isset($_POST['parent_id']) ? $_POST['parent_id'] : 0);
			$pars['date'] = nowfull();
			$pars['code'] = isset_or($_POST['code']) ? $_POST['code'] : $this -> models -> articles -> get_code($pars['title'], $id);
			if ($_POST['type'] == "events") {
				$others = $article['others'];
				$others['start_date'] = $_POST['start_date'] . ' ' . $_POST['start_time'] . ':00';
				$others['end_date'] = $_POST['end_date'] . ' ' . $_POST['end_time'] . ':00';
				$others['address'] = $_POST['address'];
				$others['country'] = $_POST['country'];
				$others['state'] = $_POST['state'];
				$others['city'] = $_POST['city'];
				$others['zip'] = $_POST['zip'];
				$others['timezone'] = $_POST['timezone'];
				$pars['others'] = ser($others);
			}
			if ($article['image_id'])
				$this -> models -> articles_images -> save_request("image", $article['id'], $article['image_id'], $pars['summary']);
			else
				$pars['image_id'] = $this -> models -> articles_images -> save_request("image", $article['id'], '', $pars['summary']);

			$this -> models -> articles -> update($pars, "id=" . $id);
			$this -> add_message("success", "S-a salvat articolul cu succes!");
		} else {
			$pars = array();
			$pars['title'] = $_POST['_title'];
			$pars['type'] = $_POST['type'];
			$pars['summary'] = isset_or($_POST['summary']) ? $_POST['summary'] : $this -> models -> articles -> get_summary($_POST['_content']);
			$pars['language_id'] = $_POST['language_id'];
			$pars['content'] = $_POST['_content'];
			$pars['has_kids'] = (isset($_POST['has_kids']) ? $_POST['has_kids'] : 0);
			$pars['parent_id'] = (isset($_POST['parent_id']) ? $_POST['parent_id'] : 0);
			$pars['related_id'] = isset_or($_POST['parent_id']) ? $this -> models -> articles -> get_field($_POST['parent_id'], 'related_id') : 0;
			$pars['date'] = date("y-m-d h:i");
			$pars['code'] = (isset_or($_POST['code']) ? $_POST['code'] : $this -> models -> articles -> get_code($_POST['_title']));
			if ($_POST['type'] == "events") {
				$others = array();
				$others['start_date'] = $_POST['start_date'] . ' ' . $_POST['start_time'] . ':00';
				$others['end_date'] = $_POST['end_date'] . ' ' . $_POST['end_time'] . ':00';
				$others['address'] = $_POST['address'];
				$others['country'] = $_POST['country'];
				$others['state'] = $_POST['state'];
				$others['city'] = $_POST['city'];
				$others['zip'] = $_POST['zip'];
				$others['timezone'] = $_POST['timezone'];
				$pars['others'] = ser($others);
			}

			$id = $this -> models -> articles -> insert($pars);
			$image_id = $this -> models -> articles_images -> save_request("image", $id, '', $pars['summary']);
			$this -> models -> articles -> update_field($id, 'image_id', $image_id);
			$this -> add_message("success", "S-a salvat articolul cu succes!");
		}

		$parent_folder = $this -> models -> articles -> get($_POST['parent_id']);
		$article = $this -> models -> articles -> get($id);
		$this -> models -> articles -> update_field($id, 'others', ser($article['others']));

		if (isset($_POST['return']) && $_POST['return'])
			$this -> redirect($this -> system -> paths['current']);
		else
			$this -> redirect($this -> system -> paths['current'] . "?a=edit:" . $article['id']);
	}

	function action_edit($id = 0) {
		$article = $this -> models -> articles -> get($id);
		$languages = $this -> models -> languages -> GetActive();
		$this -> assign('page_title', 'Edit article');
		$this -> assign('languages', $languages);
		$this -> assign('article', $article);

		$this -> view = 'edit';
		if ($this -> system -> subquery[2] == 'events') {
			$article = $this -> models -> articles -> get($id);
			$parent_folder = $this -> models -> articles -> get($article['parent_id']);
			if ($parent_folder['others']['has_payment']) {
				$this -> assign('category', $this -> models -> sales_products_categories -> get($parent_folder['others']['category_id']));
				$this -> assign('product', $this -> models -> sales_products -> get($article['others']['product_id']));
			}
			$this -> assign('parent_folder', $parent_folder);
			$this -> assign('countries', $this -> models -> xcountries -> get_all());
			$this -> view = 'edit_event';
		}
	}

	function action_trad_save() {
		$pars = array();
		$pars['title'] = $_POST['_title'];
		$pars['type'] = $_POST['type'];
		$pars['summary'] = $_POST['summary'];
		$pars['language_id'] = $_POST['language_id'];
		$pars['content'] = $_POST['_content'];
		$pars['has_kids'] = (isset($_POST['has_kids']) ? $_POST['has_kids'] : 0);
		$pars['parent_id'] = (isset($_POST['parent_id']) ? $_POST['parent_id'] : 0);
		$pars['date'] = date("y-m-d h:i");
		$pars['code'] = (isset($_POST['code']) ? $_POST['code'] : urlencode($_POST['_title']));

		$id = $this -> models -> articles -> insert($pars);
		$this -> add_message("success", "S-a tradus articolul cu succes!");

		$article = $this -> models -> articles -> get($id);
		if (isset($_POST['return']) && $_POST['return'])
			$this -> redirect($this -> system -> paths['current']);
		else
			$this -> redirect($this -> system -> paths['current'] . "?a=edit:" . $article['id']);
	}

	function action_trad($id = 0) {
		$article = $this -> models -> articles -> get($id);
		$languages = $this -> models -> languages -> GetActive();
		$form = array();
		$form['name'] = "texte_form";
		$form['id'] = "texte_form";
		$form['btn_submit_text'] = "Salveaza";
		$form['btn_submit_return_text'] = "Salveaza si afiseaza lista";
		$form['btn_reset_text'] = "Reset";
		$form['btn_cancel_text'] = "Cancel";
		$form['btn_cancel_link'] = $this -> system -> paths['current'];
		$form['zones'] = array("Traducere articol" => array("fields" => array("Actiunea:" => array("name" => "a", "type" => "hidden", "value" => "trad_save"), "Code:" => array("name" => "code", "type" => "hidden", "value" => $article['code']), "Cod:" => array("name" => "code", "type" => "none", "value" => $article['code']), "Tipul:" => array("name" => "type", "type" => "hidden", "value" => $this -> system -> subquery[2]), "Titlu:" => array("name" => "_title", "type" => "text", "value" => $article['title'], "validate" => array("required" => "Completati titlul!")), "Sumar:" => array("name" => "summary", "type" => "textarea", "value" => $article['summary']), "Limba:" => array("name" => "language_id", "type" => "radioimages", "value" => $article['language_id'], "field_value" => "id", "field_image" => "image_id", "field_title" => "valoare", "options" => $languages, "validate" => array("required" => "Alegeti limba!")), "Continut:" => array("name" => "_content", "type" => "htmleditor", "value" => $article['content'], "validate" => array("required" => "Completati continutul articolului!")))));

		$this -> template -> assign("form", $form);
	}

	function action_add($parent_id = 0) {
		$languages = $this -> models -> languages -> GetActive();
		$this -> assign('languages', $languages);
		$this -> assign('parent_id', $parent_id);
		$this -> assign('page_title', 'Add new article');

		if ($this -> system -> subquery[2] == 'events') {
			$parent_folder = $this -> models -> articles -> get($parent_id);
			if ($parent_folder['others']['has_payment']) {
				$this -> assign('category', $this -> models -> sales_products_categories -> get($parent_folder['others']['category_id']));
			}
			$this -> assign('parent_folder', $parent_folder);
			$this -> assign('countries', $this -> models -> xcountries -> get_all());
			$this -> view = 'add_event';
		} else
			$this -> view = 'add';
	}

	function action_update() {
		$this -> init_table(1);

		$this -> table -> display_data();
	}

	function init_table($data = 0) {
		$this -> assign('folders', $this -> models -> articles -> get_all('', '', 'title', '', 'type="' . $this -> session['articles_type'] . '" and parent_id=' . $this -> session['articles']['parent_id'] . ' and is_directory=1'));
		$articles = array();
		switch($this->system->subquery[2]) {
			case 'events' :
				$articles = $this -> models -> articles -> get_all('', '', 'date', '', 'type="' . $this -> session['articles_type'] . '" and parent_id=' . $this -> session['articles']['parent_id'] . ' and is_directory=0');
				usort($articles, function($a, $b) {
					if ($a['others']['start_date'] > $b['others']['start_date'])
						return -1;
					else if ($a['others']['start_date'] < $b['others']['start_date'])
						return 1;
					return 0;
				});
				if (!isset_or($this -> session['articole']['show_past'])) {
					$articles = array_filter($articles, function($article) {
						return $article['others']['start_date'] > nowfull();
					});
				}
				break;
			default :
				$articles = $this -> models -> articles -> get_all('', '', 'date', '', 'type="' . $this -> session['articles_type'] . '" and parent_id=' . $this -> session['articles']['parent_id'] . ' and is_directory=0');
				break;
		}

		$this -> assign('articles', $articles);
		$this -> assign('display_type', isset_or($this -> session['articole']['display_type']));
	}

	function action_delete($id) {
		$article = $this -> models -> articles -> get($id);
		$this -> models -> articles -> delete($id);
		if ($this -> system -> ajax)
			die();
		$this -> add_message('success', 'Item removed!');
		$this -> redirect($this -> paths['current']);
	}

	function action_new_folder($parent_id = 0) {
		if ($this -> system -> subquery[2] == 'events')
			$this -> assign('categories', $this -> models -> sales_products_categories -> get_tree());
		$languages = $this -> models -> languages -> GetActive();
		$this -> assign('languages', $languages);
		$this -> view = 'new_folder';
		$this -> assign('parent_id', $parent_id);
		$this -> assign('page_title', 'Add new directory');
	}

	function action_edit_folder($folder_id = 0) {
		if ($this -> system -> subquery[2] == 'events')
			$this -> assign('categories', $this -> models -> sales_products_categories -> get_tree());
		$this -> assign('related', $this -> models -> articles -> get_all('', '', 'title', '', 'is_directory=0 and is_translation=0'));
		$this -> assign('directory', $this -> models -> articles -> get($folder_id));
		$languages = $this -> models -> languages -> GetActive();
		$this -> assign('languages', $languages);
		$this -> view = 'edit_folder';
		$this -> assign('page_title', 'Edit directory');

	}

	function action_save_folder($folder_id = 0) {
		$params = array();

		$params['title'] = $_POST['_title'];
		$params['code'] = $_POST['code'];
		$params['summary'] = $_POST['summary'];
		$params['type'] = $_POST['type'];
		$params['language_id'] = $_POST['language_id'];
		$params['others'] = ser($_POST['others']);
		$params['parent_id'] = $_POST['parent_id'];
		$params['related_id'] = $_POST['related_id'];
		$params['is_directory'] = 1;
		$params['has_kids'] = 1;
		$params['date'] = nowfull();

		if ($folder_id) {
			$folder = $this -> models -> articles -> get($folder_id);
			if ($folder['image_id'])
				$this -> models -> images -> save_request("image", "articles", $folder['image_id']);
			else
				$params['image_id'] = $this -> models -> images -> save_request("image", "articles");
			$this -> models -> articles -> update($params, 'id=' . $folder_id);
		} else {
			if (isset_or($_FILES['image']['name']))
				$params['image_id'] = $this -> models -> images -> save_request("image", "articles");
			$folder_id = $this -> models -> articles -> insert($params);
		}

		if (isset($_POST['return']) && $_POST['return'])
			$this -> redirect($this -> system -> paths['current'] . '?article=' . $params['parent_id']);
		else
			$this -> redirect($this -> system -> paths['current'] . "?a=edit_folder:" . $folder_id);
	}

	function action_states($code, $default = '') {
		$this -> view = 'states';
		$country = $this -> models -> xcountries -> get_cond('code="' . $code . '"');
		$this -> template -> assign('default', $default);
		if (isset_or($country['id']))
			$this -> assign('states', $this -> models -> xjudete -> get_all('', '', '', '', 'country_id=' . $country['id']));
	}

	function action_display_mode($mode = 'months') {
		$this -> session['articole']['display_type'] = $mode;
	}

	function action_show_past() {
		$this -> session['articole']['show_past'] = isset_or($this -> session['articole']['show_past']) ? 0 : 1;
	}

	function action_add_translation_save($article_id) {
		$article = $this -> models -> articles -> get($article_id);
		if (in_array($_REQUEST['language_id'], $article['translatations_available'])) {
			$params = array();
			$params['type'] = $article['type'];
			$params['title'] = $article['title'];
			$params['code'] = $article['code'];
			$params['content'] = $article['content'];
			$params['date'] = nowfull();
			$params['language_id'] = $_REQUEST['language_id'];
			$params['summary'] = $article['summary'];
			$params['parent_id'] = $article['id'];
			$params['has_kids'] = 0;
			$params['image_id'] = 0;
			$params['others'] = ser($article['others']);
			$params['is_translation'] = 1;
			$id = $this -> models -> articles -> insert($params);

			$this -> add_message('success', 'Traducerea a fost adaugata!');
			$this -> redirect($this -> paths['current'] . '?a=edit:' . $id);
		}
		$this -> add_message('error', 'Traducerea in limba aceasta nu se poate adauga.');
		$this -> redirect($this -> paths['current'] . '?a=edit:' . $article['id']);
	}

	function action_images($id) {
		$this -> assign('article', $this -> models -> articles -> get($id));
		$this -> view = 'images';
	}

	function action_image_add($id) {
		$this -> assign('article', $this -> models -> articles -> get($id));
		$this -> view = 'image_add';
	}

	function action_image_edit($id, $image_id) {
		$this -> assign('article', $this -> models -> articles -> get($id));
		$this -> assign('image', $this -> models -> articles_images -> get($image_id));
		$this -> view = 'image_edit';
	}

	function action_image_save($article_id, $image_id = 0) {
		$this -> models -> articles_images -> save_request("image", $article_id, $image_id, $_REQUEST['description'], $_REQUEST['url'], $_REQUEST['target']);
		echo 1;
		die ;
	}

	function action_set_main_image($article_id, $image_id) {
		$this -> models -> articles -> update_field($article_id, 'image_id', $image_id);
		$this -> action_images($article_id);
	}

	function action_image_remove($article_id, $image_id) {
		$art = $this -> models -> articles -> get($article_id);
		if ($art['image_id'] == $image_id)
			$this -> models -> articles -> update_field($article_id, 'image_id', 0);
		$this -> models -> articles_images -> delete($image_id);
		$this -> view = 'images';
		$this -> action_images($article_id);
	}

}
?>