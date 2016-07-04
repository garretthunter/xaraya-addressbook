<?php
/**
 * File: $Id: modifylabels.php,v 1.2 2004/03/28 23:22:23 garrett Exp $
 *
 * AddressBook admin modifylabels
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
 * Display form used to update the labels in the contact form
 * Handle the data submission
 *
 * @param GET / POST passed from modifylabels form
 * @return xarTemplate data
 */
function addressbook_admin_modifylabels() 
{

    $output = array (); // template contents go here
    /**
     * Security check first
     */
    if (xarSecurityCheck('AdminAddressBook',0)) {
        /*
         * Check if we've come in via a submit, commit everything and cary on
         */
        xarVarFetch('formSubmit', 'str::', $formSubmit,FALSE);
        if ($formSubmit) {

            if (!xarSecConfirmAuthKey())
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));

            if (!xarVarFetch ('id', 'array::',$formData['id'], FALSE)) return FALSE;
            if (!xarVarFetch ('del', 'array::',$formData['del'], FALSE)) return FALSE;
            if (!xarVarFetch ('name', 'array::',$formData['name'], FALSE)) return FALSE;
            if (!xarVarFetch ('newname','str::30',$formData['newname'],FALSE)) return FALSE;

            if (!xarModAPIFunc(__ADDRESSBOOK__,'admin','updatelabels',$formData)) {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }
        }

        // get the list of labels
        $output['labels'] = xarModAPIFunc(__ADDRESSBOOK__,'util','getitems',array('tablename'=>'labels'));

        // Generate a one-time authorisation code for this operation
        $output['authid'] = xarSecGenAuthKey();

        // Submit button
        $output['btnCommitText'] = xarVarPrepHTMLDisplay(xarML(_AB_ADDRESSBOOKUPDATE));

    } else {
        return xarTplModule(__ADDRESSBOOK__,'user','noauth');
    }

    return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));

} // END modifylabels


?>