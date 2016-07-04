<?php
/**
 * File: $Id: updateexport.php,v 1.2 2005/08/22 06:29:41 garrett Exp $
 *
 * AddressBook admin function
 *
 * @package Xaraya eXtensible Management System
 * @copyright (C) 2003 by the Xaraya Development Team
 * @license GPL <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.xaraya.com
 *
 * @subpackage AddressBook Module
 * @author Garrett Hunter <garrett@blacktower.com>
 */
/**
 * update the label information used in the contact form
 *
 * @param passed in from modifyexport api
 * @return bool
 * @raise BAD_PARAM, NO_PERMISSION, DATABASE_ERROR
 */
function addressbook_adminapi_updateexport($args)
{

    // var defines
    /**
     * Security check
     */
    if (!xarSecurityCheck('AdminAddressBook',0)) return FALSE;
    extract($args);

    // Return
    return TRUE;
} // END updateexport
?>