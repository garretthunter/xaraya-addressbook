<?php
/**
 * File: $Id: main.php,v 1.2 2004/03/28 23:23:16 garrett Exp $
 *
 * AddressBook user functions
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

function AddressBook_user_main() 
{

    $output = xarModFunc(__ADDRESSBOOK__,'user','viewall');

    return xarModAPIFunc(__ADDRESSBOOK__,'util','handleException',array('output'=>$output));
} // END main

?>
