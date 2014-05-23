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

$API = array(
	'Modules' => array(
		'methods' => array(
			'getAllModules' => array(
				'len' => 0
			),
			'getActiveModules' => array(
				'len' => 0
			),
			'getEnabledModules' => array(
				'len' => 0
			),
			'getDisabledModules' => array(
				'len' => 0
			),
			'getModuleByName' => array(
				'len' => 1
			),
			'updateModule' => array(
				'len' => 1
			)
		)
	),
	'Calendar' => array(
		'methods' => array(
			'getCalendars' => array(
				'len' => 0
			),
			'getEvents' => array(
				'len' => 1
			),
			'addEvent' => array(
				'len' => 1
			),
			'updateEvent' => array(
				'len' => 1
			),
			'deleteEvent' => array(
				'len' => 1
			),
			'getPatientFutureEvents' => array(
				'len' => 1
			),
		)
	),
	'Messages' => array(
		'methods' => array(
			'getMessages' => array(
				'len' => 1
			),
			'deleteMessage' => array(
				'len' => 1
			),
			'sendNewMessage' => array(
				'len' => 1
			),
			'replyMessage' => array(
				'len' => 1
			),
			'updateMessage' => array(
				'len' => 1
			)
		)
	),
	'Globals' => array(
		'methods' => array(
			'setGlobals' => array(
				'len' => 0
			),
			'getGlobals' => array(
				'len' => 0
			),
			'getAllGlobals' => array(
				'len' => 0
			),
			'updateGlobals' => array(
				'len' => 1
			)
		)
	),
	'DataManager' => array(
		'methods' => array(
			'getServices' => array(
				'len' => 1),
			'addService' => array(
				'len' => 1
			),
			'updateService' => array(
				'len' => 1
			),
			'liveCodeSearch' => array(
				'len' => 1
			),
			'getCptCodes' => array(
				'len' => 1
			),
			'addCptCode' => array(
				'len' => 1
			),
			'updateCptCode' => array(
				'len' => 1
			),
			'deleteCptCode' => array(
				'len' => 1
			),
			'getActiveProblems' => array(
				'len' => 1
			),
			'addActiveProblems' => array(
				'len' => 1
			),
			'removeActiveProblems' => array(
				'len' => 1
			),
			'getMedications' => array(
				'len' => 1
			),
			'addMedications' => array(
				'len' => 1
			),
			'removeMedications' => array(
				'len' => 1
			),
			'updateMedications' => array(
				'len' => 1
			),
			'getAllLabObservations' => array(
				'len' => 1
			),
			'getLabObservations' => array(
				'len' => 1
			),
			'addLabObservation' => array(
				'len' => 1
			),
			'updateLabObservation' => array(
				'len' => 1
			),
			'removeLabObservation' => array(
				'len' => 1
			),
			'getActiveLaboratoryTypes' => array(
				'len' => 0
			)
		)
	),
	/**
	 * User Functions
	 */
	'User' => array(
		'methods' => array(
			'getUsers' => array(
				'len' => 1
			),
			'getUser' => array(
				'len' => 1
			),
			'addUser' => array(
				'len' => 1
			),
			'updateUser' => array(
				'len' => 1
			),
			'updatePassword' => array(
				'len' => 1
			),
			'usernameExist' => array(
				'len' => 1
			),
			'getCurrentUserData' => array(
				'len' => 0
			),
			'getCurrentUserBasicData' => array(
				'len' => 0
			),
			'updateMyAccount' => array(
				'len' => 1
			),
			'verifyUserPass' => array(
				'len' => 1
			),
			'getProviders' => array(
				'len' => 0
			),
			'getActiveProviders' => array(
				'len' => 0
			),
			'getUserFullNameById' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Authorization Procedures Functions
	 */
	'authProcedures' => array(
		'methods' => array(
			'login' => array(
				'len' => 1
			),
			'ckAuth' => array(
				'len' => 0
			),
			'unAuth' => array(
				'len' => 0
			),
			'getSites' => array(
				'len' => 0
			)
		)
	),
	/**
	 * Navigation Function
	 */
	'Navigation' => array(
		'methods' => array(
			'getNavigation' => array(
				'len' => 0
			)
		)
	),
	/**
	 * Navigation Function
	 */
	'Roles' => array(
		'methods' => array(
			'getRolePerms' => array(
				'len' => 0
			),
			'updateRolePerm' => array(
				'len' => 1
			),
			'getRolesData' => array(
				'len' => 0
			),
			'saveRolesData' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Navigation Function
	 */
	'ACL' => array(
		'methods' => array(
			'getAllUserPermsAccess' => array(
				'len' => 0
			),
			'hasPermission' => array(
				'len' => 1
			),
			'emergencyAccess' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Navigation Function
	 */
	'AuditLog' => array(
		'methods' => array(
			'getLogs' => array(
				'len' => 1
			),
            'setLog' => array(
                'len' => 1
            )
		)
	),
	'Documents' => array(
		'methods' => array(
			'updateDocumentsTitle' => array(
				'len' => 1
			)
		)
	),
	/**
	 * Document Handler functions
	 */
	'DocumentHandler' => array(
		'methods' => array(
			'uploadDocument' => array(
				'formHandler' => true,
				'len' => 1
			),
			'getDocumentsTemplates' => array(
				'len' => 1
			),
			'addDocumentsTemplates' => array(
				'len' => 1
			),
			'updateDocumentsTemplates' => array(
				'len' => 1
			),
			'getHeadersAndFootersTemplates' => array(
				'len' => 1
			),
			'getDefaultDocumentsTemplates' => array(
				'len' => 1
			),
			'createDocument' => array(
				'len' => 1
			),
			'createDocumentDoctorsNote' => array(
				'len' => 1
			),
			'checkDocHash' => array(
				'len' => 1
			)
		)
	),
	'File' => array(
		'methods' => array(
			'savePatientBase64Document' => array(
				'len' => 1
			)
		)
	),
	'CronJob' => array(
		'methods' => array(
			'run' => array(
				'len' => 0
			)
		)
	),
	'i18nRouter' => array(
		'methods' => array(
			'getTranslation' => array(
				'len' => 0
			),
			'getDefaultLanguage' => array(
				'len' => 0
			),
			'getAvailableLanguages' => array(
				'len' => 0
			)
		)
	),
	'SiteSetup' => array(
		'methods' => array(
			'checkDatabaseCredentials' => array(
				'len' => 1
			),
			'checkRequirements' => array(
				'len' => 0
			),
			'setSiteDirBySiteId' => array(
				'len' => 1
			),
			'createDatabaseStructure' => array(
				'len' => 1
			),
			'loadDatabaseData' => array(
				'len' => 1
			),
			'createSiteAdmin' => array(
				'len' => 1
			),
			'createSConfigurationFile' => array(
				'len' => 1
			),
			'loadCode' => array(
				'len' => 1
			)
		)
	),
	'Applications' => array(
		'methods' => array(
			'getApplications' => array(
				'len' => 1
			),
			'addApplication' => array(
				'len' => 1
			),
			'updateApplication' => array(
				'len' => 1
			),
			'deleteApplication' => array(
				'len' => 1
			)
		)
	),
	'HL7Messages' => array(
		'methods' => array(
			'getMessages' => array(
				'len' => 1
			),
			'getMessage' => array(
				'len' => 1
			),
			'getMessageById' => array(
				'len' => 1
			),
			'getRecipients' => array(
				'len' => 1
			),
			'sendVXU' => array(
				'len' => 1
			)
		)
	),
	'Encryption' => array(
		'methods' => array(
			'Encrypt' => array(
				'len' => 1
			),
			'Decrypt' => array(
				'len' => 1
			)
		)
	),
	'Test' => array(
		'methods' => array(
			't1' => array(
				'len' => 0
			),
			't2' => array(
				'len' => 1
			)
		)
	)
);
