<?php
/**
 * File: $Id: getmenulinks.php,v 1.4 2005/08/22 06:29:41 garrett Exp $
 *
 * AddressBook admin getMenuLinks
 *
 * @package Xaraya eXtensible Management System
 * @copyright (C) 2003 by the Xaraya Development Team
 * @license GPL <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.xaraya.com
 *
 * @subpackage AddressBook Module
 * @author Garrett Hunter <garrett.hunter@blacktower.com>
 */

/**
 * builds an array of menulinks for display in a menu block
 *
 * @return array of menu links
 */
function addressbook_adminapi_getmenulinks()
{
    $menulinks = array();

    if (xarSecurityCheck('AdminAddressBook',0)) {
        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                   'admin',
                                                   'modifyconfig'),
                              'title' => xarML('Modify the settings for the module.'),
                              'label' => xarML('Modify Config'));

        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                   'admin',
                                                   'modifycategories'),
                              'title' => xarML('Modify categories for the module.'),
                              'label' => xarML('Categories'));

        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                   'admin',
                                                   'modifylabels'),
                              'title' => xarML('Modify module labels used to describe data fields'),
                              'label' => xarML('Contact Labels'));

        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                  'admin',
                                                  'modifyprefixes'),
                              'title' => xarML('Modify contact prefix labels (Mr. / Mrs. / etc.)'),
                              'label' => xarML('Name Prefixes'));

		$useCustomFields = xarModGetVar(__ADDRESSBOOK__,'useCustomFields');
		if ($useCustomFields) {
	        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
    	                                              'admin',
        	                                          'modifycustomfields'),
            	                  'title' => xarML('Modify custom fields'),
                	              'label' => xarML('Custom Fields'));
		}

        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                  'admin',
                                                  'modifyexport'),
                              'title' => xarML('Select which fields to include in the export'),
                              'label' => xarML('Export Config'));

        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                  'admin',
                                                  'displaydocs'),
                              'title' => xarML('Administrator Documentation'),
                              'label' => xarML('Admin Docs'));
    }

    return $menulinks;

} // END getMenuLinks

?>