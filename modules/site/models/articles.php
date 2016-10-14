<?php
class articles extends Base {
	function _($code) {
		global $page;
		$code = urlencode($code);
		if ($article = $this -> get_cond("code='$code' and language_id=" . $page -> session['language_id']))
			return $article;
		else
			return $this -> get_cond("code=" . sat($code) . " and language_id=" . $page -> settings['default_language_id']['id']);
	}

	/**
	 *
	 * @return returneaza o lista de articole
	 * @param object $date[optional] - scoate articole de pe o anumita data numai daca e setata data
	 * @param object $type[optional] - scoate articole dupa tip optional
	 * @param object $parent_id[optional] - scoate articolele dupa parinte optional
	 */
	function getList($date = "", $type = "", $haskids = "0", $parent_id = NULL, $from = 0, $limit = 0) {
		$parent_id = intval($parent_id);
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . $this -> table . " WHERE 1";
		if (isset($type) and strlen($type) != 0) {
			$sql .= " AND type =" . sat($type);
		}//end if (isset($type) and $type !="")
		if (isset($date) and strlen($date) != 0) {
			$sql .= " AND date like " . sat($date);
		}//end if (isset($date) and $date !="")
		$sql .= " AND has_kids = " . intval($haskids);
		if (isset($parent_id) and $parent_id != 0) {
			$sql .= " AND parent_id = " . $parent_id;
		}//end if (isset($date) and $date !="")
		$sql .= " order by date DESC ";
		if ($limit != 0) {
			$sql .= " LIMIT " . $from . ", " . $limit;
		}//end if ($limit !=0)
		$result['result'] = $this -> db -> getAll($sql);
		$result['totalrows'] = $this -> db -> countTotalRows();
		return $result;
	}//end function getList($date,$type)

	/**
	 *
	 * @return
	 * @param object $type - tipul inregistrarii
	 * @param object $orderby - ordonat dupa
	 * @param object $ascdesc[optional] - ascendent sau descendent
	 * @param object $no_records - numar de inregistrari
	 * @param object $page[optional]  - numar pagini
	 */
	function getListType($type, $orderby, $ascdesc = 'DESC', $no_records, $page = 0) {
		$no_from = $no_records * $page;
		$sql = "SELECT * FROM " . $this -> table . " WHERE type =" . sat($type) . " ORDER BY " . sat($orderby) . " " . $ascdesc . " LIMIT " . $no_from . " , " . $no_records;
		$result = $this -> db -> getAll($sql);
		return $result;
	}//end function getListType($type,$orderby,$ascdesc,$no_records,$page=0)

	function getByLanguage($code, $language_id) {
		$sql = "SELECT * FROM " . $this -> table . " WHERE code = " . sat(urlencode($code)) . " and language_id = " . intval($language_id);
		$result = $this -> db -> getRow($sql);
		return $result;
	}//end function get($code)

	function process_row($row) {
		if (isset_or($row['id'])) {
			$row['others'] = unser($row['others']);
			$row['no_articles'] = $this -> count_all('parent_id=' . $row['id'] . ' and is_directory=0');
			$row['no_directories'] = $this -> count_all('parent_id=' . $row['id'] . ' and is_directory=1');
			if ($row['type'] == 'events' && !$row['is_directory'])
				$row['expired'] = strtotime($row['others']['start_date']) < strtotime(nowfull());
			$row['language'] = $this -> models -> languages -> get($row['language_id']);
			if (!$row['is_directory']) {
				global $dal;
				$row['translations'] = $this -> get_all('', '', '', '', 'parent_id=' . $row['id'] . ' and type="' . $row['type'] . '"');
				$languages = array();
				$languages[$row['language_id']] = $row['language_id'];
				foreach ($row['translations'] as $v)
					$languages[$v['language_id']] = $v['language_id'];

				$row['translatations_available'] = array();
				if (!$row['is_translation']) {
					$langs = $dal -> languages -> getactive();
					foreach ($langs as $v)
						if (!isset($languages[$v['id']]))
							$row['translatations_available'][] = $v['id'];
				}
				$row['kids'] = $this -> db -> getAll('select id,title from `' . $this -> table . '` where related_id=' . $row['id'] . ' and type="newpage" and is_directory=0 and is_translation=0');
				$row['images'] = $dal -> articles_images -> get_all('', '', 'order', '', 'article_id=' . $row['id']);
			}
		}
		return $row;
	}

	function delete($id,$callbacks=true) {
		global $dal;
		$kids = $this -> get_all('', '', '', '', 'parent_id=' . $id);
		foreach ($kids as $v) {
			if ($v['type'] == 'events' && isset_or($v['others']['product_id']))
				$dal -> sales_products -> delete($v['others']['product_id']);
			$this -> delete($v['id']);
		}
		parent::delete($id);
	}

	function get_code($title, $id = '') {
		$init_code = generate_seo_link($title);
		$code = $init_code;
		$i = 1;
		while ($this -> exists_cond('code="' + $code + '" and id!=' . sat($id))) {
			$code = $init_code . '-' . $i;
			$i++;
		}
		return $code;
	}

	function get_summary($content) {
		return substr(trim(preg_replace('/\s\s+/', ' ', strip_tags($content))), 0, 200);
	}

	function get_path($article_id) {
		if ($article_id != 0) {
			$article = $this -> get($article_id);
			$arr = array($article_id => $article_id);
			return array_merge($arr, $this -> get_path($article['parent_id']));
		} else
			return array(0);
	}

}
?>