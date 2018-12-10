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
class Migration_Add_profile extends CI_Migration {

    //add profile picture column
    public function up() {
        $fields = array(
            'profile' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'after' => 'phone',
                'null' => TRUE
            ),
        );
        $this->dbforge->add_column('users', $fields);
    }

    public function down() {
        $this->dbforge->drop_table('users');
    }

}
