<?php


class settings extends Base
{
    function AdminInit()
    {
        $this->admin_fields = array(
            'id' => array(
                'table' => array(
                    'hidden' => 1
                )
            ),
            'type' => array(
                'table' => array(
                    'hidden' => 1
                )
            ),
            'from_table'=>array(
                'table'=>array(
                    'hidden'=>1
                )
            ),
            'from_field'=>array(
                'table'=>array(
                    'hidden'=>1
                )
            ),
            'description'=>array(
                'table'=>array(
                    'strong' => 1
                )
            ),
            'value'=>array(
                'table'=>array(
                    'eval' => '{if $o.type.value=="id"}{bind table=$o.from_table.value get_field=$o.from_field.value}{$o.value.value}{/bind}{elseif $o.type.value=="array"}{foreach item=val from=$o.value.value}{bind table=$o.from_table.value get_field=$o.from_field.value}{$val}{/bind},{/foreach}{else}{$o.value.value}{/if}'
                )
            )

        );
        global $page;
        $this->add_action('Edit', '', $page->paths['current'] . '?a=edit:{$o.id.value}', '', 0, 'icon-pencil', '');
        $this->add_action("Sterge", "", "", 'delete:{$o.id.value}', 1, "icon-trash-o", "Sunteti sigur ca doriti sa stergeti?");
    }

    function process_row($row)
    {
        if (isset($row['id'])) {
            if ($row['type'] == 'array')
                $row['value'] = unser($row['value']);
        }

        return $row;
    }
}

?>