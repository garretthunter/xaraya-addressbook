<?php
/**
 * File: $Id: getmenulinks.php,v 1.5 2005/08/22 06:29:42 garrett Exp $
 *
 * AddressBook user getMenuLinks
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
function addressbook_userapi_getmenulinks()
{
    $menulinks = array();

    if (xarSecurityCheck('ReadAddressBook',0)) {
        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                   'user',
                                                   'viewall'),
                              'title' => xarML('View address book entries'),
                              'label' => xarML('View Addresses'));
    }

    if (xarSecurityCheck('AddAddressBook',0)) {
        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                   'user',
                                                   'insertedit'),
                              'title' => xarML('Add a new address'),
                              'label' => xarML('New Address'));
    }

    if (xarSecurityCheck('ReadAddressBook',0)) {
        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                   'user',
                                                   'export'),
                             'title' => xarML('Export address book entries'),
                             'label' => xarML('Export Addresses'));
    }

    if (xarSecurityCheck('AdminAddressBook',0)) {
        $menulinks[] = Array('url'   => xarModURL(__ADDRESSBOOK__,
                                                   'user',
                                                   'export'),
                             'title' => xarML('You see this becuase you are ADMIN'),
                             'label' => xarML('TEST ADMIN LINK'));
    }

    return $menulinks;

} // END getMenuLinks

?>