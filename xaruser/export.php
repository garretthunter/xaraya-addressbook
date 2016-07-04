<?php
/**
 * File: $Id: export.php,v 1.1 2005/03/29 07:17:29 garrett Exp $
 *
 * AddressBook user viewAll
 *
 * @package Xaraya eXtensible Management System
 * @copyright (C) 2003 by the Xaraya Development Team
 * @license GPL <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.xaraya.com
 *
 * @subpackage AddressBook Module
 * @author Garrett Hunter <garrett@blacktower.com>
 * Based on pnAddressBook by Thomas Smiatek <thomas@smiatek.com>
 */

/**
 * Export configuration page
 *
 * @return array of menu links
 */
function addressbook_user_export()
{
    $output = array();

    /**
     * Security check first
     */
    if (xarSecurityCheck('AdminAddressBook',0)) {

        xarModAPIFunc(__ADDRESSBOOK__,'user','export');

    }

    return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));

} // END export

?>