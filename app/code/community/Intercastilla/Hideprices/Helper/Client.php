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
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
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

class Intercastilla_Hideprices_Helper_Client extends Mage_Core_Helper_Abstract
{

	/**
	 * System config path for customer_groups
	 */
	const CONFIG_PATH_HIDEPRICE_CUSTOMER_GROUP = 'hideprices/hideprices/customer_groups';

	/**
	 * Flag to tell whether the user is allowed to see prices
	 *
	 * @var bool
	 */
	private $_isAllowedToSeePrices;

	/**
	 * Checks if a client is allowed to see prices on the frontend
	 *
	 * @return bool
	 */
	public function isAllowedToSeePrices()
	{
		if (!isset($this->_isAllowedToSeePrices)) {

			if (Mage::helper('hideprices')->isEnabled()) {
				// by default we don't show prices for guest users
				$this->_isAllowedToSeePrices = false;
				// is the user logged in ?
				if (Mage::getSingleton('customer/session' )->isLoggedIn()) {
					// get configured customer groups
					$configuredCustomerGroups = Mage::getStoreConfig(self::CONFIG_PATH_HIDEPRICE_CUSTOMER_GROUP);
					// get this customer group id
					$groupId = Mage::getSingleton('customer/session' )->getCustomerGroupId();
					if ('' != $configuredCustomerGroups && $groupId) {
						$configuredCustomerGroups = explode(',', $configuredCustomerGroups);
						$this->_isAllowedToSeePrices  = in_array($groupId, $configuredCustomerGroups);
					}
				}
			}

		}

		return $this->_isAllowedToSeePrices;
	}

}