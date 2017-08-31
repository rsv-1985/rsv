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
        margin-left:10%;
    }
    .nav>li>a {
        position: relative;
        display: block;
        padding: 10px 15px;
        cursor: pointer;
    }
    a.tree-toggle.active {
        background: #f5f5f5;
    }
</style>

<?php echo $this->category;?>
<script>
    $(document).ready(function(){
        $('.tree-toggle').click(function () {
            $(".nav a").each(function(index, item){
                $(item).removeClass('active');
            });
            $(this).addClass('active');
            $(this).parent().children('ul.tree').toggle(200);
        });
    })
</script>
