<?php
/**
 * Intercastilla Hideprices Extension
 *
 * NOTICE OF LICENSE
 *
 * Copyright (c) 2014 Intercastilla Diseño y Comunicación Gráfica, S.L.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included i
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS O
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL TH
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHE
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category    Intercastilla
 * @package     Intercastilla_Hideprices
 * @author      Borja A.
 * @copyright   Copyright (c) 2014 Intercastilla Diseño y Comunicación Gráfica, S.L. (http://www.intercastilla.com)
 * @license     http://opensource.org/licenses/MIT  MIT License
 */

class Intercastilla_Hideprices_Model_Observer_Product extends Varien_Object
{

	/**
	 * Boolean flags for salable/not salable
	 */
	const HIDEPRICES_PRODUCT_MARK_NOT_SALABLE = false;
	const HIDEPRICES_PRODUCT_MARK_SALABLE = true;

	/**
	 * Boolean flags for show/not show price
	 */
	const HIDEPRICES_PRODUCT_MARK_NO_SHOW_PRICE = false;
	const HIDEPRICES_PRODUCT_MARK_SHOW_PRICE = true;


	/**
	 * Logic to determine if the product should NOT be available
	 * (Don't show price and don't allow add to cart)
	 *
	 * @return bool
	 */
	protected function _matches()
	{
		$_matches = !Mage::helper('hideprices/client')->isAllowedToSeePrices();

		Mage::dispatchEvent('intercastilla_hideprices_client_matches', array(
			'matches' => $_matches
		));

		return $_matches;
	}

	/**
	 * Sets each item with the neccesary condition to not show the price
	 *
	 * @param Mage_Catalog_Model_Product    $item
	 * @param bool                          True for show price, false for not
	 *
	 */
	protected function _markItemShowPrice($item, $showPrice = self::HIDEPRICES_PRODUCT_MARK_NO_SHOW_PRICE)
	{
		$item->setCanShowPrice($showPrice);
	}

	/**
	 * Sets the item as salable/not salable
	 *
	 * @param Mage_Catalog_Model_Product    $item
	 * @param bool                          True for salable, false for not salable
	 */
	protected function _markItemSalable($item, $isSalable = self::HIDEPRICES_PRODUCT_MARK_NOT_SALABLE)
	{
		$item->setIsSalable($isSalable);
		// additional custom flag, useful for template stuff
		// this is because seting a product as not salable, will
		// set the product as 'out of stock'
		$item->setIsNotAvailableForUser($isSalable);
	}

	/**
	 * Marks an item BOTH not salable and NOT allowed to see price for it
	 *
	 * @param Mage_Catalog_Model_Product $item
	 */
	protected function _setItemNotAvailable($item)
	{
		if ($item->getId()) {
			$this->_markItemShowPrice($item);
			$this->_markItemSalable($item);
		}
	}

	/**
	 * Hooks on product init, and sets canShowPrice = false
	 * if the condition matches
	 *
	 * @param Varien_Event_Observer $observer
	 *
	 */
	public function catalogControllerProductInit(Varien_Event_Observer $observer)
	{
		if (Mage::helper('hideprices')->isEnabled()) {
			if (($product = $observer->getProduct()) && ($this->_matches())) {
				$this->_setItemNotAvailable($product);
			}
		}
	}

	/**
	 * Hooks on product collection creation, and sets the required flags to each product
	 * if the condition matches
	 *
	 * @param $observer Varien_Event_Observer
	 */
	public function productCollectionLoadAfter(Varien_Event_Observer $observer)
	{
		if (Mage::helper('hideprices')->isEnabled() && $this->_matches()) {
			/** @var Mage_Catalog_Model_Resource_Product_Collection $collection */
			if ($collection = $observer->getCollection()) {
				foreach ($collection as $item) {
					$this->_setItemNotAvailable($item);
				}
			}
		}
	}

	/**
	 * Hooks to catalog_product_is_salable_after
	 * Sets the product to not salable if the condition matches
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function catalogProductIsSalableAfter(Varien_Event_Observer $observer)
	{
		if (Mage::helper('hideprices')->isEnabled() && $this->_matches()) {
			if ($salable = $observer->getSalable()) {
				// mark item as not salable
				$this->_markItemSalable($observer->getSalable()->getProduct());
			}
		}
	}

}