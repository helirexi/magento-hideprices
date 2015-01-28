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

class Intercastilla_Hideprices_Helper_Data extends Mage_Core_Helper_Abstract
{

	/**
	 * System config path for extension nabled flag
	 */
	const CONFIG_PATH_HIDEPRICE_ENABLED = 'hideprices/hideprices/enabled';

	/**
	 * Flag to tell whether the extension is enabled
	 *
	 * @var bool
	 */
	private $_isEnabled;

	/**
	 * Checks if the extension is enabled
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		if (!isset($this->_isEnabled)) {
			$this->_isEnabled = Mage::getStoreConfigFlag(self::CONFIG_PATH_HIDEPRICE_ENABLED);
		}

		return $this->_isEnabled;
	}

}