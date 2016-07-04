<?php
/**
 * File: $Id: modifyexport.php,v 1.1 2005/03/29 07:17:29 garrett Exp $
 *
 * AddressBook admin modifyexport
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
 * Display form used to update the prefixes in the name form
 * Handle the data submission
 *
 * @param GET / POST passed from modifyexport form
 * @return xarTemplate data
 */
function addressbook_admin_modifyexport($args)
{
    $output = array (); // template contents go here
    /**
     * Security check first
     */
    if (xarSecurityCheck('AdminAddressBook',0)) {
        /*
         * Check if we've come in via a submit, commit everything and cary on
         */
/*        xarVarFetch('formSubmit', 'str::', $formSubmit,FALSE);
        if ($formSubmit) {

            if (!xarSecConfirmAuthKey())
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));


            if (!xarVarFetch ('id', 'array::',$formData['id'], FALSE)) {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }
            if (!xarVarFetch ('del', 'array::',$formData['del'], FALSE))  {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }
            if (!xarVarFetch ('name', 'array::',$formData['name'], FALSE))  {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }
            if (!xarVarFetch ('newname','str::30',$formData['newname'],FALSE))  {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }

            if (!xarModAPIFunc(__ADDRESSBOOK__,'admin','updateprefixes',$formData)) {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }
        }

        // get the list of prefixes
        $output['prefixes'] = xarModAPIFunc(__ADDRESSBOOK__,'util','getitems',array('tablename'=>'prefixes'));

        // Generate a one-time authorisation code for this operation
        $output['authid'] = xarSecGenAuthKey();

        // Submit button
        $output['btnCommitText'] = xarVarPrepHTMLDisplay(xarML(_AB_ADDRESSBOOKUPDATE));
*/
    } else {
        return xarTplModule(__ADDRESSBOOK__,'user','noauth');
    }

    return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));

} // END modifyexport

?>