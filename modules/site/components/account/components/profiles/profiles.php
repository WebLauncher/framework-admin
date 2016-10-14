<?php

class profiles_page extends Page
{
    function on_init()
    {
    }

    function on_load()
    {
    }

    function index()
    {
        $this->init_table();
        $this->assign('table', $this->table->get_array());
    }

    function action_add()
    {
        if ($this->system->user['is_master']) {
            $form = array();
            $form['name'] = "admins_form";
            $form['id'] = "admins_form";
            $form['btn_submit_text'] = "Salveaza";
            $form['btn_submit_return_text'] = "Salveaza si afiseaza lista";
            $form['btn_reset_text'] = "Reset";
            $form['btn_cancel_text'] = "Cancel";
            $form['btn_cancel_link'] = $this->paths['current'];
            $form['zones'] =
                array(
                    "Adaugare administrator" => array(
                        "fields" => array(
                            "Actiunea:" => array(
                                "name" => "a",
                                "type" => "hidden",
                                "value" => "save"
                            ),
                            "Utilizatorul:" => array(
                                "name" => "username",
                                "type" => "text",
                                "value" => "",
                                "validate" => array("required" => "Completati utilizatorul!")
                            ),
                            "Parola:" => array(
                                "name" => "password",
                                "type" => "password",
                                "value" => "",
                                "validate" => array("required" => "Completati parola!")
                            ),
                            "E-mail:" => array(
                                "name" => "email",
                                "type" => "text",
                                "value" => "",
                                "validate" => array("required" => "Completati email-ul corect!",
                                    "email" => "Completati email-ul corect!")
                            ),
                            "Prenume:" => array(
                                "name" => "firstname",
                                "type" => "text",
                                "value" => "",
                                "validate" => array("required" => "Completati prenumele!")
                            ),
                            "Numele:" => array(
                                "name" => "lastname",
                                "type" => "text",
                                "value" => "",
                                "validate" => array("required" => "Completati numele!")
                            ),
                            "Master:" => array(
                                "name" => "master",
                                "type" => "radiolist",
                                "default" => 0,
                                "options" => array(
                                    "Nu" => 0,
                                    "Da" => 1
                                )
                            ),
                            "Activ:" => array(
                                "name" => "active",
                                "type" => "radiolist",
                                "default" => 0,
                                "options" => array(
                                    "Nu" => 0,
                                    "Da" => 1
                                )
                            )
                        )
                    )
                );

            $this->assign("form", $form);
        } else {
            $this->redirect($this->paths['root_content']);
        }
    }

    function action_save($id = 0)
    {
        if ($this->system->user['is_master'] || $this->system->user['id'] == $id) {

            $username = $_REQUEST['username'];
            $password = $_REQUEST['password'];
            $email = $_REQUEST['email'];
            $fistname = $_REQUEST['firstname'];
            $lastname = $_REQUEST['lastname'];
            $is_master = $_REQUEST['master'];
            $is_active = $_REQUEST['active'];
            if ($id) {
                $params = array();
                $params['language_id'] = $_REQUEST['language_id'];
                $this->models->administrators->update($params, 'id=' . $id);
            }
            if (!$this->models->administrators->exists("user", $username) || ($id && (!$this->models->administrators->exists("user", $username) || $this->models->administrators->get_field($id, "user") == $username))) {
                if ($this->models->administrators->save(array('id' => $id, 'username' => $username, 'password' => sha1($password), 'email' => $email, 'firstname' => $fistname, 'lastname' => $lastname, 'is_maste' => $is_master, 'is_active' => $is_active))) {
                    $this->add_message("success", "S-a salvat administratorul!");
                    if (isset($_REQUEST['return']))
                        $this->redirect($this->paths['current'] . "?a=edit:$id");
                    else
                        $this->redirect($this->paths['current']);
                }
            } else {
                $this->add_message("error", "Utilizatorul exista!");
                $this->redirect($this->system->user['is_master'] ? $this->paths['current'] : $this->paths['root_content']);
            }
        } else {
            $this->redirect($this->paths['current']);
        }
    }

    function action_edit($id)
    {
        if ($this->system->user['is_master'] || $this->system->user['id'] == $id) {
            $obj = $this->models->administrators->get($id);
            $languages = $this->models->languages->GetActive();
            $form = array();
            $form['name'] = "language_form";
            $form['id'] = "language_form";
            $form['btn_submit_text'] = "Salveaza";
            $form['btn_submit_return_text'] = "Salveaza si afiseaza lista";
            $form['btn_reset_text'] = "Reset";
            $form['btn_cancel_text'] = "Cancel";
            $form['btn_cancel_link'] = $this->system->user['is_master'] ? $this->paths['current'] : $this->paths['root_content'];
            $form['zones'] =
                array(
                    "Modificare cont administrator" => array(
                        "fields" => array(
                            "Actiunea:" => array(
                                "name" => "a",
                                "type" => "hidden",
                                "value" => "save:" . $obj['id']
                            ),
                            "Utilizatorul:" => array(
                                "name" => "username",
                                "type" => "text",
                                "value" => $obj['user'],
                                "validate" => array("required" => "Completati utilizatorul!")
                            ),
                            "Schimba parola:" => array(
                                "name" => "password",
                                "type" => "password",
                                "value" => ""
                            ),
                            "E-mail:" => array(
                                "name" => "email",
                                "type" => "text",
                                "value" => $obj['email'],
                                "validate" => array("required" => "Completati email-ul corect!",
                                    "email" => "Completati email-ul corect!")
                            ),
                            "Prenume:" => array(
                                "name" => "firstname",
                                "type" => "text",
                                "value" => $obj['firstname'],
                                "validate" => array("required" => "Completati prenumele!")
                            ),
                            "Numele:" => array(
                                "name" => "lastname",
                                "type" => "text",
                                "value" => $obj['lastname'],
                                "validate" => array("required" => "Completati numele!")
                            )

                        )
                    ),
                    'Setari Profil' => array(
                        'fields' => array(
                            "Limba:" => array(
                                "name" => "language_id",
                                "type" => "radioimages",
                                "value" => $obj['language_id'],
                                "field_value" => "id",
                                "field_image" => "image_id",
                                "field_title" => "valoare",
                                "options" => $languages,
                                "validate" => array("required" => "Alegeti limba!")
                            )
                        )
                    )
                );

            if ($this->system->user['is_master']) {
                $form['zones']["Modificare cont administrator"]["fields"]["Master:"] = array(
                    "name" => "master",
                    "type" => "radiolist",
                    "value" => $obj['is_master'],
                    "default" => 0,
                    "options" => array(
                        "Nu" => 0,
                        "Da" => 1
                    )
                );
                $form['zones']["Modificare cont administrator"]["fields"]["Activ:"] = array(
                    "name" => "active",
                    "type" => "radiolist",
                    "value" => $obj['is_active'],
                    "default" => 0,
                    "options" => array(
                        "Nu" => 0,
                        "Da" => 1
                    )
                );
            }

            $this->assign("form", $form);
        } else {
            $this->redirect($this->paths['root_content']);
        }
    }

    function action_update()
    {
        $this->init_table(1);

        $this->table->display_data();
    }

    function init_table($data = 0)
    {
        $this->table = new AjaxTable();
        $this->table->id = 'table_general_states';
        if ($data) {
            $this->table->header = array(
                array(
                    'col' => 'id',
                    'hidden' => 1
                ),
                array(
                    'name' => 'Utilizator',
                    'col' => 'user',
                    'strong' => 1
                ),
                array(
                    'name' => 'E-mail',
                    'col' => 'email'
                ),
                array(
                    'name' => 'Prenume',
                    'col' => 'firstname'
                ),
                array(
                    'name' => 'Super Administrator',
                    'col' => 'is_master',
                    'active' => 1,
                    'action' => 'master'
                ),
                array(
                    'name' => 'Activ',
                    'col' => 'is_active',
                    'active' => 1
                ),
                array(
                    'name' => 'Ultima logare',
                    'col' => 'last_login'
                ),
                array(
                    'name' => 'Creat',
                    'col' => 'created'
                )
            );

            $this->table->process_request();

            $this->table->process_content($this->models->administrators->get_all($this->system->page_skip, $this->system->page_offset, $this->table->sort_by, $this->table->sort_dir, '', true, $this->table->get_search_fields(), $this->table->search_keyword));
            $this->system->no_total_rows = $this->models->administrators->total_rows;

            $this->table->add_action('Modifica', '', $this->paths['current'] . '?a=edit:{$o.id.value}', '', 0, 'icon-pencil');
            $this->table->add_action("Sterge", "", "", 'delete:{$o.id.value}', 1, "icon-trash-o", "Sunteti sigur ca doriti sa stergeti?");
        }
        $this->table->update_action = 'update';
        $this->table->edit_link = 'none';
        $this->table->sort_col_no = 1;
        $this->table->total = $this->system->no_total_rows;
    }

    function action_active($id, $value)
    {
        $this->models->administrators->set_active($id, $value);
        die;
    }

    function action_master($id, $value)
    {
        $this->models->administrators->update_field($id, "is_master", $value);
        die;
    }

    function action_delete($id)
    {
        $this->models->administrators->delete($id);
        die;
    }

}

?>
