<?php
namespace TYPO3\CMS\Install\ControllerAction;

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
 * Handle important actions
 */
class ImportantActions extends AbstractAction implements ActionInterface {

	/**
	 * Handle this action
	 *
	 * @return string content
	 */
	public function handle() {
		$this->initialize();

		if (isset($this->postValues['set']['changeEncryptionKey'])) {
			$this->setNewEncryptionKeyAndLogOut();
		}

		$actionMessages = array();
		if (isset($this->postValues['set']['changeInstallToolPassword'])) {
			$actionMessages[] = $this->changeInstallToolPassword();
		}
		if (isset($this->postValues['set']['changeSiteName'])) {
			$actionMessages[] = $this->changeSiteName();
		}
		if (isset($this->postValues['set']['createAdministrator'])) {
			$actionMessages[] = $this->createAdministrator();
		}

		// Database analyzer handling
		if (isset($this->postValues['set']['databaseAnalyzerExecute'])
			|| isset($this->postValues['set']['databaseAnalyzerAnalyze'])
		) {
			$this->loadExtLocalconfDatabaseAndExtTables();
		}
		if (isset($this->postValues['set']['databaseAnalyzerExecute'])) {
			$actionMessages = array_merge($actionMessages, $this->databaseAnalyzerExecute());
		}
		if (isset($this->postValues['set']['databaseAnalyzerAnalyze'])) {
			$actionMessages[] = $this->databaseAnalyzerAnalyze();
		}

		$this->view->assign('actionMessages', $actionMessages);

		$operatingSystem = TYPO3_OS == 'WIN' ? 'Windows' : 'Unix';
		$cgiDetected = (PHP_SAPI == 'fpm-fcgi' || PHP_SAPI == 'cgi' || PHP_SAPI == 'isapi' || PHP_SAPI == 'cgi-fcgi')
			? TRUE
			: FALSE;

		$this->view
			->assign('operatingSystem', $operatingSystem)
			->assign('cgiDetected', $cgiDetected)
			->assign('databaseName', $GLOBALS['TYPO3_CONF_VARS']['DB']['database'])
			->assign('databaseUsername', $GLOBALS['TYPO3_CONF_VARS']['DB']['username'])
			->assign('databaseHost', $GLOBALS['TYPO3_CONF_VARS']['DB']['host'])
			->assign('databasePort', $GLOBALS['TYPO3_CONF_VARS']['DB']['port'])
			->assign('databaseNumberOfTables', count($this->getDatabase()->admin_get_tables()));

		return $this->view->render();
	}

	/**
	 * Set new password if requested
	 *
	 * @return \TYPO3\CMS\Install\Status\StatusInterface
	 */
	protected function changeInstallToolPassword() {
		$values = $this->postValues['values'];
		if ($values['newInstallToolPassword'] !== $values['newInstallToolPasswordCheck']) {
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\ErrorStatus');
			$message->setTitle('Install tool password not changed');
			$message->setMessage('Given passwords do not match.');
		} elseif (strlen($values['newInstallToolPassword']) < 8) {
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\ErrorStatus');
			$message->setTitle('Install tool password not changed');
			$message->setMessage('Given passwords must be a least eight characters long.');
		} else {
			/** @var \TYPO3\CMS\Core\Configuration\ConfigurationManager $configurationManager */
			$configurationManager = $this->objectManager->get('TYPO3\\CMS\\Core\\Configuration\\ConfigurationManager');
			$configurationManager->setLocalConfigurationValueByPath('BE/installToolPassword', md5($values['newInstallToolPassword']));
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\OkStatus');
			$message->setTitle('Install tool password changed');
		}
		return $message;
	}

	/**
	 * Set new site name
	 *
	 * @return \TYPO3\CMS\Install\Status\StatusInterface
	 */
	protected function changeSiteName() {
		$values = $this->postValues['values'];
		if (isset($values['newSiteName']) && strlen($values['newSiteName']) > 0) {
			/** @var \TYPO3\CMS\Core\Configuration\ConfigurationManager $configurationManager */
			$configurationManager = $this->objectManager->get('TYPO3\\CMS\\Core\\Configuration\\ConfigurationManager');
			$configurationManager->setLocalConfigurationValueByPath('SYS/sitename', $values['newSiteName']);
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\OkStatus');
			$message->setTitle('Site name changed');
			$this->view->assign('siteName', $values['newSiteName']);
		} else {
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\ErrorStatus');
			$message->setTitle('Site name not changed');
			$message->setMessage('Site name must be at least one character long.');
		}
		return $message;
	}

	/**
	 * Set new encryption key
	 *
	 * @return void
	 */
	protected function setNewEncryptionKeyAndLogOut() {
		$newKey = \TYPO3\CMS\Core\Utility\GeneralUtility::getRandomHexString(96);
		/** @var \TYPO3\CMS\Core\Configuration\ConfigurationManager $configurationManager */
		$configurationManager = $this->objectManager->get('TYPO3\\CMS\\Core\\Configuration\\ConfigurationManager');
		$configurationManager->setLocalConfigurationValueByPath('SYS/encryptionKey', $newKey);
		/** @var $formProtection \TYPO3\CMS\Core\FormProtection\InstallToolFormProtection */
		$formProtection = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get(
			'TYPO3\\CMS\\Core\\FormProtection\\InstallToolFormProtection'
		);
		$formProtection->clean();
		/** @var \TYPO3\CMS\Install\Session $session */
		$session = $this->objectManager->get('TYPO3\\CMS\\Install\\Session');
		$session->destroySession();
		\TYPO3\CMS\Core\Utility\HttpUtility::redirect('StepInstaller.php?install[context]=' . $this->getContext());
	}

	/**
	 * Create administrator user
	 *
	 * @return \TYPO3\CMS\Install\Status\StatusInterface
	 */
	protected function createAdministrator() {
		$values = $this->postValues['values'];
		$username = preg_replace('/[^\\da-z._]/i', '', trim($values['newUserUsername']));
		$password = $values['newUserPassword'];
		$passwordCheck = $values['newUserPasswordCheck'];

		if (strlen($username) < 1) {
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\ErrorStatus');
			$message->setTitle('Administrator user not created');
			$message->setMessage('No valid username given.');
		} elseif ($password !== $passwordCheck) {
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\ErrorStatus');
			$message->setTitle('Administrator user not created');
			$message->setMessage('Passwords do not match.');
		} elseif (strlen($password) < 8) {
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\ErrorStatus');
			$message->setTitle('Administrator user not created');
			$message->setMessage('Password must be at least eight characters long.');
		} else {
			$database = $this->getDatabase();
			$userExists = $database->exec_SELECTcountRows(
				'uid',
				'be_users',
				'username=' . $database->fullQuoteStr($username, 'be_users')
			);
			if ($userExists) {
				/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
				$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\ErrorStatus');
				$message->setTitle('Administrator user not created');
				$message->setMessage('A user with username ' . $username . ' exists already.');
			} else {
				// @TODO: Handle saltedpasswords in installer and store password salted in the first place
				$adminUserFields = array(
					'username' => $username,
					'password' => md5($password),
					'admin' => 1,
					'tstamp' => $GLOBALS['EXEC_TIME'],
					'crdate' => $GLOBALS['EXEC_TIME']
				);
				$database->exec_INSERTquery('be_users', $adminUserFields);
				/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
				$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\OkStatus');
				$message->setTitle('Administrator created');
			}
		}

		return $message;
	}

	/**
	 * Execute database migration
	 *
	 * @return array<\TYPO3\CMS\Install\Status\StatusInterface>
	 */
	protected function databaseAnalyzerExecute() {
		$messages = array();

		// Early return in case no updade was selected
		if (empty($this->postValues['values'])) {
			/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
			$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\WarningStatus');
			$message->setTitle('No database changes selected');
			$messages[] = $message;
			return $message;
		}

		/** @var \TYPO3\CMS\Install\Sql\SchemaMigrator $schemaMigrator */
		$schemaMigrator = $this->objectManager->get('TYPO3\\CMS\\Install\\Sql\\SchemaMigrator');

		$expectedSchema = $this->getExpectedDatabaseSchema();
		$currentSchema = $schemaMigrator->getFieldDefinitions_database();

		$statementHashesToPerform = $this->postValues['values'];

		$results = array();

		// Difference from expected to current
		$addCreateChange = $schemaMigrator->getDatabaseExtra($expectedSchema, $currentSchema);
		$addCreateChange = $schemaMigrator->getUpdateSuggestions($addCreateChange);
		$results[] = $schemaMigrator->performUpdateQueries($addCreateChange['add'], $statementHashesToPerform);
		$results[] = $schemaMigrator->performUpdateQueries($addCreateChange['change'], $statementHashesToPerform);
		$results[] = $schemaMigrator->performUpdateQueries($addCreateChange['create_table'], $statementHashesToPerform);

		// Difference from current to expected
		$dropRename = $schemaMigrator->getDatabaseExtra($currentSchema, $expectedSchema);
		$dropRename = $schemaMigrator->getUpdateSuggestions($dropRename, 'remove');
		$results[] = $schemaMigrator->performUpdateQueries($dropRename['change'], $statementHashesToPerform);
		$results[] = $schemaMigrator->performUpdateQueries($dropRename['drop'], $statementHashesToPerform);
		$results[] = $schemaMigrator->performUpdateQueries($dropRename['change_table'], $statementHashesToPerform);
		$results[] = $schemaMigrator->performUpdateQueries($dropRename['drop_table'], $statementHashesToPerform);

		// Create error flash messages if any
		foreach ($results as $resultSet) {
			if (is_array($resultSet)) {
				foreach ($resultSet as $errorMessage) {
					/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
					$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\ErrorStatus');
					$message->setTitle('Database update failed');
					$message->setMessage('Error: ' . $errorMessage);
					$messages[] = $message;
				}
			}
		}

		/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
		$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\OkStatus');
		$message->setTitle('Executed database updates');
		$messages[] = $message;

		return $messages;
	}

	/**
	 * "Compare" action of analyzer
	 *
	 * @TODO: The SchemaMigrator API is a mess and should be refactored
	 * @TODO: Refactoring this should aim to make EM independent from ext:install by moving SchemaMigrator to ext:core
	 * @return \TYPO3\CMS\Install\Status\StatusInterface
	 */
	protected function databaseAnalyzerAnalyze() {
		/** @var \TYPO3\CMS\Install\Sql\SchemaMigrator $schemaMigrator */
		$schemaMigrator = $this->objectManager->get('TYPO3\\CMS\\Install\\Sql\\SchemaMigrator');

		$expectedSchema = $this->getExpectedDatabaseSchema();
		$currentSchema = $schemaMigrator->getFieldDefinitions_database();

		$databaseAnalyzerSuggestion = array();

		// Difference from expected to current
		$addCreateChange = $schemaMigrator->getDatabaseExtra($expectedSchema, $currentSchema);
		$addCreateChange = $schemaMigrator->getUpdateSuggestions($addCreateChange);
		if (isset($addCreateChange['create_table'])) {
			$databaseAnalyzerSuggestion['addTable'] = array();
			foreach ($addCreateChange['create_table'] as $hash => $statement) {
				$databaseAnalyzerSuggestion['addTable'][$hash] = array(
					'hash' => $hash,
					'statement' => $statement,
				);
			}
		}
		if (isset($addCreateChange['add'])) {
			$databaseAnalyzerSuggestion['addField'] = array();
			foreach ($addCreateChange['add'] as $hash => $statement) {
				$databaseAnalyzerSuggestion['addField'][$hash] = array(
					'hash' => $hash,
					'statement' => $statement,
				);
			}
		}
		if (isset($addCreateChange['change'])) {
			$databaseAnalyzerSuggestion['change'] = array();
			foreach ($addCreateChange['change'] as $hash => $statement) {
				$databaseAnalyzerSuggestion['change'][$hash] = array(
					'hash' => $hash,
					'statement' => $statement,
				);
				if (isset($addCreateChange['change_currentValue'][$hash])) {
					$databaseAnalyzerSuggestion['change'][$hash]['current'] = $addCreateChange['change_currentValue'][$hash];
				}
			}
		}

		// Difference from current to expected
		$dropRename = $schemaMigrator->getDatabaseExtra($currentSchema, $expectedSchema);
		$dropRename = $schemaMigrator->getUpdateSuggestions($dropRename, 'remove');
		if (isset($dropRename['change_table'])) {
			$databaseAnalyzerSuggestion['renameTableToUnused'] = array();
			foreach ($dropRename['change_table'] as $hash => $statement) {
				$databaseAnalyzerSuggestion['renameTableToUnused'][$hash] = array(
					'hash' => $hash,
					'statement' => $statement,
				);
				if (!empty($dropRename['tables_count'][$hash])) {
					$databaseAnalyzerSuggestion['renameTableToUnused'][$hash]['count'] = $dropRename['tables_count'][$hash];
				}
			}
		}
		if (isset($dropRename['change'])) {
			$databaseAnalyzerSuggestion['renameTableFieldToUnused'] = array();
			foreach ($dropRename['change'] as $hash => $statement) {
				$databaseAnalyzerSuggestion['renameTableFieldToUnused'][$hash] = array(
					'hash' => $hash,
					'statement' => $statement,
				);
			}
		}
		if (isset($dropRename['drop'])) {
			$databaseAnalyzerSuggestion['deleteField'] = array();
			foreach ($dropRename['drop'] as $hash => $statement) {
				$databaseAnalyzerSuggestion['deleteField'][$hash] = array(
					'hash' => $hash,
					'statement' => $statement,
				);
			}
		}
		if (isset($dropRename['drop_table'])) {
			$databaseAnalyzerSuggestion['deleteTable'] = array();
			foreach ($dropRename['drop_table'] as $hash => $statement) {
				$databaseAnalyzerSuggestion['deleteTable'][$hash] = array(
					'hash' => $hash,
					'statement' => $statement,
				);
				if (!empty($dropRename['tables_count'][$hash])) {
					$databaseAnalyzerSuggestion['deleteTable'][$hash]['count'] = $dropRename['tables_count'][$hash];
				}
			}
		}

		$this->view->assign('databaseAnalyzerSuggestion', $databaseAnalyzerSuggestion);

		/** @var $message \TYPO3\CMS\Install\Status\StatusInterface */
		$message = $this->objectManager->get('TYPO3\\CMS\\Install\\Status\\OkStatus');
		$message->setTitle('Analyzed current database');
		return $message;
	}

	/**
	 * Get expected schema array
	 *
	 * @return array Expected schema
	 */
	protected function getExpectedDatabaseSchema() {
		/** @var \TYPO3\CMS\Install\Sql\SchemaMigrator $schemaMigrator */
		$schemaMigrator = $this->objectManager->get('TYPO3\\CMS\\Install\\Sql\\SchemaMigrator');
		// Raw concatenated ext_tables.sql and friends string
		$expectedSchemaString = $this->getTablesDefinitionString();
		// Remove comments
		$cleanedExpectedSchemaString = implode(LF, $schemaMigrator->getStatementArray($expectedSchemaString, TRUE, '^CREATE TABLE '));
		$expectedSchema = $schemaMigrator->getFieldDefinitions_fileContent($cleanedExpectedSchemaString);
		return $expectedSchema;
	}

	/**
	 * Cycle through all loaded extensions and get full table definitions as concatenated string
	 *
	 * @return string Concatenated SQL of loaded extensions ext_tables.sql
	 */
	protected function getTablesDefinitionString() {
		$sqlString = array();

		// Find all ext_tables.sql of loaded extensions
		$loadedExtensionInformation = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::loadTypo3LoadedExtensionInformation(FALSE);
		foreach ($loadedExtensionInformation as $extensionKey => $extensionConfiguration) {
			if (is_array($extensionConfiguration) && $extensionConfiguration['ext_tables.sql']) {
				$sqlString[] = GeneralUtility::getUrl($extensionConfiguration['ext_tables.sql']);
			}
		}

		// Add caching framework sql definition
		$sqlString[] = $this->getCachingFrameworkRequiredDatabaseSchema();

		// Add category registry sql definition
		$sqlString[] = \TYPO3\CMS\Core\Category\CategoryRegistry::getInstance()->getDatabaseTableDefinitions();

		return implode(LF . LF . LF . LF, $sqlString);
	}
}
?>
