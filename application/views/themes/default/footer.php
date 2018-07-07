<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($this->seo_text){?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->seo_text;?>
            </div>
        </div>
    </div>
<?php } ?>
<div class="footer-top-area" xmlns="http://www.w3.org/1999/xhtml">
    <div class="container">
        <div class="row">

            <div class="col-md-3 col-sm-6">
                <?php echo form_open('/ajax/newsletter',['id' => 'newsletter']);?>
                <div class="form-group">
                    <label><?php echo lang('text_heading_newsletter');?></label>
                    <input type="email" name="email" class="form-control" placeholder="email" required>
                    <div class="alert-danger"></div>
                </div>
                <div class="form-group">
                    <button class="btn-default" type="submit"><?php echo lang('button_newsletter');?></button>
                </div>
                <p><?php echo lang('text_newsletter');?></p>
                </form>
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
                            <li><i class="fa fa-phone-square"></i> <?php echo $phone;?></li>
                        <?php } ?>
                        <?php foreach(explode(';',$this->contacts['email']) as $email){?>
                            <li><i class="fa fa-envelope"></i> <a href="mailto:<?php echo $email;?>"> <?php echo $email;?></a></li>
                        <?php } ?>
                        <li><i class="fa fa-map-marker"></i> <?php echo $this->contacts['city'];?> <?php echo $this->contacts['address'];?></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 g_map">
                <?php echo $this->contacts['map'];?>
            </div>
        </div>
    </div>
</div> <!-- End footer top area -->
<?php if(@$this->options['show_callback']){?>
<!--PHONE BTN-->
<div class="dws"  data-toggle="modal" data-target="#call-back-modal">
    <div class="pulse">
        <div class="bloc"></div>
        <div class="phone"><i class="fa fa-phone" aria-hidden="true"></i></div>
        <div class="text">Кнопка связи</div>
    </div>
</div>
<?php } ?>

<div class="footer-bottom-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-social pull-left">
                    <?php if(isset($this->contacts['vk']) && $this->contacts['vk']){?>
                        <a href="<?php echo $this->contacts['vk'];?>" target="_blank" rel="nofollow">
                            <img src="<?php echo theme_url();?>/img/vk_hover.svg" alt="vk"/>
                        </a>
                    <?php } ?>
                    <?php if(isset($this->contacts['google']) && $this->contacts['google']){?>
                        <a href="<?php echo $this->contacts['google'];?>" target="_blank" rel="nofollow">
                            <img src="<?php echo theme_url();?>/img/google-plus_hover.svg" alt="google+"/>
                        </a>
                    <?php } ?>
                    <?php if(isset($this->contacts['instagram']) && $this->contacts['instagram']){?>
                        <a href="<?php echo $this->contacts['instagram'];?>" target="_blank" rel="nofollow">
                            <img src="<?php echo theme_url();?>/img/insta_hover.svg" alt="instagram"/>
                        </a>
                    <?php } ?>
                    <?php if(isset($this->contacts['fb']) && $this->contacts['fb']){?>
                        <a href="<?php echo $this->contacts['fb'];?>" target="_blank" rel="nofollow">
                            <img src="<?php echo theme_url();?>/img/fb_hover.svg" alt="facebook"/>
                        </a>
                    <?php } ?>
                </div>

                <div class="pull-right" id="powered">
                    <?php if(@$this->contacts['copyright']){?>
                        <?php echo $this->contacts['copyright'];?>
                    <?php }else{ ?>
                        <div>
                            <a href="https://autox.pro" title="Создание интернет-магазина автозапчастей">Autox.pro</a>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div> <!-- End footer bottom area -->
</div>
    <!-- Modal login-->
    <div class="modal fade" id="login" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <?php echo form_open('/ajax/login',['id' => 'login_form']);?>
            <div class="modal-content">
                <div class="product-big-title-area">
                    <div class="product-bit-title text-center">
                        <?php echo lang('text_login_link');?>
                    </div>
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
                        <a href="/customer/registration"><?php echo lang('text_registration');?></a> |
                        <a href="/customer/forgot"><small><?php echo lang('text_remainder');?></small></a>
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
    <div class="modal fade bs-example-modal-lg" id="search_modal" tabindex="-1" role="dialog">
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
                    <a href="#" class="pull-right" onclick="location.reload();return false;">Открыть в новом окне</a>
                    <div class="search_result">
                        <div style="text-align: center;font-size: 24px;margin: 0 0 15px;"><?php echo lang('text_change_brand');?></div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- Modal call-back-->
    <div class="modal fade" id="call-back-modal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="product-big-title-area">
                    <div class="product-bit-title text-center">
                        <?php echo lang('text_call_back');?>
                    </div>
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
                        <button type="submit" class="pull-right"><?php echo lang('button_send');?></button>
                    </div>
                    <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal fast order-->
    <div class="modal fade" id="fast-order-modal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="product-big-title-area">
                    <div class="product-bit-title text-center">
                        Быстрый заказ
                    </div>
                </div>
                <div class="modal-body" id="fast-order-body">
                    <?php echo form_open('/ajax/fastorder',['id' => 'fast_order_form']);?>
                    <input type="hidden" name="href" id="fast-order-product" value="" required>
                    <div class="form-group">
                        <label>Имя</label>
                        <input type="text" class="form-control" name="name" minlength="3">
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="text" class="form-control" name="telephone" minlength="3" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="pull-right"><?php echo lang('button_send');?></button>
                    </div>
                    <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <link href='https://fonts.googleapis.com/css?family=Play:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php echo theme_url();?>css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo theme_url();?>css/owl.carousel.css">
<link rel="stylesheet" href="<?php echo theme_url();?>css/responsive.css">

    <script src="<?php echo theme_url();?>js/bootstrap.min.js"></script>
    <script src="<?php echo theme_url();?>js/jquery.maskedinput.min.js"></script>
    <script src="<?php echo theme_url();?>js/function.js?v9"></script>
    <script src="<?php echo theme_url();?>js/owl.carousel.min.js"></script>
    <script src="<?php echo theme_url();?>js/main.js?v6"></script>
    <script type="text/javascript" src="<?php echo theme_url();?>js/bxslider.min.js"></script>
    <script type="text/javascript" src="<?php echo theme_url();?>js/script.slider.js"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $('a[href="/<?php echo $this->uri->uri_string(); ?>"]').parents('li').addClass('active');
            $("[name='telephone']").mask("<?php echo @$this->options['phonemask'];?>");
            $("[name='phone']").mask("<?php echo @$this->options['phonemask'];?>");
        });
    </script>

    <?php if($this->config->item('my_script')){?>
        <?php foreach ($this->config->item('my_script') as $script){?>
            <?php echo @file_get_contents(base_url($script));?>
        <?php } ?>
    <?php } ?>
    <?php if(ENVIRONMENT == 'development' || $this->input->get('debug_show')){
        $this->output->enable_profiler(TRUE);
    }?>

    </body>
    </html>