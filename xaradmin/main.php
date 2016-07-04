<?php
/**
 * File: $Id: main.php,v 1.2 2004/03/28 23:22:23 garrett Exp $
 *
 * AddressBook admin functions
 *
 * @package Xaraya eXtensible Management System
 * @copyright (C) 2003 by the Xaraya Development Team
 * @license GPL <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.xaraya.com
 *
 * @subpackage AddressBook Module
 * @author Garrett Hunter <garrett.hunter@blacktower.com>
 * Based on pnAddressBook by Thomas Smiatek <thomas@smiatek.com>
 */

function addressbook_admin_main()
{

    /**
     * Check if we want to display our overview panel.
     */
    if (xarModGetVar('adminpanels', 'overview') == 0){
        return array();
    } else {
        xarResponseRedirect(xarModURL(__ADDRESSBOOK__,'admin','modifyconfig'));
    }

} // END main

?>
