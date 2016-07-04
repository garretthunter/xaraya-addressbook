<?php
/**
 * File: $Id: export.php,v 1.1 2005/03/28 23:15:19 garrett Exp $
 *
 * AddressBook user export
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
 * Export all addresses to a comma separated values (CSV) ASCII file
 *
 * @return array of menu links
 */
function addressbook_userapi_export()
{
    // SQL Query
    $dbconn =& xarDBGetConn();
    $xarTables =& xarDBGetTables();
    $address_table = $xarTables['addressbook_address'];

    $sql = "SELECT *
              FROM $address_table
          ORDER BY lname";

    $result =& $dbconn->Execute($sql);
    if($dbconn->ErrorNo() != 0) { return false; }

    // Retrieve all the custom fields, we use this throughout.
    $custFields = xarModAPIFunc(__ADDRESSBOOK__,'user','getcustfieldinfo',array('flag'=>_AB_CUST_ALLFIELDINFO));

    /**
     * Get the prefix decodes if we are to display them
     */
    $prefixes = array();
    if (xarModGetVar(__ADDRESSBOOK__, 'display_prefix')) {
        $prefixes = xarModAPIFunc(__ADDRESSBOOK__,'util','getitems',array('tablename'=>'prefixes'));
    }

// List Header
    $address_export  = '"Prefix","First Name","Last Name","Title","Company","Address 1","Address 2","City","State","Zipcode","Country",';
    $address_export .= '"Donor","Caretaker","Member","Trap, Neuter, Release","Volunteer","Foster","Feeder","Food Source","Packet","Contact Date",';
    $address_export .= '"2002 Donations","2003 Donations","2004 Donations","2005 Donations","Referred By","Note","Last Updated"'."\n";

    $abData = array('id'            => ''
                   ,'cat_id'        => ''
                   ,'prefix'        => ''
                   ,'lname'         => ''
                   ,'fname'         => ''
                   ,'sortname'      => ''
                   ,'title'         => ''
                   ,'company'       => ''
                   ,'sortcompany'   => ''
                   ,'img'           => ''
                   ,'zip'           => ''
                   ,'city'          => ''
                   ,'address_1'     => ''
                   ,'address_2'     => ''
                   ,'state'         => ''
                   ,'country'       => ''
                   ,'contact_1'     => ''
                   ,'contact_2'     => ''
                   ,'contact_3'     => ''
                   ,'contact_4'     => ''
                   ,'contact_5'     => ''
                   ,'c_label_1'     => ''
                   ,'c_label_2'     => ''
                   ,'c_label_3'     => ''
                   ,'c_label_4'     => ''
                   ,'c_label_5'     => ''
                   ,'c_main'        => ''
                   ,'custom_1'      => ''
                   ,'custom_2'      => ''
                   ,'custom_3'      => ''
                   ,'custom_4'      => ''
                   ,'note'          => ''
                   ,'user'          => ''
                   ,'private'       => ''
                   ,'last_updt'     => '');


    foreach($custFields as $custField) {
        $abData[$custField['colName']] = '';
    }

    for (; !$result->EOF; $result->MoveNext()) {

        $index = 0;
        foreach ($abData as $key=>$value) {
            $abData[$key] = $result->fields[$index++];
        }
        extract ($abData);

        if ($prefix > 0) {
            $address_export .= '"'.$prefixes[$prefix-1]['name'].'",';
        } else {
            $address_export .= '"",';
        }
        $address_export .= '"'.$fname.'",';
        $address_export .= '"'.$lname.'",';
        $address_export .= '"'.$title.'",';
        $address_export .= '"'.$company.'",';
        $address_export .= '"'.$address_1.'",';
        $address_export .= '"'.$address_2.'",';
        $address_export .= '"'.$city.'",';
        $address_export .= '"'.$state.'",';
        $address_export .= '"'.$zip.'",';
        $address_export .= '"'.$country.'",';

        foreach($custFields as $custField) {
            switch ($custField['custType']) {
                case 'int(1) default NULL':
                    if ($$custField['colName']) {
                        $address_export .= '"Y",';
                    } else {
                        $address_export .= '"",';
                    }
                    break;
                default:
                    $address_export .= '"'.$$custField['colName'].'",';
                    break;
            }
        }

        $address_export .= '"'.$note.'",';
        $address_export .= '"'.date("m-d-Y",$last_updt).'"';
        $address_export .="\n";
    }

    header("Content-type: text/x-excel");
    header('Content-Disposition: inline; filename="FCCC_AddressList.csv"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    echo $address_export;
    exit;

    return FALSE;

} // END export

?>