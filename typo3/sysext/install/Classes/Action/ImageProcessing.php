<?php
namespace TYPO3\CMS\Install\Action;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Christian Kuhn <lolli@schwarzbu.ch>
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Handle image processing
 */
class ImageProcessing extends AbstractAction {

	/**
	 * @var array Configuration values
	 */
	protected $config_array = array(
		// Flags are set in this array if the options are available and checked ok.
		'dir_typo3temp' => 0,
		'dir_temp' => 0,
		'im_versions' => array(),
		'im' => 1,
	);

	/**
	 * Handle image processing
	 *
	 * @return mixed|void
	 */
	public function handle() {
		$this->checkTheConfig();
		$this->checkTheImageProcessing();
	}

	/**
	 * Calling the functions that checks the system
	 *
	 * @return void
	 */
	protected function checkTheConfig() {
		if (TYPO3_OS == 'WIN') {
			$paths = array($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path_lzw'], $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path'], 'c:\\php\\imagemagick\\', 'c:\\php\\GraphicsMagick\\', 'c:\\apache\\ImageMagick\\', 'c:\\apache\\GraphicsMagick\\');
			if (!isset($_SERVER['PATH'])) {
				$serverPath = array_change_key_case($_SERVER, CASE_UPPER);
				$paths = array_merge($paths, explode(';', $serverPath['PATH']));
			} else {
				$paths = array_merge($paths, explode(';', $_SERVER['PATH']));
			}
		} else {
			$paths = array($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path_lzw'], $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path'], '/usr/local/bin/', '/usr/bin/', '/usr/X11R6/bin/', '/opt/local/bin/');
			$paths = array_merge($paths, explode(':', $_SERVER['PATH']));
		}
		$paths = array_unique($paths);
		asort($paths);
	}


	/**********************
	 *
	 * IMAGE processing
	 *
	 **********************/
	/**
	 * jesus.TIF:	IBM/LZW
	 * jesus.GIF:	Save for web, 32 colors
	 * jesus.JPG:	Save for web, 30 quality
	 * jesus.PNG:	Save for web, PNG-24
	 * jesus.tga	24 bit TGA file
	 * jesus.pcx
	 * jesus.bmp	24 bit BMP file
	 * jesus_ps6.PDF:	PDF w/layers and vector data
	 * typo3logo.ai:	Illustrator 8 file
	 * pdf_from_imagemagick.PDF	PDF-file made by Acrobat Distiller from InDesign PS-file
	 *
	 *
	 * Imagemagick
	 * - Read formats
	 * - Write png, gif, jpg
	 * - compare gif size
	 * - scaling (by stdgraphic)
	 * - combining (by stdgraphic)
	 *
	 * GDlib:
	 * - create from:....
	 * - ttf text
	 *
	 * From TypoScript: (GD only, GD+IM, IM)
	 *
	 * @return void
	 */
	protected function checkTheImageProcessing() {
		$this->message('Image Processing', 'What is it?', '
			<p>
				TYPO3 is known for its ability to process images on the server.
				<br />
				In the backend interface (TBE) thumbnails are automatically
				generated (by ImageMagick in thumbs.php) as well as icons, menu
				items and pane tabs (by GDLib).
				<br />
				In the TypoScript enabled frontend all kinds of graphical
				elements are processed. Typically images are scaled down to fit
				the pages (by ImageMagick) and menu items, graphical headers and
				such are generated automatically (by GDLib + ImageMagick).
				<br />
				In addition TYPO3 is able to handle many file formats (thanks to
				ImageMagick), for example TIF, BMP, PCX, TGA, AI and PDF in
				addition to the standard web formats; JPG, GIF, PNG.
			</p>
			<p>
				In order to do this, TYPO3 uses two sets of tools:
			</p>
			<p>
				<strong>ImageMagick / GraphicsMagick:</strong>
				<br />
				For conversion of non-web formats to webformats, combining
				images with alpha-masks, performing image-effects like blurring
				and sharpening.
				<br />
				ImageMagick is a collection of external programs on the server
				called by the exec() function in PHP. TYPO3 uses three of these,
				namely \'convert\' (converting fileformats, scaling, effects),
				\'combine\'/\'composite\' (combining images with masks) and
				\'identify\' (returns image information).
				GraphicsMagick is an alternative to ImageMagick and can be enabled
				by setting [GFX][im_version_5] to \'gm\'. This is recommended and
				enabled by default.
				<br />
				Because ImageMagick and Graphicsmagick are external programs, a
				requirement must be met: The programs must be installed on the
				server and working.
				<br />
				ImageMagick is available for both Windows and Unix. The current
				version is 6+.
				<br />
				ImageMagick homepage is at <a href="http://www.imagemagick.org/">http://www.imagemagick.org/</a>
			</p>
			<p>
				<strong>GDLib:</strong>
				<br />
				For drawing boxes and rendering text on images with truetype
				fonts. Also used for icons, menuitems and generally the
				TypoScript GIFBUILDER object is based on GDlib, but extensively
				utilizing ImageMagick to process intermediate results.
				<br />
				GDLib is accessed through internal functions in PHP, you\'ll need a version
				of PHP with GDLib compiled in. Also in order to use TrueType
				fonts with GDLib you\'ll need FreeType compiled in as well.
				<br />
			</p>
			<p>
				You can disable all image processing options in TYPO3
				([GFX][image_processing]=0), but that would seriously disable
				TYPO3.
			</p>
		');
		$this->message('Image Processing', 'Verifying the image processing capabilities of your server', '
			<p>
				This page performs image processing and displays the result.
				It\'s a thorough check that everything you\'ve configured is
				working correctly.
				<br />
				It\'s quite simple to verify your installation; Just look down
				the page, the images in pairs should look like each other. If
				some images are not alike, something is wrong. You may also
				notice warnings and errors if this tool found signs of any
				problems.
			</p>
			<p>
				The image to the right is the reference image (how it should be)
				and to the left the image made by your server.
				<br />
				The reference images are made with the classic ImageMagick
				install based on the 4.2.9 RPM and 5.2.3 RPM. If the version 5
				flag is set, the reference images are made by the 5.2.3 RPM.
			</p>
			<p>
				This test will work only if your ImageMagick/GDLib configuration
				allows it to. The typo3temp/ folder must be writable for all the
				temporary image files. They are all prefixed \'install_\' so
				they are easy to recognize and delete afterwards.
			</p>
		');
		$im_path = $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path'];
		if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_version_5'] == 'gm') {
			$im_path_version = $this->config_array['im_versions'][$im_path]['gm'];
		} else {
			$im_path_version = $this->config_array['im_versions'][$im_path]['convert'];
		}
		$im_path_lzw = $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_path_lzw'];
		$im_path_lzw_version = $this->config_array['im_versions'][$im_path_lzw]['convert'];
		$msg = '
			<dl id="t3-install-imageprocessingim">
				<dt>
					ImageMagick enabled:
				</dt>
				<dd>
					' . htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['im']) . '
				</dd>
				<dt>
					ImageMagick path:
				</dt>
				<dd>
					' . htmlspecialchars($im_path) . ' <span>(' . htmlspecialchars($im_path_version) . ')</span>
				</dd>
				<dt>
					ImageMagick path/LZW:
				</dt>
				<dd>
					' . htmlspecialchars($im_path_lzw) . ' <span>(' . htmlspecialchars($im_path_lzw_version) . ')</span>
				</dd>
				<dt>
					Version 5/GraphicsMagick flag:
				</dt>
				<dd>
					' . ($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_version_5'] ? htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_version_5']) : '&nbsp;') . '
				</dd>
			</dl>
			<dl id="t3-install-imageprocessingother">
				<dt>
					GDLib enabled:
				</dt>
				<dd>
					' . ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib'] ? htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib']) : '&nbsp;') . '
				</dd>
				<dt>
					GDLib using PNG:
				</dt>
				<dd>
					' . ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib_png'] ? htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib_png']) : '&nbsp;') . '
				</dd>
				<dt>
					IM5 effects enabled:
				</dt>
				<dd>
					' . htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_v5effects']) . '
					<span>(Blurring/Sharpening with IM 5+)</span>
				</dd>
				<dt>
					Freetype DPI:
				</dt>
				<dd>
					' . htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['TTFdpi']) . '
					<span>(Should be 96 for Freetype 2)</span>
				</dd>
				<dt>
					Mask invert:
				</dt>
				<dd>
					' . htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_imvMaskState']) . '
					<span>(Should be set for some IM versions approx. 5.4+)</span>
				</dd>
			</dl>
			<dl id="t3-install-imageprocessingfileformats">
				<dt>
					File Formats:
				</dt>
				<dd>
					' . htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']) . '
				</dd>
			</dl>
		';
		// Various checks to detect IM/GM version mismatches
		$mismatch = FALSE;
		switch (strtolower($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_version_5'])) {
			case 'gm':
				if (doubleval($im_path_version) >= 2) {
					$mismatch = TRUE;
				}
				break;
			default:
				if (($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_version_5'] ? TRUE : FALSE) != doubleval($im_path_version) >= 6) {
					$mismatch = TRUE;
				}
				break;
		}
		if ($mismatch) {
			$msg .= '
				<p>
					Warning: Mismatch between the version of ImageMagick' . ' (' . htmlspecialchars($im_path_version) . ') and the configuration of ' . '[GFX][im_version_5] (' . htmlspecialchars($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_version_5']) . ')
				</p>
			';
			$etype = 2;
		} else {
			$etype = 1;
		}
		if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['im_version_5'] == 'gm') {
			$msg = str_replace('ImageMagick', 'GraphicsMagick', $msg);
		}
		$this->message('Image Processing', 'Current configuration', $msg, $etype);
		if (!$GLOBALS['TYPO3_CONF_VARS']['GFX']['image_processing']) {
			$this->message('Image Processing', 'Image Processing disabled!', '
				<p>
					Image Processing is disabled by the config flag
					[GFX][image_processing] set to FALSE (zero)
				</p>
			', 2);
			return;
		}
		$msg = '
			<p>
				<a id="testmenu"></a>
				Click each of these links in turn to test a topic.
				<strong>
					Please be aware that each test may take several seconds!
				</strong>:
			</p>
		' . $this->imageMenu();
		$this->message('Image Processing', 'Testmenu', $msg, '');
		$parseStart = GeneralUtility::milliseconds();
		$imageProc = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\GraphicalFunctions');
		$imageProc->init();
		$imageProc->tempPath = PATH_site . 'typo3temp/';
		$imageProc->dontCheckForExistingTempFile = 1;
		$imageProc->filenamePrefix = 'install_';
		$imageProc->dontCompress = 1;
		$imageProc->alternativeOutputKey = 'TYPO3_INSTALL_SCRIPT';
		$imageProc->noFramePrepended = $GLOBALS['TYPO3_CONF_VARS']['GFX']['im_noFramePrepended'];
		// Very temporary!!!
		$imageProc->dontUnlinkTempFiles = 0;
		$imActive = $this->config_array['im'] && $im_path;
		$gdActive = $GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib'];
		$formValues = GeneralUtility::_GP('images');
		switch ($formValues['images_type']) {
			case 'read':
				$headCode = 'Reading and converting images';
				$this->message($headCode, 'Supported file formats', '
					<p>
						This verifies that your ImageMagick installation is able
						to read the nine default file formats; JPG, GIF, PNG,
						TIF, BMP, PCX, TGA, PDF, AI. The tool \'identify\' will
						be used to read the  pixeldimensions of non-web formats.
						The tool \'convert\' is used to read the image and write
						a temporary JPG-file.
					</p>
					<p>
						In case the images appear remarkably darker than the reference images,
						try to set [TYPO3_CONF_VARS][GFX][colorspace] = sRGB.
					</p>
				');
				if ($imActive) {
					// Reading formats - writing JPG
					$extArr = explode(',', 'jpg,gif,png,tif,bmp,pcx,tga');
					foreach ($extArr as $ext) {
						if ($this->isExtensionEnabled($ext, $headCode, 'Read ' . strtoupper($ext))) {
							$imageProc->IM_commands = array();
							$theFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus.' . $ext;
							if (!@is_file($theFile)) {
								die('Error: ' . $theFile . ' was not a file');
							}
							$imageProc->imageMagickConvert_forceFileNameBody = 'read_' . $ext;
							$fileInfo = $imageProc->imageMagickConvert($theFile, 'jpg', '', '', '', '', array(), TRUE);
							$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
							$this->message($headCode, 'Read ' . strtoupper($ext), $result[0], $result[1]);
						}
					}
					if ($this->isExtensionEnabled('pdf', $headCode, 'Read PDF')) {
						$imageProc->IM_commands = array();
						$theFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/pdf_from_imagemagick.pdf';
						if (!@is_file($theFile)) {
							die('Error: ' . $theFile . ' was not a file');
						}
						$imageProc->imageMagickConvert_forceFileNameBody = 'read_pdf';
						$fileInfo = $imageProc->imageMagickConvert($theFile, 'jpg', '170', '', '', '', array(), TRUE);
						$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
						$this->message($headCode, 'Read PDF', $result[0], $result[1]);
					}
					if ($this->isExtensionEnabled('ai', $headCode, 'Read AI')) {
						$imageProc->IM_commands = array();
						$theFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/typo3logotype.ai';
						if (!@is_file($theFile)) {
							die('Error: ' . $theFile . ' was not a file');
						}
						$imageProc->imageMagickConvert_forceFileNameBody = 'read_ai';
						$fileInfo = $imageProc->imageMagickConvert($theFile, 'jpg', '170', '', '', '', array(), TRUE);
						$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
						$this->message($headCode, 'Read AI', $result[0], $result[1]);
					}
				} else {
					$this->message($headCode, 'Test skipped', '
						<p>
							Use of ImageMagick has been disabled in the
							configuration.
							<br />
							Refer to section \'Basic Configuration\' to change
							or review you configuration settings
						</p>
					', 2);
				}
				break;
			case 'write':
				// Writingformats - writing JPG
				$headCode = 'Writing images';
				$this->message($headCode, 'Writing GIF and PNG', '
					<p>
						This verifies that ImageMagick is able to write GIF and
						PNG files.
						<br />
						The GIF-file is attempted compressed with LZW by the
						TYPO3\\CMS\\Core\\Utility\\GeneralUtility::gif_compress() function.
					</p>
				');
				if ($imActive) {
					// Writing GIF
					$imageProc->IM_commands = array();
					$theFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus.gif';
					if (!@is_file($theFile)) {
						die('Error: ' . $theFile . ' was not a file');
					}
					$imageProc->imageMagickConvert_forceFileNameBody = 'write_gif';
					$fileInfo = $imageProc->imageMagickConvert($theFile, 'gif', '', '', '', '', array(), TRUE);
					if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gif_compress']) {
						clearstatcache();
						$prevSize = GeneralUtility::formatSize(@filesize($fileInfo[3]));
						$returnCode = GeneralUtility::gif_compress($fileInfo[3], '');
						clearstatcache();
						$curSize = GeneralUtility::formatSize(@filesize($fileInfo[3]));
						$note = array('Note on gif_compress() function:', 'The \'gif_compress\' method used was \'' . $returnCode . '\'.<br />Previous filesize: ' . $prevSize . '. Current filesize:' . $curSize);
					} else {
						$note = array('Note on gif_compress() function:', '<em>Not used! Disabled by [GFX][gif_compress]</em>');
					}
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands, $note);
					$this->message($headCode, 'Write GIF', $result[0], $result[1]);
					// Writing PNG
					$imageProc->IM_commands = array();
					$theFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus.gif';
					$imageProc->imageMagickConvert_forceFileNameBody = 'write_png';
					$fileInfo = $imageProc->imageMagickConvert($theFile, 'png', '', '', '', '', array(), TRUE);
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
					$this->message($headCode, 'Write PNG', $result[0], $result[1]);
				} else {
					$this->message($headCode, 'Test skipped', '
						<p>
							Use of ImageMagick has been disabled in the
							configuration.
							<br />
							Refer to section \'Basic Configuration\' to change
							or review you configuration settings
						</p>
					', 2);
				}
				break;
			case 'scaling':
				// Scaling
				$headCode = 'Scaling images';
				$this->message($headCode, 'Scaling transparent images', '
					<p>
						This shows how ImageMagick reacts when scaling
						transparent GIF and PNG files.
					</p>
				');
				if ($imActive) {
					// Scaling transparent image
					$imageProc->IM_commands = array();
					$theFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus2_transp.gif';
					if (!@is_file($theFile)) {
						die('Error: ' . $theFile . ' was not a file');
					}
					$imageProc->imageMagickConvert_forceFileNameBody = 'scale_gif';
					$fileInfo = $imageProc->imageMagickConvert($theFile, 'gif', '150', '', '', '', array(), TRUE);
					if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gif_compress']) {
						clearstatcache();
						$prevSize = GeneralUtility::formatSize(@filesize($fileInfo[3]));
						$returnCode = GeneralUtility::gif_compress($fileInfo[3], '');
						clearstatcache();
						$curSize = GeneralUtility::formatSize(@filesize($fileInfo[3]));
						$note = array('Note on gif_compress() function:', 'The \'gif_compress\' method used was \'' . $returnCode . '\'.<br />Previous filesize: ' . $prevSize . '. Current filesize:' . $curSize);
					} else {
						$note = array('Note on gif_compress() function:', '<em>Not used! Disabled by [GFX][gif_compress]</em>');
					}
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands, $note);
					$this->message($headCode, 'GIF to GIF, 150 pixels wide', $result[0], $result[1]);
					$imageProc->IM_commands = array();
					$theFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus2_transp.png';
					if (!@is_file($theFile)) {
						die('Error: ' . $theFile . ' was not a file');
					}
					$imageProc->imageMagickConvert_forceFileNameBody = 'scale_png';
					$fileInfo = $imageProc->imageMagickConvert($theFile, 'png', '150', '', '', '', array(), TRUE);
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
					$this->message($headCode, 'PNG to PNG, 150 pixels wide', $result[0], $result[1]);
					$imageProc->IM_commands = array();
					$theFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus2_transp.gif';
					if (!@is_file($theFile)) {
						die('Error: ' . $theFile . ' was not a file');
					}
					$imageProc->imageMagickConvert_forceFileNameBody = 'scale_jpg';
					$fileInfo = $imageProc->imageMagickConvert($theFile, 'jpg', '150', '', '', '', array(), TRUE);
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
					$this->message($headCode, 'GIF to JPG, 150 pixels wide', $result[0], $result[1]);
				} else {
					$this->message($headCode, 'Test skipped', '
						<p>
							Use of ImageMagick has been disabled in the
							configuration.
							<br />
							Refer to section \'Basic Configuration\' to change
							or review you configuration settings
						</p>
					', 2);
				}
				break;
			case 'combining':
				// Combine
				$headCode = 'Combining images';
				$this->message($headCode, 'Combining images', '
					<p>
						This verifies that the ImageMagick tool,
						\'combine\'/\'composite\', is able to combine two images
						through a grayscale mask.
						<br />
						If the masking seems to work but inverted, that just
						means you\'ll have to make sure the invert flag is set
						(some combination of im_negate_mask/im_imvMaskState)
					</p>
				');
				if ($imActive) {
					$imageProc->IM_commands = array();
					$input = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/greenback.gif';
					$overlay = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus.jpg';
					$mask = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/blackwhite_mask.gif';
					if (!@is_file($input)) {
						die('Error: ' . $input . ' was not a file');
					}
					if (!@is_file($overlay)) {
						die('Error: ' . $overlay . ' was not a file');
					}
					if (!@is_file($mask)) {
						die('Error: ' . $mask . ' was not a file');
					}
					$output = $imageProc->tempPath . $imageProc->filenamePrefix . GeneralUtility::shortMD5(($imageProc->alternativeOutputKey . 'combine1')) . '.jpg';
					$imageProc->combineExec($input, $overlay, $mask, $output, TRUE);
					$fileInfo = $imageProc->getImageDimensions($output);
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
					$this->message($headCode, 'Combine using a GIF mask with only black and white', $result[0], $result[1]);
					// Combine
					$imageProc->IM_commands = array();
					$input = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/combine_back.jpg';
					$overlay = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus.jpg';
					$mask = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/combine_mask.jpg';
					if (!@is_file($input)) {
						die('Error: ' . $input . ' was not a file');
					}
					if (!@is_file($overlay)) {
						die('Error: ' . $overlay . ' was not a file');
					}
					if (!@is_file($mask)) {
						die('Error: ' . $mask . ' was not a file');
					}
					$output = $imageProc->tempPath . $imageProc->filenamePrefix . GeneralUtility::shortMD5(($imageProc->alternativeOutputKey . 'combine2')) . '.jpg';
					$imageProc->combineExec($input, $overlay, $mask, $output, TRUE);
					$fileInfo = $imageProc->getImageDimensions($output);
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
					$this->message($headCode, 'Combine using a JPG mask with graylevels', $result[0], $result[1]);
				} else {
					$this->message($headCode, 'Test skipped', '
						<p>
							Use of ImageMagick has been disabled in the
							configuration.
							<br />
							Refer to section \'Basic Configuration\' to change
							or review you configuration settings
						</p>
					', 2);
				}
				break;
			case 'gdlib':
				// GDLibrary
				$headCode = 'GDLib';
				$this->message($headCode, 'Testing GDLib', '
					<p>
						This verifies that the GDLib installation works properly.
					</p>
				');
				if ($gdActive) {
					// GD with box
					$imageProc->IM_commands = array();
					$im = imagecreatetruecolor(170, 136);
					$Bcolor = ImageColorAllocate($im, 0, 0, 0);
					ImageFilledRectangle($im, 0, 0, 170, 136, $Bcolor);
					$workArea = array(0, 0, 170, 136);
					$conf = array(
						'dimensions' => '10,50,150,36',
						'color' => 'olive'
					);
					$imageProc->makeBox($im, $conf, $workArea);
					$output = $imageProc->tempPath . $imageProc->filenamePrefix . GeneralUtility::shortMD5('GDbox') . '.' . $imageProc->gifExtension;
					$imageProc->ImageWrite($im, $output);
					$fileInfo = $imageProc->getImageDimensions($output);
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
					$this->message($headCode, 'Create simple image', $result[0], $result[1]);
					// GD from image with box
					$imageProc->IM_commands = array();
					$input = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'imgs/jesus.' . $imageProc->gifExtension;
					if (!@is_file($input)) {
						die('Error: ' . $input . ' was not a file');
					}
					$im = $imageProc->imageCreateFromFile($input);
					$workArea = array(0, 0, 170, 136);
					$conf = array();
					$conf['dimensions'] = '10,50,150,36';
					$conf['color'] = 'olive';
					$imageProc->makeBox($im, $conf, $workArea);
					$output = $imageProc->tempPath . $imageProc->filenamePrefix . GeneralUtility::shortMD5('GDfromImage+box') . '.' . $imageProc->gifExtension;
					$imageProc->ImageWrite($im, $output);
					$fileInfo = $imageProc->getImageDimensions($output);
					$GDWithBox_filesize = @filesize($output);
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
					$this->message($headCode, 'Create image from file', $result[0], $result[1]);
					// GD with text
					$imageProc->IM_commands = array();
					$im = imagecreatetruecolor(170, 136);
					$Bcolor = ImageColorAllocate($im, 128, 128, 150);
					ImageFilledRectangle($im, 0, 0, 170, 136, $Bcolor);
					$workArea = array(0, 0, 170, 136);
					$conf = array(
						'iterations' => 1,
						'angle' => 0,
						'antiAlias' => 1,
						'text' => 'HELLO WORLD',
						'fontColor' => '#003366',
						'fontSize' => 18,
						'fontFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('core') . 'Resources/Private/Font/vera.ttf',
						'offset' => '17,40'
					);
					$conf['BBOX'] = $imageProc->calcBBox($conf);
					$imageProc->makeText($im, $conf, $workArea);
					$output = $imageProc->tempPath . $imageProc->filenamePrefix . GeneralUtility::shortMD5('GDwithText') . '.' . $imageProc->gifExtension;
					$imageProc->ImageWrite($im, $output);
					$fileInfo = $imageProc->getImageDimensions($output);
					$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands);
					$this->message($headCode, 'Render text with TrueType font', $result[0], $result[1]);
					if ($imActive) {
						// extension: GD with text, niceText
						$conf['offset'] = '17,65';
						$conf['niceText'] = 1;
						$imageProc->makeText($im, $conf, $workArea);
						$output = $imageProc->tempPath . $imageProc->filenamePrefix . GeneralUtility::shortMD5('GDwithText-niceText') . '.' . $imageProc->gifExtension;
						$imageProc->ImageWrite($im, $output);
						$fileInfo = $imageProc->getImageDimensions($output);
						$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands, array('Note on \'niceText\':', '\'niceText\' is a concept that tries to improve the antialiasing of the rendered type by actually rendering the textstring in double size on a black/white mask, downscaling the mask and masking the text onto the image through this mask. This involves ImageMagick \'combine\'/\'composite\' and \'convert\'.'));
						$this->message($headCode, 'Render text with TrueType font using \'niceText\' option', '
							<p>
								(If the image has another background color than
								the image above (eg. dark background color with
								light text) then you will have to set
								TYPO3_CONF_VARS[GFX][im_imvMaskState]=1)
							</p>
						' . $result[0], $result[1]);
					} else {
						$this->message($headCode, 'Render text with TrueType font using \'niceText\' option', '
							<p>
								<strong>Test is skipped!</strong>
							</p>
							<p>
								Use of ImageMagick has been disabled in the
								configuration. ImageMagick is needed to generate
								text with the niceText option.
								<br />
								Refer to section \'Basic Configuration\' to
								change or review you configuration settings
							</p>
						', 2);
					}
					if ($imActive) {
						// extension: GD with text, niceText AND shadow
						$conf['offset'] = '17,90';
						$conf['niceText'] = 1;
						$conf['shadow.'] = array(
							'offset' => '2,2',
							'blur' => $imageProc->V5_EFFECTS ? '20' : '90',
							'opacity' => '50',
							'color' => 'black'
						);
						$imageProc->makeShadow($im, $conf['shadow.'], $workArea, $conf);
						$imageProc->makeText($im, $conf, $workArea);
						$output = $imageProc->tempPath . $imageProc->filenamePrefix . GeneralUtility::shortMD5('GDwithText-niceText-shadow') . '.' . $imageProc->gifExtension;
						$imageProc->ImageWrite($im, $output);
						$fileInfo = $imageProc->getImageDimensions($output);
						$result = $this->displayTwinImage($fileInfo[3], $imageProc->IM_commands, array('Note on drop shadows:', 'Drop shadows are done by using ImageMagick to blur a mask through which the drop shadow is generated. The blurring of the mask only works in ImageMagick 4.2.9 and <em>not</em> ImageMagick 5 - which is why you may see a hard and not soft shadow.'));
						$this->message($headCode, 'Render \'niceText\' with a shadow under', '
							<p>
								(This test makes sense only if the above test
								had a correct output. But if so, you may not see
								a soft dropshadow from the third text string as
								you should. In that case you are most likely
								using ImageMagick 5 and should set the flag
								TYPO3_CONF_VARS[GFX][im_v5effects]. However this
								may cost server performance!
							</p>
						' . $result[0], $result[1]);
					} else {
						$this->message($headCode, 'Render \'niceText\' with a shadow under', '
							<p>
								<strong>Test is skipped!</strong>
							</p>
							<p>
								Use of ImageMagick has been disabled in the
								configuration. ImageMagick is needed to generate
								shadows.
								<br />
								Refer to section \'Basic Configuration\' to
								change or review you configuration settings
							</p>
						', 2);
					}
					if ($imageProc->gifExtension == 'gif') {
						$buffer = 20;
						$assess = 'This assessment is based on the filesize from \'Create image from file\' test, which were ' . $GDWithBox_filesize . ' bytes';
						$goodNews = 'If the image was LZW compressed you would expect to have a size of less than 9000 bytes. If you open the image with Photoshop and saves it from Photoshop, you\'ll a filesize like that.<br />The good news is (hopefully) that your [GFX][im_path_lzw] path is correctly set so the gif_compress() function will take care of the compression for you!';
						if ($GDWithBox_filesize < 8784 + $buffer) {
							$msg = '
								<p>
									<strong>
										Your GDLib appears to have LZW compression!
									</strong>
									<br />
									This assessment is based on the filesize
									from \'Create image from file\' test, which
									were ' . $GDWithBox_filesize . ' bytes.
									<br />
									This is a real advantage for you because you
									don\'t need to use ImageMagick for LZW
									compressing. In order to make sure that
									GDLib is used,
									<strong>
										please set the config option
										[GFX][im_path_lzw] to an empty string!
									</strong>
									<br />
									When you disable the use of ImageMagick for
									LZW compressing, you\'ll see that the
									gif_compress() function has a return code of
									\'GD\' (for GDLib) instead of \'IM\' (for
									ImageMagick)
								</p>
							';
						} elseif ($GDWithBox_filesize > 19000) {
							$msg = '
								<p>
									<strong>
										Your GDLib appears to have no
										compression at all!
									</strong>
									<br />
									' . $assess . '
									<br />
									' . $goodNews . '
								</p>
							';
						} else {
							$msg = '
								<p>
									Your GDLib appears to have RLE compression
									<br />
									' . $assess . '
									<br />
									' . $goodNews . '
								</p>
							';
						}
						$this->message($headCode, 'GIF compressing in GDLib', '
						' . $msg . '
						', 1);
					}
				} else {
					$this->message($headCode, 'Test skipped', '
						<p>
							Use of GDLib has been disabled in the configuration.
							<br />
							Refer to section \'Basic Configuration\' to change
							or review you configuration settings
						</p>
					', 2);
				}
				break;
		}
		if ($formValues['images_type']) {
			$parseMS = GeneralUtility::milliseconds() - $parseStart;
			$this->message('Info', 'Parsetime', '
				<p>
					' . $parseMS . ' ms
				</p>
			');
		}
	}


	/**
	 * Check if image file extension is enabled
	 * Adds error message to the message array
	 *
	 * @param string $ext The image file extension
	 * @param string $headCode The header for the message
	 * @param string $short The short description for the message
	 * @return boolean TRUE if extension is enabled
	 */
	protected function isExtensionEnabled($ext, $headCode, $short) {
		if (!GeneralUtility::inList($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'], $ext)) {
			$this->message($headCode, $short, '
				<p>
					Skipped - extension not in the list of allowed extensions
					([GFX][imagefile_ext]).
				</p>
			', 1);
		} else {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Generate the HTML after reading and converting images
	 * Displays the verification and the converted image if succeeded
	 * Adds error messages if needed
	 *
	 * @param string $imageFile The file name of the converted image
	 * @param array $IMcommands The ImageMagick commands used
	 * @param string $note Additional note for image operation
	 * @return array Contains content and highest error level
	 */
	protected function displayTwinImage($imageFile, $IMcommands = array(), $note = '') {
		// Get the template file
		$templateFile = @file_get_contents((PATH_site . $this->templateFilePath . 'DisplayTwinImage.html'));
		// Get the template part from the file
		$template = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($templateFile, '###TEMPLATE###');
		$errorLevels = array(-1);
		$imageSubpart = '';
		$noImageSubpart = '';
		if ($imageFile) {
			// Get the subpart for the images
			$imageSubpart = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($template, '###IMAGE###');
			$verifyFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('install') . 'verify_imgs/' . basename($imageFile);
			$destImg = @getImageSize($imageFile);
			$verifyImg = @getImageSize($verifyFile);
			clearstatcache();
			$destImg['filesize'] = @filesize($imageFile);
			clearstatcache();
			$verifyImg['filesize'] = @filesize($verifyFile);
			// Define the markers content
			$imageMarkers = array(
				'destWidth' => $destImg[0],
				'destHeight' => $destImg[1],
				'destUrl' => '../../' . substr($imageFile, strlen(PATH_site)),
				'verifyWidth' => $verifyImg[0],
				'verifyHeight' => $verifyImg[1],
				'verifyUrl' => '../' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('install') . 'verify_imgs/' . basename($verifyFile),
				'yourServer' => 'Your server:',
				'yourServerInformation' => GeneralUtility::formatSize($destImg['filesize']) . ', ' . $destImg[0] . 'x' . $destImg[1] . ' pixels',
				'reference' => 'Reference:',
				'referenceInformation' => GeneralUtility::formatSize($verifyImg['filesize']) . ', ' . $verifyImg[0] . 'x' . $verifyImg[1] . ' pixels'
			);
			$differentPixelDimensionsSubpart = '';
			if ($destImg[0] != $verifyImg[0] || $destImg[1] != $verifyImg[1]) {
				// Get the subpart for the different pixel dimensions message
				$differentPixelDimensionsSubpart = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($imageSubpart, '###DIFFERENTPIXELDIMENSIONS###');
				// Define the markers content
				$differentPixelDimensionsMarkers = array(
					'message' => 'Pixel dimension are not equal!'
				);
				// Fill the markers in the subpart
				$differentPixelDimensionsSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray($differentPixelDimensionsSubpart, $differentPixelDimensionsMarkers, '###|###', TRUE, FALSE);
				$errorLevels[] = 2;
			}
			// Substitute the subpart for different pixel dimensions message
			$imageSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteSubpart($imageSubpart, '###DIFFERENTPIXELDIMENSIONS###', $differentPixelDimensionsSubpart);
			$noteSubpart = '';
			if ($note) {
				// Get the subpart for the note
				$noteSubpart = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($imageSubpart, '###NOTE###');
				// Define the markers content
				$noteMarkers = array(
					'message' => $note[0],
					'label' => $note[1]
				);
				// Fill the markers in the subpart
				$noteSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray($noteSubpart, $noteMarkers, '###|###', TRUE, FALSE);
			}
			// Substitute the subpart for the note
			$imageSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteSubpart($imageSubpart, '###NOTE###', $noteSubpart);
			$imCommandsSubpart = '';
			if (count($IMcommands)) {
				$commands = $this->formatImCmds($IMcommands);
				// Get the subpart for the ImageMagick commands
				$imCommandsSubpart = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($imageSubpart, '###IMCOMMANDS###');
				// Define the markers content
				$imCommandsMarkers = array(
					'message' => 'ImageMagick commands executed:',
					'rows' => \TYPO3\CMS\Core\Utility\MathUtility::forceIntegerInRange(count($commands), 2, 10),
					'commands' => htmlspecialchars(implode(LF, $commands))
				);
				// Fill the markers in the subpart
				$imCommandsSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray($imCommandsSubpart, $imCommandsMarkers, '###|###', TRUE, FALSE);
			}
			// Substitute the subpart for the ImageMagick commands
			$imageSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteSubpart($imageSubpart, '###IMCOMMANDS###', $imCommandsSubpart);
			// Fill the markers
			$imageSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray($imageSubpart, $imageMarkers, '###|###', TRUE, FALSE);
		} else {
			// Get the subpart when no image has been generated
			$noImageSubpart = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($template, '###NOIMAGE###');
			$commands = $this->formatImCmds($IMcommands);
			$commandsSubpart = '';
			if (count($commands)) {
				// Get the subpart for the ImageMagick commands
				$commandsSubpart = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($noImageSubpart, '###COMMANDSAVAILABLE###');
				// Define the markers content
				$commandsMarkers = array(
					'rows' => \TYPO3\CMS\Core\Utility\MathUtility::forceIntegerInRange(count($commands), 2, 10),
					'commands' => htmlspecialchars(implode(LF, $commands))
				);
				// Fill the markers in the subpart
				$commandsSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray($commandsSubpart, $commandsMarkers, '###|###', TRUE, FALSE);
			}
			// Substitute the subpart for the ImageMagick commands
			$noImageSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteSubpart($noImageSubpart, '###COMMANDSAVAILABLE###', $commandsSubpart);
			// Define the markers content
			$noImageMarkers = array(
				'message' => 'There was no result from the ImageMagick operation',
				'label' => 'Below there\'s a dump of the ImageMagick commands executed:'
			);
			// Fill the markers
			$noImageSubpart = \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray($noImageSubpart, $noImageMarkers, '###|###', TRUE, FALSE);
			$errorLevels[] = 3;
		}
		// Substitute the subpart when image has been generated
		$content = \TYPO3\CMS\Core\Html\HtmlParser::substituteSubpart($template, '###IMAGE###', $imageSubpart);
		// Substitute the subpart when no image has been generated
		$content = \TYPO3\CMS\Core\Html\HtmlParser::substituteSubpart($content, '###NOIMAGE###', $noImageSubpart);
		return array($content, max($errorLevels));
	}

	/**
	 * Format ImageMagick commands for use in HTML
	 *
	 * @param array $arr The ImageMagick commands
	 * @return string The formatted commands
	 */
	protected function formatImCmds($arr) {
		$out = array();
		if (is_array($arr)) {
			foreach ($arr as $v) {
				$out[] = $v[1];
				if ($v[2]) {
					$out[] = '   RETURNED: ' . $v[2];
				}
			}
		}
		return $out;
	}

	/**
	 * Generate the menu for the test menu in 'image processing'
	 *
	 * @return string The HTML for the test menu
	 */
	protected function imageMenu() {
		$template = @file_get_contents((PATH_site . $this->templateFilePath . 'ImageMenu.html'));
		$menuSubPart = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($template, '###MENU###');
		$menuItemSubPart = \TYPO3\CMS\Core\Html\HtmlParser::getSubpart($menuSubPart, '###MENUITEM###');
		$menuItems = array(
			'read' => 'Reading image formats',
			'write' => 'Writing GIF and PNG',
			'scaling' => 'Scaling images',
			'combining' => 'Combining images',
			'gdlib' => 'GD library functions'
		);
		$items = array();
		foreach ($menuItems as $menuKey => $menuName) {
			$markers = array(
				'url' => htmlspecialchars('index.php?TYPO3_INSTALL[type]=images&images[images_type]=' . $menuKey . '#imageMenu'),
				'item' => $menuName
			);
			$items[] = \TYPO3\CMS\Core\Html\HtmlParser::substituteMarkerArray($menuItemSubPart, $markers, '###|###', TRUE, FALSE);
		}
		$menuSubPart = \TYPO3\CMS\Core\Html\HtmlParser::substituteSubpart($menuSubPart, '###MENUITEM###', implode(LF, $items));
		return $menuSubPart;
	}


}
?>