<?php
/**
 * File: $Id: deleterecord.php,v 1.2 2004/03/28 23:23:16 garrett Exp $
 *
 * AddressBook userapi deleteRecord
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

/**
 * Delete a record from the address table
 *
 * @param int $id - address table key
 * @return bool
 */
function addressbook_userapi_deleterecord($args) 
{
    extract($args);

    $dbconn =& xarDBGetConn();
    $xarTables =& xarDBGetTables();
    $address_table = $xarTables['addressbook_address'];

    $sql = "DELETE FROM $address_table WHERE nr=$id";

    $result =& $dbconn->Execute($sql);

    if($dbconn->ErrorNo() != 0) { return false; }

    $result->Close();
    return true;
} // END deleterecord

?>