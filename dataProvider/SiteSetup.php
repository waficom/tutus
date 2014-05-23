<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

error_reporting(E_ALL);
include_once (dirname(dirname(__FILE__)) . '/classes/FileManager.php');
include_once (dirname(dirname(__FILE__)) . '/dataProvider/ACL.php');

class SiteSetup
{
	/**
	 * @var PDO
	 */
	private $conn;
	private $err;

	function __construct()
	{
		chdir($_SESSION['root']);
	}

	/*
	 * Verify: checkDatabaseCredentials
	 */
	public function checkDatabaseCredentials(stdClass $params)
	{
		if(isset($params->rootUser)){
			$success = $this->rootDatabaseConn($params->dbHost, $params->dbPort, $params->rootUser, $params->rootPass);
			if($success && $this->conn->query("USE $params->dbName") !== false){
				return array(
					'success' => false, 'error' => 'Database name is used. Please, use a different Database name'
				);
			}
		} else {
			$success = $this->databaseConn($params->dbHost, $params->dbPort, $params->dbName, $params->dbUser, $params->dbPass);
		}
		if($success){
			$maxAllowPacket  = $this->setMaxAllowedPacket();
			if($maxAllowPacket !== true){
				return array(
					'success' => false, 'error' => 'Could not set the MySQL <strong>max_allowed_packet</strong> variable.<br>GaiaEHR requires to set max_allowed_packet to 50M or more.<br>Please check my.cnf or my.ini, also you can install GaiaEHR using MySQL root user<br>max_allowed_packet = '.$maxAllowPacket
				);
			}
			return array(
				'success' => true, 'dbInfo' => $params
			);
		} else {
			return array(
				'success' => false, 'error' => 'Could not connect to sql server!<br>Please, check database information and try again'
			);
		}
	}

	function setMaxAllowedPacket()
	{
		$stm = $this->conn->query("SELECT @@global.max_allowed_packet AS size");
		$pkg = $stm->fetch(PDO::FETCH_ASSOC);
		if($pkg['size'] < 209715200){
			$this->conn->exec("SET @@global.max_allowed_packet = 52428800000");
			$error = $this->conn->errorInfo();
			if(isset($error[2])){
				return $pkg['size'];
			} else {
				return true;
			}
		}
		return true;
	}

	function databaseConn($host, $port, $dbName, $dbUser, $dbPass)
	{
		try{
			$this->conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $dbUser, $dbPass, array(
				PDO::MYSQL_ATTR_LOCAL_INFILE => 1, PDO::ATTR_PERSISTENT => true, PDO::ATTR_TIMEOUT => 7600
			));
			return true;
		} catch(PDOException $e){
			$this->err = $e->getMessage();
			return false;
		}
	}

	/*
	 * Make a connection to the database: rootDatabaseConn
	 * We use PDO to make the connection, PDO is a internal library of PHP that make
	 * connections
	 * to any database please refer to MatchaHelper.php to learn more about PHP PDO.
	 */
	public function rootDatabaseConn($host, $port, $rootUser, $rootPass)
	{
		try{
			$this->conn = new PDO("mysql:host=$host;port=$port", $rootUser, $rootPass);
			return true;
		} catch(PDOException $e){
			$this->err = $e->getMessage();
			return false;
		}
	}

	/*
	 * Verify: checkRequirements
	 */
	public function checkRequirements()
	{
		$row = array();
		// check if ...
		$status = (empty($_SESSION['sites']['sites']) ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'GaiaEHR is not installed', 'status' => $status
		);
		// verified that php 5.2.0 or later is installed
		$status = (version_compare(phpversion(), '5.3.2', '>=') ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'PHP 5.3.2 + installed', 'status' => $status
		);
		// Check if get_magic_quotes_gpc is off
		$status = (get_magic_quotes_gpc() != 1 ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'get_magic_quotes_gpc off/disabled', 'status' => $status
		);
		// try chmod sites folder and check chmod after that
		$status = (chmod('sites', 0755) ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'Sites dir writable by Web Server', 'status' => $status
		);
		// check if safe_mode is off
		$status = (!ini_get('safe_mode') ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'PHP safe mode off', 'status' => $status
		);
		// check if PDO
		$status = (class_exists('PDO') ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'PHP class PDO', 'status' => $status
		);
		// check if ZipArchive is enable
		$status = (function_exists("gzcompress") ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'PHP class zlib', 'status' => $status
		);
		// check if ZipArchive is enable
		$status = (function_exists('curl_version') ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'PHP Curl installed', 'status' => $status
		);
		// check for mcrypt is installed in PHP
		$status = (function_exists('mcrypt_encrypt') ? 'Ok' : 'Fail');
		$row[]  = array(
			'msg' => 'PHP MCrypt installed', 'status' => $status
		);
        // check if PDO object exists
        $status = (defined('PDO::ATTR_DRIVER_NAME') ? 'Ok' : 'Fail');
        $row[]  = array(
            'msg' => 'PDO installed', 'status' => $status
        );
		return $row;
	}

	public function setSiteDirBySiteId($siteId)
	{
		$siteDir = "sites/$siteId";
		if(!file_exists($siteDir)){
			if(mkdir($siteDir, 0755, true)){
				if(chmod($siteDir, 0755)){
					if((mkdir("$siteDir/patients", 0755, true) && chmod("$siteDir/patients", 0755)) && (mkdir("$siteDir/documents", 0755, true) && chmod("$siteDir/documents", 0755)) && (mkdir("$siteDir/temp", 0755, true) && chmod("$siteDir/temp", 0755)) && (mkdir("$siteDir/trash", 0755, true) && chmod("$siteDir/trash", 0755))){
						return array('success' => true);
					} else {
						return array(
							'success' => false, 'error' => 'Something went wrong creating site sub directories'
						);
					}
				} else {
					return array(
						'success' => false, 'error' => 'Unable to set "/sites/' . $siteId . '" write permissions,<br>Please, check "/sites/' . $siteId . '" directory write permissions'
					);
				}
			} else {
				return array(
					'success' => false, 'error' => 'Unable to create Site directory,<br>Please, check "/sites" directory write permissions'
				);
			}
		} else {
			return array(
				'success' => false, 'error' => 'Site ID already in use.<br>Please, choose another Site ID'
			);
		}
	}

	public function createDatabaseStructure(stdClass $params)
	{
		if(isset($params->rootUser) && $this->rootDatabaseConn($params->dbHost, $params->dbPort, $params->rootUser, $params->rootPass)){
			$this->conn->exec("CREATE DATABASE $params->dbName;");
			$this->conn->exec("CREATE USER '$params->dbUser'@'$params->dbHost' IDENTIFIED BY '$params->dbPass'");
			$this->conn->exec("GRANT USAGE ON * . * TO  '$params->dbUser'@'$params->dbHost' IDENTIFIED BY  $params->dbPass' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0");
			$this->conn->exec("GRANT ALL PRIVILEGES ON $params->dbName.* TO '$params->dbUser'@'$params->dbHost' WITH GRANT OPTION");
			if($this->databaseConn($params->dbHost, $params->dbPort, $params->dbName, $params->dbUser, $params->dbPass)){
				if($this->loadDatabaseStructure()){
					return array('success' => true);
				} else {
					FileManager::rmdir_recursive("sites/$params->siteId");
					return array(
						'success' => false, 'error' => $this->conn->errorInfo()
					);
				}
			} else {
				FileManager::rmdir_recursive("sites/$params->siteId");
				return array(
					'success' => false, 'error' => $this->err
				);
			}
		} elseif($this->databaseConn($params->dbHost, $params->dbPort, $params->dbName, $params->dbUser, $params->dbPass)) {
			if($this->loadDatabaseStructure()){
				return array('success' => true);
			} else {
				FileManager::rmdir_recursive("sites/$params->siteId");
				return array(
					'success' => false, 'error' => $this->conn->errorInfo()
				);
			}
		} else {
			FileManager::rmdir_recursive("sites/$params->siteId");
			return array(
				'success' => false, 'error' => $this->err
			);
		}
	}

	public function loadDatabaseStructure()
	{
        ini_set('memory_limit', '-1');
		if(file_exists($sqlFile = 'sql/gaiadb_install_structure.sql')){
			$query = file_get_contents($sqlFile);
			if($this->conn->query($query) !== false){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function loadDatabaseData(stdClass $params)
	{
        ini_set('memory_limit', '-1');
		if($this->databaseConn($params->dbHost, $params->dbPort, $params->dbName, $params->dbUser, $params->dbPass)){
            if(file_exists($sqlFile = 'sql/gaiadb_install_data.sql')){
                $query = file_get_contents($sqlFile);
				if($this->conn->query($query) !== false){
					return array('success' => true);
				} else {
                    FileManager::rmdir_recursive("sites/$params->siteId");
                    if(isset($params->rootUser)) $this->dropDatabase($params->dbName);
                    return array('success' => false, 'error' => $this->conn->errorInfo());
                }

            } else {
				FileManager::rmdir_recursive("sites/$params->siteId");
				if(isset($params->rootUser)) $this->dropDatabase($params->dbName);
				return array(
					'success' => false, 'error' => 'Unable find installation data file'
				);
			}
		} else {
			FileManager::rmdir_recursive("sites/$params->siteId");
			return array(
				'success' => false, 'error' => $this->err
			);
		}
	}

	function dropDatabase($dbName)
	{
		$this->conn->exec("DROP DATABASE $dbName");
	}

	public function createSConfigurationFile($params)
	{
		if(file_exists($conf = 'sites/conf.example.php')){
			if(($params->AESkey = ACL::createRandomKey()) !== false){
				$buffer    = file_get_contents($conf);
				$search    = array(
					'#host#',
                    '#user#',
                    '#pass#',
                    '#db#',
                    '#port#',
                    '#key#',
                    '#lang#',
                    '#theme#',
                    '#timezone#',
                    '#sitename#',
                    '#hl7Port#'
				);
				$replace   = array(
					$params->dbHost,
                    $params->dbUser,
                    $params->dbPass,
                    $params->dbName,
                    $params->dbPort,
                    $params->AESkey,
                    $params->lang,
                    $params->theme,
                    $params->timezone,
                    $params->siteId,
					9100 // TODO check other sites and +1 to the highest port
				);
				$newConf   = str_replace($search, $replace, $buffer);
				$siteDir   = "sites/$params->siteId";
				$conf_file = ("$siteDir/conf.php");
				$handle    = fopen($conf_file, 'w');
				fwrite($handle, $newConf);
				fclose($handle);
				chmod($conf_file, 0644);
				if(file_exists($conf_file)){
					return array(
						'success' => true, 'AESkey' => $params->AESkey
					);
				} else {
					return array(
						'success' => false, 'error' => "Unable to create $siteDir/conf.php file"
					);
				}
			} else {
				return array(
					'success' => false, 'error' => 'Unable to Generate AES 32 bit key'
				);
			}
		} else {
			return array(
				'success' => false, 'error' => 'Unable to Find sites/conf.example.php'
			);
		}
	}

	public function createSiteAdmin($params)
	{
		include_once(dirname(dirname(__FILE__)). '/sites/' . $params->siteId . '/conf.php');
		include_once(dirname(dirname(__FILE__)) . '/dataProvider/User.php');
		$u                  = new User();
		$admin              = new stdClass();
		$admin->title       = 'Mr.';
		$admin->fname       = 'Administrator';
		$admin->mname       = '';
		$admin->lname       = 'Administrator';
		$admin->username    = $params->adminUsername;
		$admin->password    = $params->adminPassword;
		$admin->authorized  = 1;
		$admin->active      = 1;
		$admin->role_id     = 1;
		$admin->facility_id = 1;
        $u->addUser($admin);
		return array('success' => true);
	}

	public function loadCode($code)
	{
		include_once (dirname(dirname(__FILE__)). '/dataProvider/ExternalDataUpdate.php');
		$codes            = new ExternalDataUpdate();
		$params           = new stdClass();
		$params->codeType = $code;
		$foo              = $codes->getCodeFiles($params);
		$params           = new stdClass();
		$params->codeType = $foo[0]['codeType'];
		$params->version  = $foo[0]['version'];
		$params->path     = $foo[0]['path'];
		$params->date     = $foo[0]['date'];
		$params->basename = $foo[0]['basename'];
		return $codes->updateCodes($params);
	}

}

//$s = new SiteSetup();
//print '<pre>';
//print_r($s->loadCode('ICD9'));
