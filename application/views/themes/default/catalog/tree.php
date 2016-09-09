<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="product-big-title-area">
    <div class="container">
        <div class="row-fluid">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h1><?php echo $h1;?></h1>
                    <?php if(isset($this->garage[md5($name)]) && $this->input->get('id_tree') && !isset($this->garage[md5($name)]['category'][$this->input->get('id_tree')])){?>
                        <a rel="nofollow" class="btn btn-info" href="<?php echo current_url();?>?add_tree=1">Добавить категорию в гараж</a>
                    <?php } ?>
                    <?php if(!isset($this->garage[md5($name)])){?>
                        <a rel="nofollow" class="btn btn-info" href="<?php echo current_url();?>?add_garage=1" role="button">Добавить автомобиль в гараж</a>
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
                    <li><a href="<?php echo $b['href']; ?>"><?php echo $b['title']; ?></a></li>
                <?php } ?>
            </ol>
            <div class="col-md-4">
                <?php if (isset($trees) && !empty($trees)) { ?>
                    <h3>Каталог запчастей</h3>
                    <ul class="list-unstyled trees">
                        <?php foreach ($trees as $tree) { ?>
                            <?php if ($tree->Level == 1) { ?>
                                <li id="<?php echo $tree->ID_tree; ?>">
                                    <?php if ($tree->Childs > 0) { ?>
                                        <i class="fa fa-plus-square-o"></i> <a href="#"
                                                                               onclick="show_tree('<?php echo $tree->ID_tree; ?>', event)"><?php echo $tree->Name; ?></a>
                                    <?php } else { ?>
                                        <i class="fa fa-circle-o"></i> <a
                                            href="<?php echo current_url(); ?>?id_tree=<?php echo $tree->ID_tree; ?>"><?php echo $tree->Name; ?></a>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
            <div class="col-md-8">
                <?php if (isset($parts)) { ?>
                    <table class="table table-responsive">
                        <tr>
                            <th>Изображение</th>
                            <th>Артикул</th>
                            <th>Производитель</th>
                            <th>Наименование</th>
                            <th>Доставка</th>
                            <th>Цена</th>
                            <th></th>
                        </tr>
                        <?php foreach ($parts as $part) { ?>
                            <?php if ($part->available['products'] || $part->available['cross']) { ?>
                                <?php if ($part->available['products']) { ?>
                                    <?php foreach ($part->available['products'] as $product) { ?>
                                        <tr>
                                            <td>
                                                <img onerror="imgError(this,50);" src="<?php echo $part->Preview; ?>"
                                                     title="<?php echo $part->Brand; ?>" >
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                   href="/product/<?php echo $product['slug']; ?>"><?php echo $product['sku']; ?></a>
                                            </td>
                                            <td>
                                                <?php echo $product['brand']; ?>
                                            </td>
                                            <td><?php echo $product['name']; ?></td>
                                            <td><?php echo format_term($product['term']);?></td>
                                            <td style="width: 150px;">
                                                <?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice']:$product['price']); ?>
                                            </td>
                                            <td>
                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\'' . md5($product['slug']) . '\', event)']); ?>
                                                <div class="input-group" style="width: 100px;">
                                                    <input type="number" name="quantity" class="form-control" value="1">
                                                    <input type="hidden" name="slug"
                                                           value="<?php echo $product['slug']; ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="submit"><i
                                                            class="fa fa-shopping-cart"></i></button>
                                                </span>
                                                </div>
                                                </form>
                                                <small><a href="/cart" id="<?php echo md5($product['slug']); ?>"
                                                        <?php if (!key_exists(md5($product['slug']), $this->cart->contents())) { ?>
                                                            style="display: none;"
                                                        <?php } ?>
                                                    ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                    </a></small>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($part->available['cross']) { ?>
                                    <?php foreach ($part->available['cross'] as $product) { ?>
                                        <tr>
                                            <td>
                                                <img style="width: 50px;" onerror="imgError(this, 50);" src="/image?img=<?php echo $part->Preview; ?>&width=50"
                                                     title="<?php echo $part->Brand; ?>" >
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                   href="/product/<?php echo $product['slug']; ?>"><?php echo $product['sku']; ?></a>
                                            </td>
                                            <td>
                                                <?php echo $product['brand']; ?>
                                            </td>
                                            <td><?php echo format_term($product['term']);?></td>
                                            <td style="width: 150px;">
                                                <?php echo format_currency($product['saleprice'] > 0 ? $product['saleprice']:$product['price']); ?>
                                            </td>

                                            <td>
                                                <?php echo form_open('/ajax/add_cart', ['onsubmit' => 'add_cart($(this).serialize(),\'' . md5($product['slug']) . '\')']); ?>
                                                <div class="input-group" style="width: 100px;">
                                                    <input type="number" name="quantity" class="form-control" value="1">
                                                    <input type="hidden" name="slug"
                                                           value="<?php echo $product['slug']; ?>">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="submit"><i
                                                            class="fa fa-shopping-cart"></i></button>
                                                </span>
                                                </div>
                                                </form>
                                                <small><a href="/cart" id="<?php echo md5($product['slug']); ?>"
                                                        <?php if (!key_exists(md5($product['slug']), $this->cart->contents())) { ?>
                                                            style="display: none;"
                                                        <?php } ?>
                                                    ><i class="fa fa-shopping-cart"></i> <?php echo lang('text_in_cart'); ?>
                                                    </a></small>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                        <?php foreach ($parts as $part) { ?>
                            <?php if (!$part->available['products'] && !$part->available['cross']) { ?>
                                <tr>
                                    <td>
                                        <img onerror="imgError(this, 50);" src="/image?img=<?php echo $part->Preview; ?>&width=50"
                                             title="<?php echo $part->Brand; ?>" >
                                    </td>
                                    <td>
                                        <?php echo $part->Article; ?>
                                    </td>
                                    <td>
                                        <?php echo $part->Brand; ?>
                                    </td>
                                    <td><?php echo $part->Name; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <a href="#" onclick="catalog_search('<?php echo $part->ID_art;?>', '<?php echo $part->Search;?>','<?php echo $part->Brand;?>', event)"><?php echo lang('text_cross'); ?></a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </table>
                <?php }else{?>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Экспресс навигация</h3>
                        </div>
                        <?php foreach ($trees as $tree){?>
                            <?php if(isset($popular_category[$tree->ID_tree])){?>
                                <a href="<?php echo current_url();?>?id_tree=<?php echo $tree->ID_tree;?>">
                                    <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail category-item">
                                            <img src="<?php echo $popular_category[$tree->ID_tree]['image'];?>" alt="<?php echo $popular_category[$tree->ID_tree]['name'];?>">
                                            <div class="caption">
                                                <p><?php echo $popular_category[$tree->ID_tree]['name'] ? $popular_category[$tree->ID_tree]['name'] : $tree->Name;?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                            <?php } ?>
                        <?php } ?>

                    </div>
                    <div class="jumbotron">
                        <h3><?php echo $name;?></h3>
                        <table class="table">
                            <tr>
                                <td>Объем</td>
                                <td><?php echo $info->CCM;?></td>
                            </tr>
                            <tr>
                                <td>Мощность Кв/Лс</td>
                                <td><?php echo $info->KwHp;?></td>
                            </tr>
                            <tr>
                                <td>Двигатель</td>
                                <td><?php echo $info->Engines;?></td>
                            </tr>
                            <tr>
                                <td>Кузов</td>
                                <td><?php echo $info->Body;?></td>
                            </tr>
                        </table>
                        <?php echo $info->Description;?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <hr/>
            <?php echo $text; ?>
        </div>
    </div>
</div>
<script>
    function show_tree(ID_tree, event) {
        event.preventDefault();
        var trees = [];
        <?php foreach ($trees as $tree){?>
        var p = {
            'ID_tree': "<?php echo $tree->ID_tree;?>",
            'ID_parent': "<?php echo $tree->ID_parent;?>",
            'Name': "<?php echo $tree->Name;?>",
            'Level': "<?php echo $tree->Level;?>",
            'Path': <?php echo $tree->Path;?>,
            'Childs': "<?php echo $tree->Childs;?>"
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