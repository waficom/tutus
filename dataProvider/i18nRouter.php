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
class i18nRouter {
	// -----------------------------------------------------------------------
	// Get the translation file
	// An array made by http://transifex.net/
	// GaiaEHR Project
	// -----------------------------------------------------------------------
	public static function getTranslation(){
		$hasModuleLocales = false;

		if(isset($_SESSION['site']['localization'])){
			$loc = $_SESSION['site']['localization'];
		} elseif(isset($_SESSION['site']['default_localization'])){
			$loc = $_SESSION['site']['default_localization'];
		} else{
			$loc = false;
		}
		// This language file is need by default.
		include(dirname(dirname(__FILE__)) . '/langs/en_US.php');
		$en_US = $LANG;

		if(isset($_SESSION['site']['modules'])){
			foreach($_SESSION['site']['modules'] AS $module){
				if(isset($module['locales'])){
					$hasModuleLocales = true;
					$key = array_search('en_US', $module['locales']);
					if($key !== false){
						include($_SESSION['root'] . '/modules/' . $module['name'] . '/locales/en_US.php');
						$en_US = array_merge($en_US, $LANG);
					}
				}
			}
		}

		// This file will be called when the user or the administrator select
		// a different language. But the primary language will be called first.
		// So if some words are not translated by the selected language it can be
		// displayed by the original language.
		if($loc !== false){
			include(dirname(dirname(__FILE__)) . '/langs/' . $loc . '.php');
			$locale = array_merge($en_US, $LANG);

			if($hasModuleLocales){
				foreach($_SESSION['site']['modules'] AS $module){
					if(isset($module['locales'])){
						$key = array_search($loc, $module['locales']);
						if($key !== false){
							include(dirname(dirname(__FILE__)) . '/modules/' . $module['name'] . '/locales/' . $loc . '.php');
							$locale = array_merge($locale, $LANG);
						}
					}
				}
			}

			return $locale;
		} else{
			return $en_US;
		}
	}

	// -----------------------------------------------------------------------
	// This will loop through the langs directory and get
	// all the available languages for GaiaEHR
	// This function is consumed by the dropdown list.
	// Need more translations go to: https://www.transifex.com/projects/p/gaiaehr/
	// -----------------------------------------------------------------------
	public static function getAvailableLanguages(){
		$availableLanguages = array();
		if($handle = opendir(dirname(dirname(__FILE__)) . '/langs/')){
			while(false !== ($entry = readdir($handle))){
				if($entry != '.' && $entry != '..'){
					include_once(dirname(dirname(__FILE__)) . '/langs/' . $entry);
					$languageContent['code'] = @key($LANG);
					$languageContent['description'] = @current($LANG);
					$availableLanguages[] = $languageContent;
					$LANG = NULL;
				}
			}
			closedir($handle);
		}
		return $availableLanguages;
	}

	// -----------------------------------------------------------------------
	// Get the default language
	// -----------------------------------------------------------------------
	public static function getDefaultLanguage(){
		if(isset($_SESSION['site']['default_localization'])){
			return $_SESSION['site']['default_localization'];
		} else{
			return 'en_US';
		}
	}

	// -----------------------------------------------------------------------
	// this function will look for the translation, if none found will return the key
	// -----------------------------------------------------------------------
	public static function t($key){
		$lang = self::getTranslation();
		return (array_key_exists($key, $lang) ? $lang[$key] : $key);
	}

}

//	print_r(i18nRouter::getTranslation());
