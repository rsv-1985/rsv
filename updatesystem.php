<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */


exec('git pull', $output);

if($output){
    exit('<h3 style="text-align: center;">Система обновлена.</h3><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Адаптивный1 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9020295635568297"
     data-ad-slot="6031374792"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>');
}
exit('Error');