<?php /**
 * Class types_page
 * @author BuildManager
 *
 */
class types_page extends Page
{
    function on_init()
    {
    }

    function index()
    {
        $this -> init_table();
        $this -> template -> assign('table', $this -> table -> get_array());
    }

    function on_load()
    {
        $this -> assign("is_master", $this -> user['is_master']);

        /*get permissions*/
        if (isset($this -> user['permissions']))
        {
            $permissions = array();
            foreach ($this->user['permissions'] as $perm)
            {
                $permissions[] = $perm['code'];
            }
        }
        else
            $permissions = "";

        if ($this -> system -> user['is_master'] != 1 && !in_array("user_types", $permissions))
        {
            $this -> add_message('error', "You don't have permission to view this page!");
            $this -> system -> redirect($this -> system -> paths['root_content']);
        }
    }

    function action_add()
    {
        /*get permissions*/
        if (isset($this -> user['permissions']))
        {
            $permissions = array();
            foreach ($this->user['permissions'] as $perm)
            {
                $permissions[] = $perm['code'];
            }
        }

        if ((isset($permissions) && in_array("user_types", $permissions)) || $this -> user['is_master'] == 1)
        {
            $types = $this -> models -> administrators_types -> get_all('', '', 'name', '');
            $form = array();
            $form['name'] = "admins_form";
            $form['id'] = "admins_form";
            $form['btn_submit_text'] = "Save";
            $form['btn_submit_return_text'] = "Salve & show list";
            $form['btn_reset_text'] = "Reset";
            $form['btn_cancel_text'] = "Cancel";
            $form['btn_cancel_link'] = $this -> system -> paths['current'];
            $permissions = $this -> models -> administrators_permissions -> get_all();
            $form['zones'] = array("Adaugare administrator" => array("fields" => array(
                        "Actiunea:" => array(
                            "name" => "a",
                            "type" => "hidden",
                            "value" => "save"
                        ),
                        "Name:" => array(
                            "name" => "name",
                            "type" => "text",
                            "value" => "",
                            "validate" => array("required" => "Fill in the name!")
                        ),
                        "Permissions:" => array(
                            "name" => "permissions",
                            "type" => "checkboxlist",
                            "value" => array(0),
                            "options" => $permissions
                        )
                    )));

            $this -> template -> assign("form", $form);
        }
        else
        {
            $this -> add_message('error', "You don't have permission to add user types!");
            $this -> system -> redirect($this -> system -> paths['root_content']);
        }
    }

    function action_edit($id)
    {
        /*get permissions*/
        if (isset($this -> user['permissions']))
        {
            $permissions = array();
            foreach ($this->user['permissions'] as $perm)
            {
                $permissions[] = $perm['code'];
            }
        }

        if ((isset($permissions) && in_array("user_types", $permissions)) || $this -> user['is_master'] == 1)
        {
            $type = $this -> models -> administrators_types -> get($id);
            $types = $this -> models -> administrators_types -> get_all('', '', 'name', '');
            $array_length = count($types);
            $types[$array_length]['id'] = 0;
            $types[$array_length]['name'] = 'SELF';

            //echopre($types);
            $form = array();
            $form['name'] = "admins_form";
            $form['id'] = "admins_form";
            $form['btn_submit_text'] = "Save";
            $form['btn_submit_return_text'] = "Salve & show list";
            $form['btn_reset_text'] = "Reset";
            $form['btn_cancel_text'] = "Cancel";
            $form['btn_cancel_link'] = $this -> system -> paths['current'];
            $permissions = $this -> models -> administrators_permissions -> get_all();
            foreach ($permissions as $ids => $perm)
            {
                if (in_array($perm['id'], unserialize($type['permissions'])))
                    $permissions[$ids]['checked'] = 1;
                else
                    $permissions[$ids]['checked'] = 0;
            }
            $form['zones'] = array("Adaugare administrator" => array("fields" => array(
                        "Actiunea:" => array(
                            "name" => "a",
                            "type" => "hidden",
                            "value" => "save:" . $id
                        ),
                        "Name:" => array(
                            "name" => "name",
                            "type" => "text",
                            "value" => $type['name'],
                            "validate" => array("required" => "Fill in the name!")
                        ),
                        "Permissions:" => array(
                            "name" => "permissions",
                            "type" => "checkboxlist",
                            "value" => unserialize($type['permissions']),
                            "options" => $permissions
                        )
                    )));
            //echopre(unserialize($type['permissions']));
            $this -> template -> assign("form", $form);
        }
        else
        {
            $this -> add_message('error', "You don't have permission to edit user types!");
            $this -> system -> redirect($this -> system -> paths['root_content']);
        }
    }

    function action_update()
    {
        $this -> init_table(1);

        $this -> table -> display_data();
    }

    function init_table($data = 0)
    {
        $this -> table = new AjaxTable();
        $this -> table -> id = 'table_admin_types';
        if ($data)
        {
            $this -> table -> header = array(
                array(
                    'col' => 'id',
                    'hidden' => 1
                ),
                array(
                    'name' => 'Name',
                    'col' => 'name',
                    'strong' => 1
                )
            );

            $this -> table -> process_request();

            $this -> table -> process_content($this -> models -> administrators_types -> GetAll($this -> system -> page_skip, $this -> system -> page_offset, $this -> table -> sort_by, $this -> table -> sort_dir, '', true, $this -> table -> get_search_fields(), $this -> table -> search_keyword));
            $this -> system -> no_total_rows = $this -> models -> administrators_types -> total_rows;

            $this -> table -> add_action('Modifica', '', $this -> system -> paths['current'] . '?a=edit:{$o.id.value}', '', 0, 'icon-pencil');
            if ($this -> system -> user['is_master'])
            {
                $this -> table -> add_action("Sterge", "", "", 'delete:{$o.id.value}', 1, "icon-trash-o", "Sunteti sigur ca doriti sa stergeti?");
            }
        }
        $this -> table -> update_action = 'update';
        $this -> table -> edit_link = 'none';
        $this -> table -> sort_col_no = 1;
        $this -> table -> total = $this -> system -> no_total_rows;
    }

    function action_save($id = 0)
    {
        /*get permissions*/
        if (isset($this -> user['permissions']))
        {
            $permissions = array();
            foreach ($this->user['permissions'] as $perm)
            {
                $permissions[] = $perm['code'];
            }
        }

        if ($this -> system -> user['is_master'] || (isset($permissions) && in_array("user_types", $permissions)))
        {
            $params = array();
            $params['name'] = $_POST['name'];
            $params['permissions'] = ser($_POST['permissions']);

            if ($id)
            {
                $this -> models -> administrators_types -> update($params, 'id=' . sat($id));
            }
            else
            {
                $id = $this -> models -> administrators_types -> insert($params);
            }
            $this -> add_message('success', 'Administrator type has been saved!');
            if (isset($_POST['return']) && $_POST['return'])
            {
                $this -> redirect($this -> system -> paths['current']);
            }
            else
            {
                $this -> redirect($this -> system -> paths['current'] . '?a=edit:' . $id);
            }
        }
    }

    function action_delete($id)
    {
        if ($this -> system -> user['is_master'])
        {
            $this -> models -> administrators_types -> delete($id);
            die ;
        }
    }

}
?>