<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
exec('git pull', $output);
echo '<pre>';
print_r($output);
echo '</pre>';