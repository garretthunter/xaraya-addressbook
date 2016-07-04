<?php
/**
 * File: $Id: getcompanyaddress.php,v 1.2 2004/03/28 23:23:16 garrett Exp $
 *
 * AddressBook userapi getCompanyAddress
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
 * getCompanyAddress
 *
 * @param int $id - AddressBook entry with Company address
 * @return array - company name + address
 */
function addressbook_userapi_getCompanyAddress($args) 
{
    extract($args);
    $dbconn =& xarDBGetConn();
    $xarTables =& xarDBGetTables();
    $adr_table = $xarTables['addressbook_address'];

    $sql = "SELECT company,address_1,address_2,zip,city,state,country
              FROM $adr_table
             WHERE company = '$id'";

    $result =& $dbconn->Execute($sql);

    if($dbconn->ErrorNo() != 0) { return array(); }
    if(!isset($result)) { return $sql; }

    list($company,$address_1,$address_2,$zip,$city,$state,$country) = $result->fields;
    $compaddress = array(   'company'=>$company,
                            'address_1'=>$address_1,
                            'address_2'=>$address_2,
                            'zip'=>$zip,
                            'city'=>$city,
                            'state'=>$state,
                            'country'=>$country);
    $result->Close();
    return $compaddress;

} // END getCompanyAddress

?>