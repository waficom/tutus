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
if(!isset($_SESSION))
{
	session_name('UntuSoft');
	session_start();
	session_cache_limiter('private');
}

class Crypt
{
	public static function encrypt($text)
	{
	    return trim(
		    base64_encode(
			    mcrypt_encrypt(
				    MCRYPT_RIJNDAEL_256,
				    $_SESSION['site']['AESkey'],
				    $text,
				    MCRYPT_MODE_ECB,
				    mcrypt_create_iv(
					    mcrypt_get_iv_size(
						    MCRYPT_RIJNDAEL_256,
						    MCRYPT_MODE_ECB
					    ),
					    MCRYPT_RAND)
			    )
		    )
	    );
	}

	public static function decrypt($text)
	{
	    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $_SESSION['site']['AESkey'], base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}
}
