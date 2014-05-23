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

if(!isset($_SESSION)){
	session_name('UntuSoft');
	session_start();
	session_cache_limiter('private');
}

require_once (dirname(__FILE__) . "/../classes/XMLParser.class.php");
//--------------------------------------------------------------------------------
// lets declare few vars for later use.
//--------------------------------------------------------------------------------
$args = '';
$count = 0;
$totals = 0;
if (isset($_REQUEST['type']))
{
	$_SESSION['search_type'] = $_REQUEST['type'];
}
$_REQUEST['q'] = urlencode($_REQUEST['q']);
//********************************************************************************
// lets check if the request is search request or if is a pager request.
// the pager does not pass the url.
//********************************************************************************
switch ($_SESSION['search_type'])
{
	case 'health_topics' :
		$baseUrl = 'http://wsearch.nlm.nih.gov/ws/query?db=healthTopics';
		if (isset($_REQUEST['type']))
		{
			//----------------------------------------------------------------------------
			// Search request!
			// lets use and store few arguments for pager requests if need it
			//----------------------------------------------------------------------------
			$args .= '&term=' . $_REQUEST['q'];
			$args .= '&retstart=' . $_REQUEST['retstart'];
			$args .= '&retmax=' . $_REQUEST['retmax'];
			$_SESSION['web_search_q'] = $_REQUEST['q'];
		}
		else
		{
			//----------------------------------------------------------------------------
			// Pager Request!
			// lets use a few session stored values.
			//----------------------------------------------------------------------------
			$args .= '&term=' . $_SESSION['web_search_q'];
			$args .= '&file=' . $_SESSION['web_search_file'];
			$args .= '&retstart=' . $_REQUEST['retstart'];
			$args .= '&retmax=' . $_REQUEST['retmax'];
		}
		break;
	default:
		if (isset($_REQUEST['type']))
		{
            if($_REQUEST['type'] == 'icd9cm') $codingSystem = '2.16.840.1.113883.6.103';
            if($_REQUEST['type'] == 'snomed') $codingSystem = '2.16.840.1.113883.6.96';
            if($_REQUEST['type'] == 'rxcui') $codingSystem = '2.16.840.1.113883.6.88';
            if($_REQUEST['type'] == 'ndc') $codingSystem = '2.16.840.1.113883.6.69';
            if($_REQUEST['type'] == 'loinc') $codingSystem = '2.16.840.1.113883.6.1';
			$baseUrl = 'http://apps.nlm.nih.gov/medlineplus/services/mpconnect_service.cfm?mainSearchCriteria.v.cs=' . $codingSystem;

			if ($_REQUEST['term'] == 'term')
			{
				$args .= '&mainSearchCriteria.v.dn=' . strtolower($_REQUEST['q']);
			}
			else
			{
				$args .= '&mainSearchCriteria.v.c=' . strtolower($_REQUEST['q']);
			}
			$_SESSION['web_search_q'] = $_REQUEST['q'];

		}
		else
		{
			if (!is_numeric($_SESSION['web_search_q']))
			{
				$args .= '&mainSearchCriteria.v.dn=' . strtolower($_SESSION['web_search_q']);
			}
			else
			{
				$args .= '&mainSearchCriteria.v.c=' . strtolower($_SESSION['web_search_q']);
			}
		}
		break;
}
//********************************************************************************
// build the URL using the baseUrl and the appended arguments from the if/else
//********************************************************************************
$url = $baseUrl . $args;

//********************************************************************************
// XML parser... PFM!
//********************************************************************************
$xml = file_get_contents($url);
$parser = new XMLParser($xml);
$parser -> Parse();
$rows = array();

//--------------------------------------------------------------------------------
// get the total value form the xml
//--------------------------------------------------------------------------------
switch ($_SESSION['search_type'])
{
	case 'health_topics' :
		if (isset($parser -> document->list[0]->document))
		{
			$totals = $parser -> document -> count[0] -> tagData;
			//----------------------------------------------------------------------------
			// store file value for pager, if need it
			//----------------------------------------------------------------------------
			$_SESSION['web_search_file'] = $parser -> document -> file[0] -> tagData;
			//****************************************************************************
			// now lets work the xml file to push stuff into the $rows array()
			//****************************************************************************
			foreach ($parser->document->list[0]->document as $document)
			{
				foreach ($parser->document->list[0]->document[$count]->content as $content)
				{
					$item['id'] = ($count + 1);
					if ($content -> tagAttrs['name'] == 'title')
					{
						$item['title'] = $content -> tagData;
					}
					elseif ($content -> tagAttrs['name'] == 'organizationName')
					{
						$item['source'] = $content -> tagData;
					}
					elseif ($content -> tagAttrs['name'] == 'FullSummary')
					{
						$item['FullSummary'] = $content -> tagData;
					}
					elseif ($content -> tagAttrs['name'] == 'snippet')
					{
						$item['snippet'] = $content -> tagData;
					}
					array_push($rows, $item);
				}
				$count++;
			}
		}
		break;
    case 'snomed';
    case 'rxcui';
    case 'loinc';
    case 'ndc';
	case 'icd9cm':
		$item['source'] = $parser->document->author[0]->name[0]->tagData;
		//--------------------------------------------------------------------------------
		// get the total value form the xml
		//--------------------------------------------------------------------------------
		if (isset($parser->document->entry))
		{
			//****************************************************************************
			// now lets work the xml file to push stuff into the $rows array()
			//****************************************************************************
			foreach ($parser->document->entry as $document)
			{
				$item['title'] = $document->title[0]->tagData;
				$item['snippet'] = substr($document->summary[0]->tagData, 0, 400) . '...';
				$item['FullSummary'] = $document -> summary[0]->tagData;
				array_push($rows, $item);
				$count++;
			}
		}
		$totals = $count;
		break;
}

//********************************************************************************
// lets print the json for sencha
//********************************************************************************
print_r(json_encode(array(
	'totals' => $totals,
	'row' => $rows
)));