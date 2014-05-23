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

class SocialHistory {

	/**
	 * @var MatchaCUP
	 */
	private $s = null;


	/**
	 * Set Model App.model.patient.PatientsOrders
	 */
	private function setModel(){
		if(!isset($this->s))
			$this->s = MatchaModel::setSenchaModel('App.model.patient.PatientSocialHistory');
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function getSocialHistories($params){
		$this->setModel();
		return $this->s->load($params)->all();
	}
	/**
	 * @param $params
	 * @return array
	 */
	public function getSocialHistory($params){
		$this->setModel();
		return $this->s->load($params)->one();
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function addSocialHistory($params){
		$this->setModel();
		return $this->s->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function updateSocialHistory($params){
		$this->setModel();
		return $this->s->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function destroySocialHistory($params){
		$this->setModel();
		return $this->s->destroy($params);
	}


}
