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
class Migration_Add_answer extends CI_Migration {

    //add profile picture column
    public function up() {
        $this->dbforge->add_field(array(
            'answer_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'answer' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'rating' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ),
            'weighting' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,3'
            ),
            'is_zero' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '0: No 1: Whole Assessment 0'
            ),
            'section_zero' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '0: No 1: Whole Section 0'
            ),
            'question_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'comment' => 'FK Question Table'
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
        
        $this->dbforge->add_key('answer_id', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('answer', TRUE, $attributes);
    }

    public function down() {
        $this->dbforge->drop_table('answer');
    }

}
