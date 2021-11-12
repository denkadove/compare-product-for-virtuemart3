<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_vmcompare
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'/components/com_vmcompare/assets/css/main.css');
$doc->addScript('/components/com_vmcompare/assets/js/main.js');
$doc->addScript('/components/com_virtuemart/assets/js/vmprices.js?vmver=94bd510e');
$doc->addScript('/components/com_virtuemart/assets/js/fancybox/jquery.fancybox-1.3.4.pack.js?vmver=94bd510e');

$productModel = VmModel::getModel('Product');

?>
<div class="container">
    <h1><?php echo $this->msg; ?></h1>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <?php foreach ($this->compareList as $key => $value) { ?>
                <a class="nav-item nav-link" id="nav-cat-<?php echo($value["catId"]); ?>-tab" 
                data-toggle="tab" href="#nav-cat-<?php echo($value["catId"]); ?>" 
                role="tab" aria-controls="nav-cat-<?php echo($value["catId"]); ?>" 
                aria-selected="true"><?php echo($value["catName"]); ?> ( <?php echo(count($value["products"])); ?> ) </a>
            <?php }?>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        

        <?php foreach ($this->compareList as $value) { ?>
            <div class="tab-pane fade" id="nav-cat-<?php echo($value["catId"]); ?>" role="tabpanel" aria-labelledby="nav-cat-<?php echo($value["catId"]); ?>-tab">
                <div class="col-12 compare_row">
                    <div class="slider slider-1">
                        <div class="slider__wrapper">
                            <div class="slider__items">
                                <?php foreach ($value["products"] as $product_item) { ?>
                                <div class="col-3 compare_element compare_item_<?php echo($product_item['id']); ?> slider__item">
                                    <a href='/' onclick="removeProductFromComparePage(<?php echo($product_item['id']); ?>); return false;" title="Удалить товар из сравнения"><i class="fa fa-times delete_from_cmpare" aria-hidden="true"></i></a>
                                <?php echo JHTML::link(JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product_item['id'].'&virtuemart_category_id='.$product->virtuemart_category_id),'<img src="'.$product_item['product_img'].'">', array('title' => $product_item['product_name'])); ?>
                                </div>
                                <?php } ?>                            
                            </div>
                        </div>
                        <a class="slider__control slider__control_prev" href="#" role="button"></a>
                        <a class="slider__control slider__control_next slider__control_show" href="#" role="button"></a>
                    </div>                       
                </div>

                <div class="col-12 compare_row">
                    <div class="slider slider-1">
                        <div class="slider__wrapper">
                            <div class="slider__items">
                            <?php foreach ($value["products"] as $product_item) { ?>
                                <div class="col-3 compare_element compare_product_name compare_item_<?php echo($product_item['id']); ?> slider__item">
                                    <h6><?php echo JHTML::link(JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product_item['id'].'&virtuemart_category_id='.$product->virtuemart_category_id), $product_item['product_name'], array('title' => $product_item['product_name'])); ?> </h6> 
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>                       
                </div>

                <div class="col-12 compare_row">
                    <div class="slider slider-1">
                        <div class="slider__wrapper">
                            <div class="slider__items">
                                <?php foreach ($value["products"] as $product_item) { ?> 
                                <div class="col-3 compare_element compare_product_price compare_item_<?php echo($product_item['id']); ?> slider__item">
                                <?php if ($product_item['product_salesPrice'] < $product_item['product_price']) {?>
                                    <p>Цена: <?php echo($product_item['product_price']) ?> руб </p>
                                    <p>Цена со скидкой: <?php echo($product_item['product_salesPrice']) ?> руб </p>                         
                                <?php }else{ ?>
                                    <p>Цена: <?php echo($product_item['product_price']) ?> руб </p>                                    
                                <?php }?> 
                                </div>
                                <?php } ?>    
                            </div>
                        </div>
                    </div>                       
                </div>

                <div class="col-12 compare_row">
                    <div class="slider slider-1">
                        <div class="slider__wrapper">
                            <div class="slider__items">
                                <?php foreach ($value["products"] as $product_item) { ?>
                                    <div class="col-3 compare_element compare_product_buy_button compare_item_<?php echo($product_item['id']); ?> slider__item">
                                        <a href="https://gazkit.loc/index.php?option=com_virtuemart&view=cart&task=add&virtuemart_product_id[]=<?php echo $product_item['id']; ?>&quantity=1" class="btn btn-primary">Купить</a>
                                    <?php 
                                        // $productToCart = $productModel->getProduct($product_item['id']);
                                        // echo shopFunctionsF::renderVmSubLayout('addtocart',array('product'=>$productToCart));                                    
                                    ?>                                    
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>                       
                </div>

                <?php foreach ($value["customfields_list"] as $cf) { ?>
                    <div class=" row compare_cf_title"> <?php echo (jtext::_($cf['title'])); ?></div> 
                    <div class="col-12 compare_row">
                    <div class="slider slider-1">
                        <div class="slider__wrapper">
                            <div class="slider__items">
                                <?php foreach ($value["products"] as $product) { 
                                    if (!empty($product["customfields"][$cf['id']]["customfield_value"])){ ?>
                                    <div class="col-3 compare_element compare_item_<?php echo($product['id']); ?> slider__item"><?php echo ($product["customfields"][$cf['id']]["customfield_value"]); ?></div>
                                <?php } else { ?>
                                    <div class="col-3 compare_element compare_item_<?php echo($product['id']); ?> slider__item">-</div>
                                <?php }} ?>
                            </div>
                        </div>
                    </div>                       
                </div>
                <?php } ?>
                
            </div>
        <?php }?>
    </div>
</div>

<script>
    function sliderInit(sliderClass){
        var sliderElements = document.querySelectorAll('.' + sliderClass);
        var sliders = [];
        for (var i = 0, length = sliderElements.length; i < length; i++) {
            sliderElements[i].id = sliderClass + '-id-' + i;
            sliders.push(sliderElements[i]);
            sliders[i] = slideShow('#' + sliderClass + '-id-' + i, {
            isAutoplay: false
            });
        }
        document.addEventListener('click', function (e) {
            if (e.target.closest('.slider')) {
            if (!e.target.closest('.slider').classList.contains(sliderClass)) {
                return;
            }
            }
            if (e.target.classList.contains('slider__control_next')) {
            e.preventDefault();
            for (var i = 0, length = sliders.length; i < length; i++) {
                sliders[i].next();
            }
            } else if (e.target.classList.contains('slider__control_prev')) {
            e.preventDefault();
            for (var i = 0, length = sliders.length; i < length; i++) {
                sliders[i].left();
            }
            } else if (e.target.getAttribute('data-slide-to')) {
            e.preventDefault();
            if (e.target.closest('.slider').id === sliderClass + '-id-0') {
                for (var i = 0, length = sliders.length; i < length; i++) {
                sliders[i].moveTo(parseInt(e.target.getAttribute('data-slide-to')));
                }
            }
            }
        });
    }
    var slidersList = document.querySelectorAll('slider');
    
    sliderInit('slider-1');

    function fullWidth() {
      spComponent = document.getElementById('sp-component');
      spComponent.className = '';
      
    }

    fullWidth();
</script>
