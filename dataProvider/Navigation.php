<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once(dirname(__FILE__) . '/ACL.php');
include_once(dirname(__FILE__) . '/i18nRouter.php');

class Navigation
{
	/**
	 * @var \ACL
	 */
	private $ACL;
	private $i18n;
	private $t;

	function __construct()
	{
		$this->ACL = new ACL();
		$this->i18n = i18nRouter::getTranslation();
	}

	public function getNavigation()
	{
		// *************************************************************************************
		// Renders the items of the navigation panel
		// Default Nav Data
		// *************************************************************************************
		$nav = array(
			array(
				'text' => $this->i18n['dashboard'],
				'disabled' => !$this->ACL->hasPermission('access_dashboard'),
				'leaf' => true,
				'cls' => 'file',
				'iconCls' => 'icoDash',
				'id' => 'App.view.dashboard.Dashboard'
			),
			array(
				'text' => $this->i18n['calendar'],
				'disabled' => !$this->ACL->hasPermission('access_calendar'),
				'leaf' => true,
				'cls' => 'file',
				'iconCls' => 'icoCalendar',
				'id' => 'App.view.calendar.Calendar'
			),
/*
			array(
				'text' => $this->i18n['messages'],
				'disabled' => !$this->ACL->hasPermission('access_messages'),
				'leaf' => true,
				'cls' => 'file',
				'iconCls' => 'mail',
				'id' => 'App.view.messages.Messages'
			),
*/
//			array(
//				'text' => $this->i18n['patient_search'],
//				'disabled' => !$this->ACL->hasPermission('access_patient_search'),
//				'leaf' => true,
//				'cls' => 'file',
//				'iconCls' => 'searchUsers',
//				'id' => 'panelPatientSearch'
//			),
/*
			array(
				'text' => $this->i18n['area_floor_plan'],
				'disabled' => false,
				'leaf' => true,
				'cls' => 'file',
				'iconCls' => 'icoZoneAreas',
				'id' => 'App.view.areas.FloorPlan'
			),
			array(
				'text' => $this->i18n['patient_pool_areas'],
				'disabled' => false,
				'leaf' => true,
				'cls' => 'file',
				'iconCls' => 'icoPoolArea16',
				'id' => 'App.view.areas.PatientPoolDropZone'
			)
*/
		);
		// *************************************************************************************
		// Patient Folder
		// *************************************************************************************
/*
		$patient = array(
			'text' => $this->i18n['patient'],
			'cls' => 'folder',
			'expanded' => true,
			'iconCls' => 'icoLogo',
			'id' => 'patient'
		);
		if($this->ACL->hasPermission('add_patient')){
			$patient['children'][] = array(
				'text' => $this->i18n['new_patient'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.patient.NewPatient'
			);
		}
		if($this->ACL->hasPermission('access_patient_summary')){
			$patient['children'][] = array(
				'text' => $this->i18n['patient_summary'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.patient.Summary'
			);
		}
		if($this->ACL->hasPermission('access_patient_visits')){
			$patient['children'][] = array(
				'text' => $this->i18n['visits_history'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.patient.Visits'
			);
		}
		if($this->ACL->hasPermission('access_encounters')){
			$patient['children'][] = array(
				'text' => $this->i18n['encounter'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.patient.Encounter'
			);
		}
		if($this->ACL->hasPermission('access_visit_checkout')){
			$patient['children'][] = array(
				'text' => $this->i18n['visit_checkout'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.patient.VisitCheckout'
			);
		}
		array_push($nav, $patient);
*/
		// *************************************************************************************
		// Billing Manager Folder
		// *************************************************************************************
/*
        array_push($nav, array(
			'text' => $this->i18n['billing_manager'],
			'cls' => 'folder',
			'expanded' => true,
			'id' => 'billing',
			'iconCls' => 'icoLogo',
			'children' => array(
				array(
					'text' => $this->i18n['payment'],
					'leaf' => true,
					'cls' => 'file',
					'id' => 'App.view.fees.Payments'
				), array(
					'text' => $this->i18n['billing'],
					'leaf' => true,
					'cls' => 'file',
					'id' => 'App.view.fees.Billing'
				)
			)
		));
*/
		// *************************************************************************************
		// Administration Folder
		// *************************************************************************************
		$admin = array(
			'text' => $this->i18n['administration'],
			'cls' => 'folder',
			'expanded' => true,
			'iconCls' => 'icoLogo',
			'id' => 'administration'
		);
		if($this->ACL->hasPermission('access_gloabal_settings')){
			$admin['children'][] = array(
				'text' => $this->i18n['global_settings'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Globals'
			);
		}
/*
		if($this->ACL->hasPermission('access_facilities')){
			$admin['children'][] = array(
				'text' => $this->i18n['facilities'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Facilities'
			);
		}
*/
		if($this->ACL->hasPermission('access_users')){
			$admin['children'][] = array(
				'text' => $this->i18n['users'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Users'
			);
		}
/*
		if($this->ACL->hasPermission('access_practice')){
			$admin['children'][] = array(
				'text' => $this->i18n['practice'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Practice'
			);
		}
		if($this->ACL->hasPermission('access_data_manager')){
			$admin['children'][] = array(
				'text' => $this->i18n['data_manager'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.DataManager'
			);
		}
		if($this->ACL->hasPermission('access_preventive_care')){
			$admin['children'][] = array(
				'text' => $this->i18n['preventive_care'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.PreventiveCare'
			);
		}
*/
//		if($this->ACL->hasPermission('access_medications')){
//			$admin['children'][] = array(
//				'text' => $this->i18n['medications'], 'leaf' => true, 'cls' => 'file', 'id' => 'panelMedications'
//			);
//		}
/*
		if($this->ACL->hasPermission('access_floor_plans')){
			$admin['children'][] = array(
				'text' => $this->i18n['floor_areas'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.FloorPlans'
			);
		}
*/
		if($this->ACL->hasPermission('access_roles')){
			$admin['children'][] = array(
				'text' => $this->i18n['roles'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Roles'
			);
		}
/*
		if($this->ACL->hasPermission('access_layouts')){
			$admin['children'][] = array(
				'text' => $this->i18n['layouts'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Layout'
			);
		}
		if($this->ACL->hasPermission('access_lists')){
			$admin['children'][] = array(
				'text' => $this->i18n['lists'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Lists'
			);
		}
*/
		if($this->ACL->hasPermission('access_event_log')){
			$admin['children'][] = array(
				'text' => $this->i18n['event_log'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Log'
			);
		}
/*
		if($this->ACL->hasPermission('access_documents')){
			$admin['children'][] = array(
				'text' => $this->i18n['documents'],
				'leaf' => true,
				'cls' => 'file',
				'id' => 'App.view.administration.Documents'
			);
		}
		//if($this->ACL->hasPermission('access_documents')){
		$admin['children'][] = array(
			'text' => $this->i18n['external_data_loads'],
			'leaf' => true,
			'cls' => 'file',
			'id' => 'App.view.administration.ExternalDataLoads'
		);
		//if($this->ACL->hasPermission('access_documents')){
		$admin['children'][] = array(
			'text' => $this->i18n['applications'],
			'leaf' => true,
			'cls' => 'file',
			'id' => 'App.view.administration.Applications'
		);
		//}
*/
		//if($this->ACL->hasPermission('access_documents')){
		$admin['children'][] = array(
			'text' => $this->i18n['modules'],
			'leaf' => true,
			'cls' => 'file',
			'id' => 'App.view.administration.Modules'
		);
/*
		$admin['children'][] = array(
			'text' => $this->i18n['encryption'],
			'leaf' => true,
			'cls' => 'file',
			'id' => 'App.view.administration.Encryption'
		);
*/
		//}
		if($this->ACL->hasPermission('access_gloabal_settings') ||
//			$this->ACL->hasPermission('access_facilities') ||
			$this->ACL->hasPermission('access_users') ||
//			$this->ACL->hasPermission('access_practice') ||
//			$this->ACL->hasPermission('access_services') ||
//			$this->ACL->hasPermission('access_medications') ||
//			$this->ACL->hasPermission('access_floor_plans') ||
			$this->ACL->hasPermission('access_roles') ||
//			$this->ACL->hasPermission('access_layouts') ||
//			$this->ACL->hasPermission('access_lists') ||
			$this->ACL->hasPermission('access_event_log')) array_push($nav, $admin);
		// *************************************************************************************
		// Miscellaneous Folder
		// *************************************************************************************
		array_push($nav, array(
			'text' => $this->i18n['miscellaneous'],
			'cls' => 'folder',
			'expanded' => false,
			'id' => 'miscellaneous',
			'iconCls' => 'icoLogo',
			'children' => array(
/*
				array(
					'text' => $this->i18n['web_search'],
					'leaf' => true,
					'cls' => 'file',
					'id' => 'App.view.miscellaneous.Websearch'
				),
				array(
					'text' => $this->i18n['address_book'],
					'leaf' => true,
					'cls' => 'file',
					'id' => 'App.view.miscellaneous.AddressBook'
				),
*/
				array(
					'text' => $this->i18n['office_notes'],
					'leaf' => true,
					'cls' => 'file',
					'id' => 'App.view.miscellaneous.OfficeNotes'
				),
//				array(
//					'text' => $this->i18n['my_settings'],
//					'leaf' => true,
//					'cls' => 'file',
//					'id' => 'App.view.miscellaneous.MySettings'
//				),
				array(
					'text' => $this->i18n['my_account'],
					'leaf' => true,
					'cls' => 'file',
					'id' => 'App.view.miscellaneous.MyAccount'
				)
			)
		));
		return $nav;

	}

}
