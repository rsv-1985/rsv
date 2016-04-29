<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="footer-top-area" xmlns="http://www.w3.org/1999/html">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                    <div <?php if($this->config->item('is_buy')){?>style="display: none;" <?php } ?>>
                        Powered by<br>
                        <a href="http://cms.autoxcatalog.com/">
                            <img src="/image?img=/assets/themes/default/img/autox.png&height=150">
                        </a>
                    </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer-menu">
                    <ul>
                        <?php if($this->footer_page){?>
                            <?php foreach($this->footer_page as $fpage){?>
                                <li><a target="<?php echo $fpage['target'];?>" href="<?php echo $fpage['href'];?>"><?php echo $fpage['menu_title'];?></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer-menu">
                    <ul>
                        <?php foreach(explode(';',$this->contacts['phone']) as $phone){?>
                            <li><i class="fa fa-phone-square"></i><a href="#"> <?php echo $phone;?></a></li>
                        <?php } ?>
                        <?php foreach(explode(';',$this->contacts['email']) as $email){?>
                            <li><i class="fa fa-envelope"></i><a href="mailto:<?php echo $email;?>"> <?php echo $email;?></a></li>
                        <?php } ?>
                        <li><i class="fa fa-map-marker"></i> <?php echo $this->contacts['address'];?></li>
                        <li><i class="fa fa-link"></i><a href="<?php echo base_url();?>"> <?php echo $this->config->item('company_name');?></a></li>
                    </ul>      
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <?php echo $this->contacts['map'];?>
            </div>
        </div>
    </div>
</div> <!-- End footer top area -->

<div class="footer-bottom-area">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                
            </div>
            <div class="col-md-4">
                
            </div>
        </div>
    </div>
</div> <!-- End footer bottom area -->
<noindex>
<!-- Modal login-->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <?php echo form_open('/ajax/login',['id' => 'login_form']);?>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo lang('text_login_link');?></h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert" style="display: none"></div>
                    <div class="form-group">
                        <label><?php echo lang('text_login');?></label>
                        <input type="text" name="login" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_password');?></label>
                        <input type="password" name="password" required class="form-control">
                    </div>
                    <div class="form-group" style="text-align: center">
                        <a href="/customer/registration"><?php echo lang('text_registration');?></a>
                    </div>
                    <div class="form-group">
                        <input class="pull-right" type="submit" value="<?php echo lang('text_login_link');?>">
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal search-->
<div class="modal fade bs-example-modal-lg" id="search_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="product-big-title-area">
                <div class="product-bit-title text-center">
                    <strong id="search_query"></strong>
                </div>
            </div>
            <div class="col-md-3">
                <div id="popover"></div>
                <div class="list-group" id="search_brand_list"></div>
            </div>
            <div class="col-md-9" style="overflow: auto; max-height: 600px">
                <div class="search_result">
                    <h3><?php echo lang('text_change_brand');?></h3>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- Modal call-back-->
<div class="modal fade" id="call-back-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang('text_call_back');?></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert" style="display: none"></div>
                <?php echo form_open('/ajax/call_back',['id' => 'call_back_form']);?>
                    <div class="form-group">
                        <label><?php echo lang('text_call_back_name');?></label>
                        <input type="text" class="form-control" name="name" minlength="3" required>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_call_back_telephone');?></label>
                        <input type="text" class="form-control" name="telephone" minlength="3" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-default pull-right" value="<?php echo lang('button_send');?>" >
                    </div>
                <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>
</noindex>
<script src="<?php echo theme_url();?>js/jquery.min.js"></script>
<script src="<?php echo theme_url();?>js/function.js"></script>
<!-- Bootstrap JS form CDN -->
<script src="<?php echo theme_url();?>js/bootstrap.min.js"></script>
<!-- jQuery sticky menu -->
<script src="<?php echo theme_url();?>js/owl.carousel.min.js"></script>
<script src="<?php echo theme_url();?>js/jquery.sticky.js"></script>

<!-- Main Script -->
<script src="<?php echo theme_url();?>js/main.js"></script>

<!-- Slider -->
<script type="text/javascript" src="<?php echo theme_url();?>js/bxslider.min.js"></script>
<script type="text/javascript" src="<?php echo theme_url();?>js/script.slider.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        $('a[href="/<?php echo $this->uri->uri_string(); ?>"]').parents('li').addClass('active');
    });
</script>
<?php if($this->config->item('my_script')){?>
    <?php foreach ($this->config->item('my_script') as $script){?>
        <?php echo @file_get_contents(base_url($script));?>
    <?php } ?>
<?php } ?>
<?php $this->output->enable_profiler(FALSE);?>
</body>
</html>