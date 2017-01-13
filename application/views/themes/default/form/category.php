<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    @media(min-width:768px){.affix-content .container{width:700px}.affix-content .container .page-header{margin-top:0}.sidebar-nav{width:100%}.affix-sidebar{padding-right:0;font-size:small;padding-left:0}.affix-row,.affix-container,.affix-content{height:100%;margin-left:0;margin-right:0}.affix-content{background-color:white}.sidebar-nav .navbar .navbar-collapse{padding:0;max-height:none}.sidebar-nav .navbar{border-radius:0;margin-bottom:0;border:0}.sidebar-nav .navbar ul{float:none;display:block}.sidebar-nav .navbar li{float:none;display:block}.sidebar-nav .navbar li a{padding-top:12px;padding-bottom:12px}}@media(min-width:769px){.affix-content .container{width:600px}.affix-content .container .page-header{margin-top:0}}@media(min-width:992px){.affix-content .container{width:900px}.affix-content .container .page-header{margin-top:0}}@media(min-width:1220px){.affix-row{overflow:hidden}.affix-content{overflow:auto}.affix-content .container{width:1000px}.affix-content .container .page-header{margin-top:0}.affix-content{padding-right:30px;padding-left:30px}.affix-title{border-bottom:1px solid #ecf0f1;padding-bottom:10px}.navbar-nav{margin:0}.navbar-collapse{padding:0}.sidebar-nav .navbar li a:hover{background-color:#428bca;color:white}.sidebar-nav .navbar li a>.caret{margin-top:8px}}
</style>
<?php if ($this->category) { ?>
    <div class="panel-heading">
        <?php echo lang('text_category'); ?>
    </div>
    <div class="sidebar-nav">
        <div class="navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="visible-xs navbar-brand"></span>
            </div>
            <div class="navbar-collapse collapse sidebar-navbar-collapse">
                <ul class="nav navbar-nav" id="sidenav01">
                    <?php foreach ($this->category as $category){?>
                    <li>
                        <?php if($category['children']){?>
                        <a href="#" onclick="return false;" data-toggle="collapse" data-target="#toggle<?php echo $category['id'];?>" data-parent="#sidenav01" class="collapsed">
                            <?php echo $category['name'];?> <span class="caret pull-right"></span>
                        </a>
                        <div class="collapse" id="toggle<?php echo $category['id'];?>" style="height: 0px;">
                            <ul class="nav nav-list">
                                <?php foreach ($category['children'] as $child){?>
                                    <li><a href="/category/<?php echo $child['slug'];?>"> <?php echo $child['name'];?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php }else{ ?>
                            <a href="/category/<?php echo $category['slug'];?>">
                                <?php echo $category['name'];?> <span class="caret pull-right"></span>
                            </a>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <hr/>
<?php } ?>

