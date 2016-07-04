<?php
/**
 * File: $Id: modifyconfig.php,v 1.7 2005/08/22 06:29:40 garrett Exp $
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

/**
 * Display form used to update the configuration settings
 * Handle the data submission
 *
 * @param GET / POST passed from modifyconfig form
 * @return xarTemplate data
 */
function addressbook_admin_modifyconfig()
{

    $output = array(); // template contents go here

    /**
     * Security check first
     */
    if (xarSecurityCheck('AdminAddressBook',0)) {
        /*
         * Check if we've come in via a submit, commit everything and cary on
         */
        xarVarFetch('formSubmit', 'str::', $formSubmit,FALSE);
        if ($formSubmit) {

            /**
             * Data integrity / Security check
             */
            if (!xarSecConfirmAuthKey())
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));

            // Common Settings
            if (!xarVarFetch ('abtitle','str::60',$formData['abtitle'], '', XARVAR_NOT_REQUIRED)) return;
            if (!xarVarFetch ('useCustomFields', 'checkbox', $formData['useCustomFields'], 0)) return;
            if (!xarVarFetch ('globalprotect','checkbox',$formData['globalprotect'], 0)) return;
            if (!xarVarFetch ('use_prefix',   'checkbox',$formData['use_prefix'],    0)) return;
            if (!xarVarFetch ('display_prefix','checkbox',$formData['display_prefix'],0)) return;
            if (!xarVarFetch ('use_img',      'checkbox',$formData['use_img'],       0)) return;
            if (!xarVarFetch ('menu_off',     'str:1:',  $formData['menu_off'],      0)) return;
            if (!xarVarFetch ('menu_semi',    'checkbox',$formData['menu_semi'],     0)) return;
            if (!xarVarFetch ('itemsperpage','int:1:100',$formData['itemsperpage'],  30)) return;
            if (!xarVarFetch ('hidecopyright','checkbox',$formData['hidecopyright'], 0)) return;

            // Search Settings
            if (!xarVarFetch ('sortdata_1','str:1:',$formData['sortdata_1'])) return;
            if (!xarVarFetch ('sortdata_2','str:1:',$formData['sortdata_2'])) return;
            if (!xarVarFetch ('sortdata_3','str:1:',$formData['sortdata_3'])) return;
            if (!xarVarFetch ('sortdata_4','str:1:',$formData['sortdata_4'])) return;
            if (!xarVarFetch ('name_order','str:1:',$formData['name_order'], 0)) return;

            // Regional Settings
            if (!xarVarFetch ('special_chars_1','str:1:24',$formData['special_chars_1'], '', XARVAR_NOT_REQUIRED)) return;
            if (!xarVarFetch ('special_chars_2','str:1:24',$formData['special_chars_2'], '', XARVAR_NOT_REQUIRED)) return;
            if (!xarVarFetch ('zipbeforecity','checkbox',$formData['zipbeforecity'], 0)) return;
            if (!xarVarFetch ('dateformat',  'str:1:',       $formData['dateformat'],    0)) return;
            if (!xarVarFetch ('numformat',   'str:1:',       $formData['numformat'],     '9,999.99')) return;

            // Quality Control Settings
            if (!xarVarFetch ('rptErrAdminFlag','checkbox',$formData['rptErrAdminFlag'], 1)) return;
            if (!xarVarFetch ('rptErrAdminEmail','str:1:128',$formData['rptErrAdminEmail'], FALSE)) return;
            if (!xarVarFetch ('rptErrDevFlag','checkbox',$formData['rptErrDevFlag'], 1)) return;

            if (!xarModAPIFunc(__ADDRESSBOOK__,'admin','updateconfig',$formData)) {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }
        }

        // User Title for Address Book
        $output['abtitle'] = xarModGetVar(__ADDRESSBOOK__, 'abtitle');

        /**
         * Build Sort Order Options
         */
        // Get the default Sort Order
        $output['defSortCols'] = explode(',',xarModGetVar(__ADDRESSBOOK__, 'sortorder_1'));
        // Get Alternate Sort Order
        $output['altSortCols'] = explode(',',xarModGetVar(__ADDRESSBOOK__, 'sortorder_2'));

        // build the basic sort options
        $sortOptions = xarModAPIFunc(__ADDRESSBOOK__,'util','getsortoptions');

        // Inclue custom fields in sorts & ordering
        $useCustomFields = xarModGetVar(__ADDRESSBOOK__,'useCustomFields');
        if ($useCustomFields) {
            $custFieldLabels = xarModAPIFunc(__ADDRESSBOOK__,'admin','getcustomfields');
            foreach($custFieldLabels as $custFieldLabel) {
                $sortOptions[] = array('id'=>$custFieldLabel['colName'], 'name'=>xarVarPrepHTMLDisplay($custFieldLabel['custLabel']));
            }
        }

        // Load the sort combo boxes
        $output['sortdata_1'] = $sortOptions;
        $output['sortdata_2'] = $sortOptions;
        $output['sortdata_3'] = $sortOptions;
        $output['sortdata_4'] = $sortOptions;

        //////////// End build sortOptions /////////////////////

        // Name display in list view & sort order
        $temp1 = xarVarPrepHTMLDisplay(_AB_SO_LASTNAME).', '.xarVarPrepHTMLDisplay(_AB_SO_FIRSTNAME);
        $temp2 = xarVarPrepHTMLDisplay(_AB_SO_FIRSTNAME).' '.xarVarPrepHTMLDisplay(_AB_SO_LASTNAME);
        $output['name_order'][] = array('id'=>0, 'name'=>$temp1);
        $output['name_order'][] = array('id'=>1, 'name'=>$temp2);
        $output['name_order_selected'] = xarModGetVar(__ADDRESSBOOK__, 'name_order');

        // Additional Settings
        $output['special_chars_1']  = xarModGetVar(__ADDRESSBOOK__, 'special_chars_1');
        $output['special_chars_2']  = xarModGetVar(__ADDRESSBOOK__, 'special_chars_2');

        $output['globalprotect']    = xarModGetVar(__ADDRESSBOOK__, 'globalprotect');
        $output['use_prefix']       = xarModGetVar(__ADDRESSBOOK__, 'use_prefix');
        $output['display_prefix']   = xarModGetVar(__ADDRESSBOOK__, 'display_prefix');
        $output['use_img']          = xarModGetVar(__ADDRESSBOOK__, 'use_img');

        // Disable / enable menu options
        $output['menu_off'][] = array('id'=>0, 'name'=>xarVarPrepHTMLDisplay(_AB_HIDENOTHING));
        $output['menu_off'][] = array('id'=>1, 'name'=>xarVarPrepHTMLDisplay(_AB_HIDEALL));
        $output['menu_off'][] = array('id'=>2, 'name'=>xarVarPrepHTMLDisplay(_AB_HIDEGUESTS));
        $output['menu_off_selected'] = (int) xarModGetVar(__ADDRESSBOOK__, 'menu_off');

        $output['menu_semi']        = xarModGetVar(__ADDRESSBOOK__, 'menu_semi');
        $output['zipbeforecity']    = xarModGetVar(__ADDRESSBOOK__, 'zipbeforecity');
        $output['itemsperpage']     = xarModGetVar(__ADDRESSBOOK__, 'itemsperpage');
        $output['hidecopyright']    = xarModGetVar(__ADDRESSBOOK__, 'hidecopyright');
        $output['useCustomFields']  = xarModGetVar(__ADDRESSBOOK__, 'useCustomFields');

        $output['dateformat'][] = array('id'=>0, 'name'=>xarVarPrepForDisplay(_AB_DATEFORMAT_1));
        $output['dateformat'][] = array('id'=>1, 'name'=>xarVarPrepForDisplay(_AB_DATEFORMAT_2));
        $output['dateformat_selected'] = xarModGetVar(__ADDRESSBOOK__, 'dateformat');

        $output['numformat'][] = array('id'=>'9,999.99', 'name'=>'9,999.99');
        $output['numformat'][] = array('id'=>'9.999,99', 'name'=>'9.999,99');
        $output['numformat_selected'] = xarModGetVar(__ADDRESSBOOK__, 'numformat');

        // Admin Message config
        $output['rptErrAdminFlag']    = xarModGetVar(__ADDRESSBOOK__, 'rptErrAdminFlag');
        $output['rptErrAdminEmail']   = xarModGetVar(__ADDRESSBOOK__, 'rptErrAdminEmail');
        $output['rptErrDevFlag']      = xarModGetVar(__ADDRESSBOOK__, 'rptErrDevFlag');

        // Generate a one-time authorisation code for this operation
        $output['authid'] = xarSecGenAuthKey();

        // Submit button
        $output['btnCommitText'] = xarVarPrepHTMLDisplay(_AB_ADDRESSBOOKUPDATE);

    } else {
        return xarTplModule(__ADDRESSBOOK__,'user','noauth');
    }

    return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));

} // END modifyconfig

?>