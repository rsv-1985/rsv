<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
exec('git pull', $output);
exit('success');
if($output){
    foreach ($output as $text){
        echo $text.'<br>';
    }
}