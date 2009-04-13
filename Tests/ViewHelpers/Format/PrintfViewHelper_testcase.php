<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Christopher Hlubek <hlubek@networkteam.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(t3lib_extMgm::extPath('extbase', 'Tests/Base_testcase.php'));

class Tx_Fluid_ViewHelpers_Format_PrintfViewHelper_testcase extends Tx_Extbase_Base_testcase {
	public function test_PrintfCanUseArrayAsArgument() {
		$helper = new Tx_Fluid_ViewHelpers_Format_PrintfViewHelper();
		$result = $helper->render('%04d-%02d-%02d', array('year' => 2009, 'month' => 4, 'day' => 5));
		$this->assertEquals('2009-04-05', $result);
	}

	public function test_PrintfCanUseStringAsArgument() {
		$helper = new Tx_Fluid_ViewHelpers_Format_PrintfViewHelper();
		$result = $helper->render('%.3e', '362525200');
		$this->assertEquals('3.625e+8', $result);
	}	
}
?>