<?php
defined('_JEXEC') or  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
if (!class_exists( 'VmConfig' )) require(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');

VmConfig::loadConfig();
VmConfig::loadJLang('mod_vm_product_compare', true);
VmConfig::loadJLang('com_virtuemart', true);
vmJsApi::jQuery();
vmJsApi::cssSite();
require(JModuleHelper::getLayoutPath('mod_vm_product_compare'));

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'/modules/mod_vm_product_compare/assets/css/style.css');

echo vmJsApi::writeJS();

//$cart = VirtueMartCart::getCart(false);

$doc = JFactory::getDocument();
$doc->addStyleDeclaration( $positionModule );
$doc->addScript(JURI::base().'/modules/mod_vm_product_compare/assets/js/main.js');

if (isset($_GET['clear'])) { return clearCompareList(); } 
if (isset($_GET['addtocompare'])) { return addToCompare(); }  

?>