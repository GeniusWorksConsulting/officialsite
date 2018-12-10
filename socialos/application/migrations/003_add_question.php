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
class Migration_Add_question extends CI_Migration {

    //add profile picture column
    public function up() {
        $this->dbforge->add_field(array(
            'question_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'evaluation' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'weight' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ),
            'cat_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'comment' => 'Admin Id'
            ),
            'is_parent' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '0: Parent 1: Child'
            ),
            'has_child' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '0: No 1: Yes'
            ),
            'answer_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'count' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '0: No 1: Yes'
            ),
            'parent_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE
            ),
            'no_answer' => array(
                'type' => 'INT',
                'constraint' => 5
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
        $this->dbforge->add_key('question_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('question', TRUE, $attributes);
    }

    public function down() {
        $this->dbforge->drop_table('category');
    }

}
