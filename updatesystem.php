<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
exec('git pull', $output);

if($output){
    exit('Success!');
}
exit('Error');