<?php
/**
 * Intercastilla Hideprices Extension
 *
 * NOTICE OF LICENSE
 *
 * Copyright (c) 2015 Intercastilla Diseño y Comunicación Gráfica, S.L.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OF
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category    Intercastilla
 * @package     Intercastilla_Hideprices
 * @author      Borja A.
 * @copyright   Copyright (c) 2015 Intercastilla Diseño y Comunicación Gráfica, S.L. (http://www.intercastilla.com)
 * @license     http://opensource.org/licenses/MIT  MIT License
 */

class Intercastilla_Hideprices_Block_Catalog_Layer_View extends Mage_Catalog_Block_Layer_View
{

	/**
	 * The price attribute code.
	 * It won't probably change in a 100 years time
	 */
	const EAV_PRICE_ATTRIBUTE_CODE = 'price';

	/**
	 * Get all fiterable attributes of current category.
	 *
	 * If the user cant view prices, remove the price layer filter
	 * from the collection of filterable attributes
	 *
	 * @return array
	 */
	protected function _getFilterableAttributes()
	{
		/** @var Mage_Catalog_Model_Resource_Product_Attribute_Collection $attributes */
		// get attributes from extended class
		$attributes = parent::_getFilterableAttributes();
		if (!Mage::helper('hideprices/client')->isAllowedToSeePrices()) {
			// remove the price filter from the filter layer collection
			/** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute */
			foreach ($attributes as $filterableKey => $attribute) {
				if (self::EAV_PRICE_ATTRIBUTE_CODE == $attribute->getAttributeCode()) {
					// remove from the layer
					$attributes->removeItemByKey($filterableKey);
					break;
				}
			}
		}

		return $attributes;
	}

}