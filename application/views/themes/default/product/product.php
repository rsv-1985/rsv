<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php echo $this->load->view('form/category', null, true);?>
                <?php if ($banner) { ?>
                    <div class="single-sidebar">
                        <?php foreach ($banner as $banner) { ?>
                            <div class="thubmnail-recent">
                                <img onerror="imgError(this);" src="/uploads/banner/<?php echo $banner['image']; ?>">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <div class="col-md-9">
                <div class="product-content-right">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="product-images">
                                <div class="product-main-img">
                                    <img onerror="imgError(this, 300);" src="/image?img=<?php echo $image; ?>&width=300" alt="<?php echo $h1; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="product-inner">
                                <h1 class="product-name"><?php echo $h1; ?></h1>
                                <div class="well well-sm">
                                    <div class="col-md-6">
                                        <small><?php echo lang('text_brand'); ?>:</small> <?php echo $brand; ?><br/>
                                        <small><?php echo lang('text_sku'); ?>:</small> <?php echo $sku; ?><br/>
                                        <?php if($excerpt){?>
                                            <small><?php echo lang('text_excerpt'); ?>:</small> <?php echo $excerpt; ?>
                                        <?php } ?>

                                    </div>
                                    <div class="col-md-6">
                                        <small><?php echo lang('text_qty'); ?>:</small> <?php echo format_quantity($quantity); ?><br/>
                                        <small><?php echo lang('text_term'); ?>:</small> <?php echo format_term($term); ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <?php if($this->is_admin){?>
                                    <div class="well well-sm">
                                        <div class="col-md-6">
                                            <small><?php echo lang('text_supplier'); ?>:</small> <?php echo $supplier; ?><br/>
                                            <small><?php echo lang('text_supplier_description'); ?>:</small> <?php echo $supplier_description; ?><br/>
                                        </div>
                                        <div class="col-md-6">
                                            <small><?php echo lang('text_qty'); ?>:</small> <?php echo $quantity; ?><br/>
                                            <small><?php echo lang('text_delivery_price'); ?>:</small> <?php echo $delivery_price; ?><br/>
                                            <small><?php echo lang('text_updated_at');?></small> <?php echo $updated_at;?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php } ?>
                                <div class="product-inner-price">
                                    <ins><?php echo $saleprice > 0 ? $saleprice : $price; ?></ins>
                                    <?php if ($saleprice > 0) { ?>
                                        <del><?php echo $price; ?></del>
                                    <?php } ?>
                                </div>

                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\'' . md5($slug) . '\', event)']); ?>
                                <div class="quantity">
                                    <input type="number" size="4" class="input-text qty text" value="1" name="quantity"
                                           min="1" step="1">
                                </div>
                                <input type="hidden" name="slug" value="<?php echo $slug; ?>">
                                <button class="add_to_cart_button"
                                        type="submit"><?php echo lang('button_add_cart'); ?></button>
                                <a href="/cart" id="<?php echo md5($slug); ?>"
                                    <?php if (!key_exists(md5($slug), $this->cart->contents())) { ?>
                                        style="display: none; margin-left: 2%;"
                                    <?php } ?>
                                ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?></a>
                                </form>

                                <div role="tabpanel">
                                    <ul class="product-tab" role="tablist">
                                        <li role="presentation" class="active"><a href="#description"
                                                                                  aria-controls="home" role="tab"
                                                                                  data-toggle="tab"><?php echo lang('text_description'); ?></a>
                                        </li>
                                        <?php if ($applicability) { ?>
                                            <li role="presentation"><a href="#applicability" aria-controls="profile"
                                                                       role="tab"
                                                                       data-toggle="tab"><?php echo lang('text_applicability'); ?></a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($components) { ?>
                                            <li role="presentation"><a href="#components" aria-controls="profile"
                                                                       role="tab"
                                                                       data-toggle="tab"><?php echo lang('text_components'); ?></a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade in active" id="description">
                                            <p><?php echo $description; ?></p>
                                        </div>
                                        <?php if ($applicability) { ?>
                                            <div role="tabpanel" class="tab-pane fade" id="applicability">

                                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                                    <?php $q = 0; foreach ($applicability as $brand_name => $ap){?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="heading<?php echo $q;?>">
                                                                <h4 class="panel-title">
                                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $q;?>" aria-expanded="false" aria-controls="collapse<?php echo $q;?>">
                                                                        <?php echo $brand_name;?>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapse<?php echo $q;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $q;?>">
                                                                <div class="panel-body">
                                                                    <table class="table table-condensed">
                                                                        <thead>
                                                                        <tr>
                                                                            <th>Производитель</th>
                                                                            <th>Моель</th>
                                                                            <th>Описание</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php foreach ($ap as $applicability) { ?>
                                                                            <tr>
                                                                                <td><?php echo $applicability->Brand; ?></td>
                                                                                <td>
                                                                                    <?php echo $applicability->Model; ?><br>
                                                                                    <?php echo $applicability->Name; ?> <?php echo $applicability->Fuel; ?><br>
                                                                                </td>
                                                                                <td><?php echo str_replace(';', '<br>', $applicability->Description); ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php $q++;} ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if ($components) { ?>
                                            <div role="tabpanel" class="tab-pane fade" id="components">
                                                <table class="table table-condensed">
                                                    <thead>
                                                    <tr>
                                                        <th>Brand</th>
                                                        <th>Article</th>
                                                        <th>Name</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($components as $components) { ?>
                                                        <tr>
                                                            <td><?php echo $components->Brand; ?></td>
                                                            <td><?php echo $components->Display; ?></td>
                                                            <td><?php echo $components->Name; ?></td>
                                                            <td>
                                                                <a href="#"
                                                                   onclick="catalog_search('<?php echo $components->ID_art; ?>', '<?php echo $components->Display; ?>','<?php echo $components->Brand; ?>', event)"><?php echo lang('button_search'); ?></a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>