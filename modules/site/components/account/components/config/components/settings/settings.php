<?php

class settings_page extends Page
{
    var $table = "";

    function on_init()
    {
        $this->set_model('settings');
        $this->model_filter='hidden=1';
    }

    function action_edit($id)
    {
        $obj = $this->models->settings->get($id);

        $form = array();
        $form['name'] = "language_form";
        $form['id'] = "language_form";
        $form['btn_submit_text'] = "Salveaza";
        $form['btn_reset_text'] = "Reset";
        $form['btn_cancel_text'] = "Cancel";
        $form['btn_cancel_link'] = $this->paths['current'];
        $form['zones'] =
            array(
                "Modificare setare" => array(
                    "fields" => array(
                        "Actiunea:" => array(
                            "name" => "a",
                            "type" => "hidden",
                            "value" => "save:" . $obj['id']
                        ),
                        "Setare:" => array(
                            "name" => "description",
                            "type" => "none",
                            "value" => $obj['description']
                        )
                    )
                )
            );
        switch ($obj['type']) {
            case "active":
                $form['zones']['Modificare setare']['fields']["Valoare"] = array(
                    "name" => "value",
                    "type" => "radiolist",
                    "value" => $obj['value'],
                    "default" => 0,
                    "options" => array(
                        "Inactive" => 0,
                        "Active" => 1
                    )
                );
                break;
            case "html":
                $form['zones']['Modificare setare']['fields']["Valoare"] = array(
                    "name" => "value",
                    "type" => "htmleditor",
                    "value" => $obj['value']
                );
                break;
            case "text":
                $form['zones']['Modificare setare']['fields']["Valoare"] = array(
                    "name" => "value",
                    "type" => "textarea",
                    "value" => $obj['value']
                );
                break;
            case "id":
                $query = "select * from `" . $obj['from_table'] . "`";
                $objs = $this->models->db->getAll($query);

                $form['zones']['Modificare setare']['fields']["Valoare"] = array(
                    "name" => "value",
                    "type" => "select",
                    "selected" => $obj['value'],
                    "default" => "",
                    "default_show" => "true",
                    "default_text" => "- alege -",
                    "option_field_value" => "id",
                    "option_field_text" => $obj['from_field'],
                    "options" => $objs
                );
                break;
            case "array":
                $query = "select * from `" . $obj['from_table'] . "`";
                $objs = $this->models->db->getAll($query);

                foreach ($objs as $k => $v) {
                    $objs[$k]['name'] = $v[$obj['from_field']];
                    if (in_array($v['id'], $obj['value']))
                        $objs[$k]['checked'] = 1;
                }

                $form['zones']['Modificare setare']['fields']["Valoare"] = array(
                    "name" => "value",
                    "type" => "checkboxlist",
                    "options" => $objs
                );
                break;
        }

        $this->assign("form", $form);
    }

    function action_save($id = 0)
    {
        $params = array();
        if (is_array($_POST['value']))
            $params['value'] = ser($_POST['value']);
        else
            $params['value'] = $_POST['value'];

        $this->models->settings->update($params, "id=" . $id);

        $this->add_message("success", "S-a salvat setarea!");
        $this->redirect($this->paths['current'] . "?a=edit:$id");
    }
}