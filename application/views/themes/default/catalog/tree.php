<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?php echo $h1; ?></h1>
            <ol class="breadcrumb">
                <?php foreach ($breadcrumb as $b) { ?>
                    <li><a href="<?php echo $b['href']; ?>"><?php echo $b['title']; ?></a></li>
                <?php } ?>
            </ol>
            <div class="col-md-4">
                <?php if (isset($trees) && !empty($trees)) { ?>
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
                    <table class="table">
                        <?php foreach ($parts as $part) { ?>
                            <?php if ($part->available['products'] || $part->available['cross']) { ?>
                                <?php if ($part->available['products']) { ?>
                                    <?php foreach ($part->available['products'] as $product) { ?>
                                        <tr>
                                            <td width="50">
                                                <img src="/image?img=<?php echo $part->Preview; ?>&width=50"
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
                                <?php if ($part->available['cross']) { ?>
                                    <?php foreach ($part->available['cross'] as $product) { ?>
                                        <tr>
                                            <td width="50">
                                                <img src="/image?img=<?php echo $part->Preview; ?>&width=50"
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
                                        <img src="/image?img=<?php echo $part->Preview; ?>&width=50"
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
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    function show_tree(ID_tree, event) {
        event.preventDefault();
        var trees = [];
        <?php foreach ($trees as $tree){?>
        var p = {
            'ID_tree': '<?php echo $tree->ID_tree;?>',
            'ID_parent': '<?php echo $tree->ID_parent;?>',
            'Name': '<?php echo $tree->Name;?>',
            'Level': '<?php echo $tree->Level;?>',
            'Path': '<?php echo $tree->Path;?>',
            'Childs': '<?php echo $tree->Childs;?>'
        };
        trees.push(p);
        <?php } ?>
        var html = '<ul class="sub" id="' + ID_tree + '">';
        $.each(trees, function (index, tree) {
            if (tree.ID_parent == ID_tree) {
                if (tree.Childs > 0) {
                    html += '<li id="' + tree.ID_tree + '"><i class="fa fa-plus-square-o"></i> <a href="#" onclick="show_tree(\'' + tree.ID_tree + '\')">' + tree.Name + '</a></li>';
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