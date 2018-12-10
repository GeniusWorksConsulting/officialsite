<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Migration_Add_profile
 *
 * @author StepUp - Chirag
 */
class Migration_Add_category extends CI_Migration {

    //add profile picture column
    public function up() {
        $this->dbforge->add_field(array(
            'cat_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'cat_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'weighting' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE
            ),
            'sub_ass_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'created' => array(
                'type' => 'datetime',
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'comments' => '0 Active 1 Deleted'
            ),
        ));
        $this->dbforge->add_key('cat_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('category', TRUE, $attributes);
    }

    public function down() {
        $this->dbforge->drop_table('category');
    }

}
