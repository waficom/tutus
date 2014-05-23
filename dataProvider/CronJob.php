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

include_once (dirname(__FILE__) . '/../classes/Sessions.php');


class CronJob
{

	function run()
	{
		/**
		 * only run cron if delay time has expired
		 */
		 error_reporting(-1);
		if ((time() - $_SESSION['cron']['time']) > $_SESSION['cron']['delay'] || $_SESSION['inactive']['start'])
		{
			/**
			 * stuff to run
			 */
			$s = new Sessions();
//			$p = new Patient();

			foreach ($s->logoutInactiveUsers() as $user)
			{
//				$p -> patientChartInByUserId($user['uid']);
			}

            /**
             * set cron start to false reset cron time to current time
             */
            $_SESSION['inactive']['start'] = false;
            $_SESSION['cron']['time'] = time();
            return array(
                'success' => true,
                'ran' => true
            );
        }
        return array(
            'success' => true,
            'ran' => false
        );
    }

}

//$foo = new CronJob();
//print '<pre>';
//$foo->run();
