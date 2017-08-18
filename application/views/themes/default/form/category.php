<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .tree{
        display: none;
        margin-left: 2%;
    }
</style>

<?php echo $this->category;?>
<script>
    $(document).ready(function(){
        $('.tree-toggle').click(function () {
            $(this).parent().children('ul.tree').toggle(200);
        });
    })
</script>
