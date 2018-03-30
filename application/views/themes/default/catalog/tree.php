<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .row.item {
        border: 1px solid #e5e5e5;
        margin: 5px;
        padding: 5px;
    }

    .single-shop-product > .info{
        background: #4cbbb9;
        color: black;
        height: 1px;
        overflow: hidden;
        z-index: 999;
        text-align: left;
        font-size: 14px;
        padding-top: 1px;
        padding-left: 5px;
        padding-right: 5px;
        position: relative;
    }
</style>
<div class="product-big-title-area">
    <div class="container">
        <div class="row-fluid">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1; ?></h1>
                    <?php if (isset($this->garage[md5($name)]) && $this->input->get('id_tree') && !isset($this->garage[md5($name)]['category'][$this->input->get('id_tree')])) { ?>
                        <a rel="nofollow" class="btn btn-info"
                           href="<?php echo current_url(); ?>?add_tree=1"><?php echo lang('text_add_tree_garage'); ?></a>
                    <?php } ?>
                    <?php if (!isset($this->garage[md5($name)])) { ?>
                        <a rel="nofollow" class="btn btn-info" href="<?php echo current_url(); ?>?add_garage=1"
                           role="button"><?php echo lang('text_add_auto_garage'); ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row-fluid">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <?php foreach ($breadcrumb as $b) { ?>

                    <? if ($b == end($breadcrumb)) {
                        ?>
                        <li><?php echo $b['title']; ?></li>

                        <?
                    } else {
                        ?>
                        <li><a href="<?php echo $b['href']; ?>"><?php echo $b['title']; ?></a></li>
                        <?

                    }
                    ?>
                <?php } ?>
            </ol>
            <div class="col-md-8">
                <?php if ($this->input->get('id_tree')) { ?>
                    <?php if (@$parts) { ?>
                        <?php foreach ($parts as $part){?>
                            <?php if ($part->product && $part->product['prices']) {?>
                                <div class="col-md-4 col-sm-6 filters-item">
                                    <div class="filters-key" style="display: none">
                                        <?php if (isset($part->filter_key)){ ?>
                                            <?php foreach ($part->filter_key as $filter_key) {echo $filter_key . ' ';} ?>
                                        <?php } ?>
                                    </div>
                                    <div class="single-shop-product">
                                        <div class="product-upper">
                                            <a href="/product/<?php echo $part->product['slug'];?>">
                                                <img onerror="this.src='/assets/themes/default/img/no_image.png'"
                                                     src="<?php echo $part->Image; ?>"
                                                     alt="<?php echo $part->Name . ' ' . $part->Brand . ' купить'; ?>"
                                                     title="<?php echo $part->Name . ' ' . $part->Brand . ' купить'; ?>"
                                                     style="max-height: 150px;">
                                            </a>
                                        </div>
                                        <small><?php echo $part->Brand.' '.$part->Search;?></small>
                                        <h2>
                                            <a href="/product/<?php echo $part->product['slug'];?>"><?php echo $part->Name;?></a>
                                        </h2>
                                        <div class="product-carousel-price">
                                            <b>
                                                <?php echo format_currency($part->product['prices'][0]['price']); ?>
                                            </b>
                                        </div>
                                        <div class="product-option-shop">
                                            <a class="btn btn-default" data-toggle="modal" data-target="#modal-price-<?php echo $part->product['id'];?>" href="#"><?php echo lang('button_cart');?></a>
                                        </div>
                                        <div class="modal fade" id="modal-price-<?php echo $part->product['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title" id="myModalLabel"><?php echo plural_form(count($part->product['prices']),[lang('text_offer_1'),lang('text_offer_2'),lang('text_offer_5')]);?></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php foreach ($part->product['prices'] as $price){ $key = $part->product['id'] . $price['supplier_id'] . $price['term'] ?>
                                                            <div class="row item">
                                                            <div class="col-md-3">
                                                                <?php echo format_currency($price['saleprice'] > 0 ? $price['saleprice'] : $price['price']);?>
                                                                <?php if($price['excerpt']){?>
                                                                    <br/>
                                                                    <small>
                                                                        <?php echo $price['excerpt'];?>
                                                                    </small>
                                                                <?php } ?>

                                                            </div>
                                                            <div class="col-md-3">
                                                                <?php echo format_term($price['term']);?>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <?php echo format_quantity($price['quantity']);?>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(), event)', 'method' => 'post']); ?>
                                                                <div class="input-group">
                                                                    <input placeholder="кол." type="number"
                                                                           name="quantity"
                                                                           class="form-control">
                                                                    <input type="hidden" name="product_id"
                                                                           value="<?php echo $part->product['id']; ?>">
                                                                    <input type="hidden" name="supplier_id"
                                                                           value="<?php echo $price['supplier_id']; ?>">
                                                                    <input type="hidden" name="term"
                                                                           value="<?php echo $price['term']; ?>">
                                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="submit"><i
                                                                    class="fa fa-shopping-cart"></i></button>
                                                    </span>
                                                                </div>
                                                                </form>
                                                                <small>
                                                                    <a href="/cart" class="<?php echo $key; ?>"
                                                                        <?php if (!key_exists(md5($key), $this->cart->contents())) { ?>
                                                                            style="display: none;"
                                                                        <?php } ?>
                                                                    ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                                    </a>
                                                                </small>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($part->Info){?>
                                            <i class="glyphicon glyphicon-info-sign hidden-lg hidden-md" ></i>
                                            <div class="info">
                                                <?php echo $part->Info;?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <?php if(@$this->options['show_tecdoc_product_without_price']){?>
                            <?php foreach ($parts as $part){?>
                                <?php if (!$part->product) { ?>
                                    <div class="col-md-4 col-sm-6 filters-item">
                                        <div class="filters-key" style="display: none">
                                            <?php if (isset($part->filter_key)){ ?>
                                                <?php foreach ($part->filter_key as $filter_key) {echo $filter_key . ' ';} ?>
                                            <?php } ?>
                                        </div>
                                        <div class="single-shop-product">
                                            <div class="product-upper">
                                                <a href="/search?search=<?php echo $part->Search; ?>&ID_art=<?php echo $part->ID_art; ?>&brand=<?php echo $part->Brand; ?>">
                                                    <img onerror="this.src='/assets/themes/default/img/no_image.png'"
                                                         src="<?php echo $part->Image; ?>"
                                                         alt="<?php echo $part->Name . ' ' . $part->Brand . ' купить'; ?>"
                                                         title="<?php echo $part->Name . ' ' . $part->Brand . ' купить'; ?>"
                                                         style="max-height: 150px;">
                                                </a>
                                            </div>
                                            <small><?php echo $part->Brand.' '.$part->Search;?></small>
                                            <h2>
                                                <a href="/search?search=<?php echo $part->Search; ?>&ID_art=<?php echo $part->ID_art; ?>&brand=<?php echo $part->Brand; ?>"><?php echo $part->Name;?></a>
                                            </h2>
                                            <div class="product-carousel-price">
                                                <b>
                                                    Нет предложений
                                                </b>
                                            </div>
                                            <div class="product-option-shop">
                                                <a class="btn btn-default" href="/search?search=<?php echo $part->Search; ?>&ID_art=<?php echo $part->ID_art; ?>&brand=<?php echo $part->Brand; ?>"><?php echo lang('text_cross'); ?></a>
                                            </div>
                                            <?php if($part->Info){?>
                                                <i class="glyphicon glyphicon-info-sign hidden-lg hidden-md" ></i>
                                                <div class="info">
                                                    <?php echo $part->Info;?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <h3>В данной категории нет запчастей</h3>
                        Оставьте свой запрос и мы обязательно найдем нужную Вам запчасть.
                        <?php echo form_open('ajax/vin', ['class' => 'vin_request']); ?>

                        <div class="well">
                            <div class="alert alert-danger" role="alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            </div>
                            <div class="alert alert-success" role="alert" style="display: none;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            </div>

                            <div class="form-group">
                                <label><?php echo lang('text_vin_manufacturer'); ?></label>
                                <input type="text" class="form-control" name="manufacturer" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_vin_model'); ?></label>
                                <input type="text" class="form-control" name="model" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_vin_engine'); ?></label>
                                <input type="text" class="form-control" name="engine" required>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_vin_vin'); ?></label>
                                <input type="text" class="form-control" name="vin">
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('text_vin_parts'); ?></label>
                                <textarea class="form-control" name="parts" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_vin_name'); ?></label>
                            <input type="text" name="name" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_vin_telephone'); ?></label>
                            <input type="text" name="telephone" class="form-control" required/>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('text_vin_email'); ?></label>
                            <input type="email" name="email" class="form-control" required/>
                        </div>

                        <div class="form-group pull-right">
                            <button type="submit"><?php echo lang('button_send'); ?></button>
                        </div>
                        </form>
                    <?php } ?>

                <?php } else { ?>
                    <?php if ($popular_category) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h3><?php echo lang('text_quick_navigation'); ?></h3>
                            </div>
                            <?php foreach ($popular_category as $tree) { ?>
                                <a href="<?php echo current_url(); ?>?id_tree=<?php echo $tree['ID_tree']; ?>">
                                    <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail category-item">
                                            <img src="/image?img=<?php echo $tree['image']; ?>&height=100"
                                                 alt="<?php echo $tree['name']; ?>">
                                            <div class="caption">
                                                <p><?php echo $tree['name']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="jumbotron">
                        <h3><?php echo $name; ?></h3>
                        <table class="table">
                            <tr>
                                <td><?php echo lang('text_CCM'); ?></td>
                                <td><?php echo $info->CCM; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('text_KwHp'); ?></td>
                                <td><?php echo $info->KwHp; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('text_Engines'); ?></td>
                                <td><?php echo $info->Engines; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo lang('text_Body'); ?></td>
                                <td><?php echo $info->Body; ?></td>
                            </tr>
                        </table>
                        <?php echo $info->Description; ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-4">
                <?php if ($filters) { ?>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                       aria-expanded="true" aria-controls="collapseOne">
                                        <?php echo lang('text_filter'); ?> <i
                                                class="glyphicon glyphicon-list pull-right"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <?php foreach ($filters as $filter_name => $filter) { ?>
                                        <b><?php echo $filter_name; ?></b>
                                        <div class="ft">
                                            <ul>
                                                <?php foreach ($filter as $key => $value) { ?>
                                                    <li><input class="filters" type="checkbox"
                                                               value="<?php echo $key; ?>"><?php echo $value; ?>
                                                    </li>
                                                <?php } ?>

                                            </ul>
                                        </div>
                                    <?php } ?>
                                    <a href="#" rel="nofollow" onclick="location.reload()"
                                       class="btn btn-info pull-right"><?php echo lang('text_resset_filter'); ?></a>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($trees) { ?>
                    <h3><?php echo lang('text_catalog_tree'); ?></h3>
                    <ul class="list-unstyled trees">
                        <?php foreach ($trees as $tree) { ?>
                            <?php if ($tree['Level'] == 1) { ?>
                                <li id="<?php echo $tree['ID_tree']; ?>">
                                    <?php if ($tree['Childs'] > 0) { ?>
                                        <i class="fa fa-plus-square-o"></i> <a href="#"
                                                                               onclick="show_tree('<?php echo $tree['ID_tree']; ?>', event)"><?php echo ucfirst($tree['Name']); ?></a>
                                    <?php } else { ?>
                                        <i class="fa fa-circle-o"></i> <a
                                                href="<?php echo current_url(); ?>?id_tree=<?php echo $tree['ID_tree']; ?>"><?php echo ucfirst($tree['Name']); ?></a>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.filters').click(function () {
            var countChecked = 0;
            $(".filters-item").hide();
            $(".filters").each(function () {
                if ($(this).prop('checked')) {
                    countChecked++;
                    $(".filters-key:contains('"+$(this).val()+"')").parent('.filters-item').show();
                }
            });
            if (countChecked == 0) {
                $(".filters-item").show();
            }
        });

        $(".single-shop-product").mouseover(function(){
            $(this).children('.info').css("height", "auto");
        });
        $(".single-shop-product").mouseout(function(){
            $(this).children('.info').css("height", "1px");
        });
    });

    function show_tree(ID_tree, event) {
        event.preventDefault();
        var trees = [];
        <?php foreach ($trees as $tree){?>
        var p = {
            'ID_tree': "<?php echo $tree['ID_tree'];?>",
            'ID_parent': "<?php echo $tree['ID_parent'];?>",
            'Name': "<?php echo ucfirst($tree['Name']);?>",
            'Level': "<?php echo $tree['Level'];?>",
            'Path': <?php echo $tree['Path'];?>,
            'Childs': "<?php echo $tree['Childs'];?>"
        };
        trees.push(p);
        <?php } ?>
        var html = '<ul class="sub" id="' + ID_tree + '">';
        $.each(trees, function (index, tree) {
            if (tree.ID_parent == ID_tree) {
                if (tree.Childs > 0) {
                    html += '<li id="' + tree.ID_tree + '"><i class="fa fa-plus-square-o"></i> <a href="#" onclick="show_tree(\'' + tree.ID_tree + '\', event)">' + tree.Name + '</a></li>';
                } else {
                    html += '<li id="' + tree.ID_tree + '"><i class="fa fa-circle-o"></i> <a href="<?php echo current_url();?>?id_tree=' + tree.ID_tree + '">' + tree.Name + '</a></li>';
                }
            }
        });
        html += '</ul>';
        if ($("#" + ID_tree + " > ul").length > 0) {
            $("#" + ID_tree + " > ul").remove();
        }
        $("#" + ID_tree).append(html);
    }
</script>