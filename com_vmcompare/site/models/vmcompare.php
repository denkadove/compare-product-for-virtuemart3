<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


/**
 * HelloWorld Model
 *
 * @since  0.0.1
 */
class VmcompareModelVmcompare extends JModelItem
{
	/**
	 * @var string message
	 */
    protected $message;
	protected $compareUrl;	
	
	//protected $compareUrl;

    /**
	 * Method to get a table object, load it if necessary.
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 * @return  JTable  A JTable object
	 * @since   1.6
	 */
	public function getTable($type = 'Vmcompare', $prefix = 'VmcompareTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	/**
	 * Get the message
	 * @return  string  The message to be displayed to the user
	 */
	public function getMsg($id = 1)
	{
		if (!is_array($this->messages))
		{
			$this->messages = array();
		}

		if (!isset($this->messages[$id]))
		{
			// Request the selected id
			$jinput = JFactory::getApplication()->input;
			$id     = $jinput->get('id', 1, 'INT');

			// Get a TableVMcompare instance
			$table = $this->getTable();

			// Load the message
			$table->load($id);

			// Assign the message
			//$this->messages[$id] = $table->greeting;
			$this->messages[$id] = 'Сравнение товаров';
		}

		return $this->messages[$id];
	}
	
	/*
	* Get url string and chek it
	*/
    public function getCompareUrl(){
		$this->compareIdList = new ArrayObject();
		$this->compareUrl = explode(',', trim($_GET['products']));
		foreach ($this->compareUrl as $value) {
			if (is_numeric($value)){
				$this->compareIdList->append($value);
			} 
		}
		return $this->compareIdList;		
	}

	/*
	* Return list for compare
	*/
	public function getProductCompareList(){
		$productModel = VmModel::getModel('Product');
		
		$this->compareArray = new ArrayObject();
		$this->compareList = $this->getCompareUrl();
		$idx = 0;
		$cf_idx = 0;		
		foreach ($this->compareList as $key => $cid) {			
			$product = $productModel->getProduct($cid);	
			// var_dump($product);
			$productModel->addImages($product,1);
			$category = $productModel->getProductCategories($cid);
			$categoryId = $category[0]['virtuemart_category_id'];
			$categoryName = $category[0]['category_name'];
			$customfields = $product->customfields;
			$this->compareArray[$categoryId]['catId'] = $categoryId;
			$this->compareArray[$categoryId]['catName'] = $categoryName;			

			foreach ($customfields as $cf) {
				if (!in_array($cf->virtuemart_custom_id, $this->compareArray[$categoryId]['customfields_list']) && $cf->customfield_value!='1359') {
					$this->compareArray[$categoryId]['customfields_list'][$cf->virtuemart_custom_id]['id'] = $cf->virtuemart_custom_id;
					$this->compareArray[$categoryId]['customfields_list'][$cf->virtuemart_custom_id]['title'] = $cf->custom_title;
				}

				$this->compareArray[$categoryId]['products'][$idx]['customfields'][$cf->virtuemart_custom_id]['virtuemart_custom_id'] = $cf->virtuemart_custom_id;
				$this->compareArray[$categoryId]['products'][$idx]['customfields'][$cf->virtuemart_custom_id]['custom_title'] = $cf->custom_title;
				$this->compareArray[$categoryId]['products'][$idx]['customfields'][$cf->virtuemart_custom_id]['customfield_value'] = $cf->customfield_value;
				$this->compareArray[$categoryId]['products'][$idx]['customfields'][$cf->virtuemart_custom_id]['is_hidden'] = $cf->is_hidden;
				$cf_idx ++;
			}

			$this->compareArray[$categoryId]['products'][$idx]['id'] = $cid;
			$this->compareArray[$categoryId]['products'][$idx]['product_name'] = $product->product_name;
			$this->compareArray[$categoryId]['products'][$idx]['product_price'] = $product->prices['product_price'];
			$this->compareArray[$categoryId]['products'][$idx]['product_salesPrice'] = $product->prices['salesPrice'];
			$this->compareArray[$categoryId]['products'][$idx]['product_img'] = JURI::root().$product->file_url;
			$this->compareArray[$categoryId]['products'][$idx]['close_url'] = 'url';

			$this->compareArray[$categoryId]['products_list_id'][$key] = $cid;

			$idx++;
		}
		
		
		return $this->compareArray;
	}
}