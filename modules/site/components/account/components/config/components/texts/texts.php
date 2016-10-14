<?php

class texts_page extends Page
{
    var $admin = 1;
    var $table = '';

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

    function action_edit($id, $language_id = "")
    {
        if ($language_id != "") {
            $text = $this->models->translations->get($id, $language_id);
        } else {
            $text = $this->models->locales_texts->get($id);
            $language_id=$this->system->settings['admin_default_language']['id'];
        }
        $language=$this->models->locales->get($language_id);

        $form = array();
        $form['name'] = "texte_form";
        $form['id'] = "texte_form";
        $form['btn_submit_text'] = "Salveaza";
        $form['btn_submit_return_text'] = "Salveaza si afiseaza lista";
        $form['btn_reset_text'] = "Reset";
        $form['btn_cancel_text'] = "Cancel";
        $form['btn_cancel_link'] = $this->paths['current'];
        $form['zones'] =
            array(
                "Modificare text" => array(
                    "fields" => array(
                        "Actiunea:" => array(
                            "name" => "a",
                            "type" => "hidden",
                            "value" => "save_text:" . $id . ($language_id ? ":" . $language_id : "")
                        ),
                        "Limba hidden:" => array(
                            "name" => "language_id",
                            "type" => "hidden",
                            "value" => @$text['language_id']
                        ),
                        "Textul curent:" => array(
                            "name" => "",
                            "type" => "none",
                            "value" => $text['value']
                        ),
                        "Limba:" => array(
                            "name" => "",
                            "type" => "image",
                            "value" => @$text['valoare'],
                            "url" => $this->paths['skin_images'].'languages/'.$language['code'].'.png'
                        ),
                        "Modifica textul:" => array(
                            "name" => "text",
                            "type" => "htmleditor",
                            "value" => $text['value'],
                            "validate" => array("required" => "Completati textul!")
                        )
                    )
                )
            );

        $this->assign("form", $form);
    }

    function action_save_text($id, $language_id = "")
    {
        if (!$language_id && isset($_REQUEST['language_id']))
            $language_id = $_REQUEST['language_id'];
        $this->models->translations->save($id, $language_id, $_REQUEST['text'], $this->admin);

        $this->add_message("success", "S-a salvat textul!");
        if ($_POST['return'] == 1)
            $this->redirect($this->paths['current']);
        else
            $this->redirect($this->paths['current'] . "?a=edit:" . $id . ($language_id ? ":" . $language_id : ":" . $_REQUEST['language_id']));

    }

    function action_trad($id, $language_id = "")
    {
        if ($language_id != "") {
            $text = $this->models->translations->get($id, $language_id);
        } else {
            $text = $this->models->locales_texts->get($id);
        }
        $languages = $this->models->languages->getactive();

        $form = array();
        $form['name'] = "texte_form";
        $form['id'] = "texte_form";
        $form['btn_submit_text'] = "Salveaza";
        $form['btn_submit_return_text'] = "Salveaza si afiseaza lista";
        $form['btn_reset_text'] = "Reset";
        $form['btn_cancel_text'] = "Cancel";
        $form['btn_cancel_link'] = $this->paths['current'];
        $form['zones'] =
            array(
                "Traducere text" => array(
                    "fields" => array(
                        "Limba selectata:" => array(
                            "name" => "",
                            "type" => "image",
                            "value" => @$text['language'],
                            "url" => @$text['image_url']
                        ),
                        "Actiunea:" => array(
                            "name" => "a",
                            "type" => "hidden",
                            "value" => "save_text:" . $text['id'] . ($language_id ? $language_id : "")
                        ),
                        "Textul curent:" => array(
                            "name" => "",
                            "type" => "none",
                            "value" => $text['value']
                        ),
                        "Limba:" => array(
                            "name" => "language_id",
                            "type" => "radioimages",
                            "value" => isset($text['language_id']) ? $text['language_id'] : 0,
                            "field_value" => "id",
                            "field_image" => "image_id",
                            "field_title" => "valoare",
                            "options" => $languages,
                            "validate" => array("required" => "Alegeti limba!")
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
    }

    function action_update()
    {
        $this->init_table(1);

        $this->table->display_data();
    }

    function init_table($data = 0)
    {
        $this->table = new AjaxTable();
        $this->table->id = 'table_texte_texts';
        if ($data) {
            $this->table->header = array(
                array(
                    'col' => 'id',
                    'hidden' => 1
                ),
                array(
                    'name' => 'Text',
                    'col' => 'value'
                ),
                array(
                    'name' => 'Traduceri',
                    'col' => 'languages',
                    'array' => 1,
                    'eval' => '<a href="{$current}?a=edit:{$o.id.value}:{$obj.id}" title="{tr}Editeaza{/tr}">{image alt=$obj.code}{$p.paths.skin_images}languages/{$obj.code}.png{/image} {$obj.valoare}</a>',
                    'no_items' => '- nu este tradus -',
                    'search' => 0
                )
            );

            $this->table->process_request();

            $this->table->process_content($this->models->locales_texts->get_all($this->system->page_skip, $this->system->page_offset, $this->table->sort_by, $this->table->sort_dir, 'admin=' . $this->admin, true, $this->table->get_search_fields(), $this->table->search_keyword));
            $this->system->no_total_rows = $this->models->locales_texts->total_rows;

            $this->table->add_action('Modifica', '', $this->paths['current'] . '?a=edit:{$o.id.value}', '', 0, 'icon-pencil');
            $this->table->add_action("Traducere", "", $this->paths['current'] . '?a=trad:{$o.id.value}', '', 0, "icon-doc_convert", "");
        }
        $this->table->update_action = 'update';
        $this->table->edit_link = 'none';
        $this->table->sort_col_no = 1;
        $this->table->total = $this->system->no_total_rows;
    }
}