<?php

    function userdata_get_user_level()
        {
            global $userdata;
            
            $user_level = '';
            for ($i=10; $i >= 0;$i--)
                {
                    if (current_user_can('level_' . $i) === TRUE)
                        {
                            $user_level = $i;
                            break;
                        }    
                }        
            return ($user_level);
        }
        
        
    function cpt_info_box()
        {
            ?>
               
            <?php   
        }

?>