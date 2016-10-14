<?php

class steps_page extends Page
{
    var $admin = 1;

    function on_init()
    {
    }

    function on_load()
    {
        if (!isset($this->system->actions[0])) {
            $this->init_table();
            $this->assign('table', $this->table->get_array());
        }
    }

    function action_trad($language_id)
    {
        $text = $this->models->locales_texts->GetForTranslation($language_id, 0, $this->admin);
        $this->models->languages->admin = $this->admin;
        $language = $this->models->languages->get($language_id);
        if (isset($text['id'])) {
            $form = array();
            $form['name'] = "texte_form";
            $form['action_link'] = $this->paths['current'];
            $form['id'] = "texte_form";
            $form['btn_submit_text'] = "Salveaza";
            $form['btn_submit_return_text'] = "Salveaza si iesi";
            $form['btn_reset_text'] = "Reset";
            $form['btn_cancel_text'] = "Cancel";
            $form['btn_cancel_link'] = $this->paths['current'];
            $form['zones'] =
                array(
                    "Traducere text" => array(
                        "fields" => array(
                            "Actiunea:" => array(
                                "name" => "a",
                                "type" => "hidden",
                                "value" => "save_text:" . $text['id'] . ":" . $language_id
                            ),
                            "Textul standard:" => array(
                                "name" => "",
                                "type" => "none",
                                "value" => addslashes($text['value'])
                            ),
                            "Traducere in limba:" => array(
                                "name" => "",
                                "type" => "none",
                                "value" => $language['valoare']
                            ),
                            "Procent tradus:" => array(
                                "name" => "progress",
                                "type" => "progressbar",
                                "value" => number_format($language['percent_translated'], 2)
                            ),
                            "Traducere text:" => array(
                                "name" => "text",
                                "type" => "htmleditor",
                                "value" => $text['value'],
                                "validate" => array("required" => "Completati textul!")
                            )
                        )
                    )
                );
            $this->assign("form", $form);
        } else {
            $this->add_message("success", "Tracerea e 100% completa!");
            $this->redirect($this->paths['current']);
        }
    }

    function action_continue($language_id)
    {
        $this->redirect($this->paths['current'] . "?a=trad:$language_id");
    }

    function action_save_text($id, $language_id = "")
    {
        if (!$language_id && isset($_REQUEST['language_id']))
            $language_id = $_REQUEST['language_id'];
        $this->models->locales_translations->save(array('text_id' => $id, 'language_id' => $language_id, 'value' => $_REQUEST['text'], 'admin' => $this->admin));

        $this->add_message("success", "S-a salvat traducerea!");
        if ($_POST['return'] == 1)
            $this->redirect($this->paths['current']);
        else
            $this->redirect($this->paths['current'] . "?a=continue:$language_id");

    }

    function action_update()
    {
        $this->init_table(1);

        $this->table->display_data();
    }

    function init_table($data = 0)
    {
        $this->table = new AjaxTable();
        $this->table->id = 'table_texte_steps';
        if ($data) {
            $this->table->header = array(
                array(
                    'col' => 'id',
                    'hidden' => 1
                ),
                array(
                    'name' => 'Limba',
                    'col' => 'valoare'
                ),
                array(
                    'name' => 'Procent tradus',
                    'col' => 'percent_translated',
                    'eval' => '{$o.percent_translated.value|string_format:"%.2f"} %'
                )
            );

            $this->table->process_request();

            $this->models->languages->admin = $this->admin;
            $this->table->process_content($this->models->languages->get_all($this->system->page_skip, $this->system->page_offset, $this->table->sort_by, $this->table->sort_dir, 'is_active=1', true, $this->table->get_search_fields(), $this->table->search_keyword));
            $this->system->no_total_rows = $this->models->languages->total_rows;

            $this->table->add_action("Continua traducerea", "", $this->paths['current'] . '?a=trad:{$o.id.value}', '', 0, "icon-forward", "");
            $this->table->add_action("Exporta", "", $this->paths['current'] . '?a=export:{$o.id.value}', '', 0, "icon-download", "");
            $this->table->add_action("Importa", "", $this->paths['current'] . '?a=import:{$o.id.value}', '', 0, "icon-upload", "");
        }
        $this->table->update_action = 'update';
        $this->table->edit_link = 'none';
        $this->table->sort_col_no = 1;
        $this->table->total = $this->system->no_total_rows;
    }

    function action_export($language_id)
    {
        $this->models->languages->admin = $this->admin;
        $this->models->languages->ExportFile($language_id);
    }

    function action_import($language_id)
    {
        $lang = $this->models->languages->get($language_id);
        $form = array();
        $form['name'] = "texte_form";
        $form['id'] = "texte_form";
        $form['btn_submit_text'] = "Importa";
        $form['btn_reset_text'] = "Reset";
        $form['btn_cancel_text'] = "Cancel";
        $form['btn_cancel_link'] = $this->paths['current'];
        $form['zones'] =
            array(
                "Importa limba din xml" => array(
                    "fields" => array(
                        "Actiunea:" => array(
                            "name" => "a",
                            "type" => "hidden",
                            "value" => "import_save:" . $language_id
                        ),
                        "Limba:" => array(
                            'type' => 'none',
                            'value' => $lang['valoare']
                        ),
                        "Fisier:" => array(
                            "name" => "file",
                            "type" => "file",
                            "value" => "",
                            'validate' => array('required' => 'Selectati un fisier!')
                        )
                    )
                )
            );

        $this->assign("form", $form);
    }

    function action_import_save($language_id)
    {
        $this->models->languages->admin = $this->admin;
        if ($this->models->languages->ImportFile($language_id))
            $this->add_message("success", "Fisierul a fost importat cu success!");
        else
            $this->add_message("error", "Limba setata in fisier nu corespunde cu limba selectata!");
        $this->redirect($this->paths['current']);
    }
}

?>
