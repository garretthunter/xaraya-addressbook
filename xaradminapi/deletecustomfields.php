<?php
/**
 * File: $Id: deletecustomfields.php,v 1.3 2005/08/22 06:29:40 garrett Exp $
 *
 * AddressBook admin deleteCustomFields
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
 *  a record based on the SQL string passed in
 *
 * @param args['delete'] string
 * @return bool
 */
function addressbook_adminapi_deleteCustomfields($args)
{
    $returnCode = TRUE;

    /**
     * Security check
     */
    if (!xarSecurityCheck('AdminAddressBook',0)) return FALSE;

    extract($args);

    /*
     * Validate parameters
     */
    $invalid = array();
    if(!isset($modDel) || !is_array($modDel)) {
        $invalid[] = 'modDel';
    }
    if(!isset($modDelType) || !is_array($modDelType)) {
        $invalid[] = 'modDelType';
    }
    if (count($invalid) > 0) {
        $msg = xarML('Invalid #(1) for #(2) function #(3)() in module #(4)',
                     join(', ', $invalid), 'admin', 'updateItems', __ADDRESSBOOK__);
        xarErrorSet(XAR_SYSTEM_EXCEPTION, 'BAD_PARAM',
                    new SystemException($msg));
        $returnCode = FALSE;
    } else {

        $xarTables =& xarDBGetTables();
        $cus_table = $xarTables['addressbook_customfields'];
        $adr_table = $xarTables['addressbook_address'];

        $deletes = array();
        foreach($modDel as $k=>$id) {
                array_push($deletes,"DELETE FROM $cus_table WHERE nr = $id");
                if (($modDelType[$k] != 'smallint default NULL') && ($modDelType[$k] != 'tinyint default NULL')) {
                    array_push($deletes,"ALTER TABLE $adr_table DROP custom_".$id);
                }
        }

        $dbconn =& xarDBGetConn();
        foreach($deletes as $delete) {
            $result =& $dbconn->Execute($delete);
            if (!$result) $returnCode = FALSE;

        }
    }
    return $returnCode;
} // END deleteCustomFields

?>