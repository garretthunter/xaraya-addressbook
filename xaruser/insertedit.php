<?php
/**
 * File: $Id: insertedit.php,v 1.6 2005/08/22 06:29:42 garrett Exp $
 *
 * AddressBook user insertEdit
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

//=========================================================================
//  Insert/Edit form
//=========================================================================
function addressbook_user_insertedit()
{

    $output = array();

    /**
     * Security check first
     */
    if (xarSecurityCheck('AddAddressBook',0)   ||
        xarSecurityCheck('EditAddressBook',0)) {

        // Get the form values
        $output = xarModAPIFunc(__ADDRESSBOOK__,'user','getsubmitvalues',array('output'=>$output));

        /**
         * read in the menu values for preservation & display
         */
        $output['menuValues'] = xarModAPIFunc(__ADDRESSBOOK__,'user','getmenuvalues');
        foreach ($output['menuValues'] as $key=>$value) {
            $output[$key] = $value;
        }

        /*
         * Check if we've come in via a submit, commit everything and cary on
         */
        if (!xarVarFetch ('cancel','str::',$cancel, FALSE))
            return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
        if (!xarVarFetch ('update','str::',$update, FALSE))
            return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
        if (!xarVarFetch ('insert','str::',$insert, FALSE))
            return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));

        // Insert or Edit
        if (!xarVarFetch ('formcall','str::',$output['formcall'], 'insert', XARVAR_NOT_REQUIRED))
            return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));


        if (!empty($cancel)) {
            xarResponseRedirect(xarModURL(__ADDRESSBOOK__, 'user', 'viewall',$output['menuValues']));
            return true;
        } elseif (!empty($update)) {
            /**
             * Check for an update
             */

         //Security Check
            if (!xarSecConfirmAuthKey()) {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }

            if (xarModAPIFunc(__ADDRESSBOOK__,'user','checksubmitvalues',$output)) {
                if (xarModAPIFunc(__ADDRESSBOOK__,'user','updaterecord',$output)) {
                    $output['menuValues'] = xarModAPIFunc(__ADDRESSBOOK__,'user','getmenuvalues');
                    xarResponseRedirect(xarModURL(__ADDRESSBOOK__, 'user', 'viewall',$output['menuValues']));
                    return true;
                }
            } else {
                $output['action'] = _AB_TEMPLATE_NAME;
            }
        } elseif (!empty($insert)) {
            /**
             * Was the insert button clicked
             */

             //Security Check
            if (!xarSecConfirmAuthKey()) {
                return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }

            if (xarModAPIFunc(__ADDRESSBOOK__,'user','checksubmitvalues',$output)) {
                if (xarModAPIFunc(__ADDRESSBOOK__,'user','insertrecord',$output)) {
                    $output['insertStatus'] = TRUE;
                    $output['insertSuccess'] = xarML(_AB_INSERT_AB_SUCCESS);
                    $output['newAddrLinkTEXT'] = xarML(_AB_MENU_ADD);
                    $output['backToListTEXT'] = xarML(_AB_GOBACK);

                    return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
                }
            } else {
                $output['action'] = _AB_TEMPLATE_NAME; //return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));
            }

        } // END submit checks

        $output['globalprotect']   = xarModGetVar(__ADDRESSBOOK__,'globalprotect');
        $output['useCustomFields'] = xarModGetVar(__ADDRESSBOOK__,'useCustomFields');
        $output['custom_tab']      = xarModGetVar(__ADDRESSBOOK__,'custom_tab');

        /**
         * Configure for custom field tab
         */
        if ($output['useCustomFields']) {
            $output['addrow'] = 1;
        }  else {
            $output['addrow'] = 0;
        }
        $output['numcols'] = _AB_NUM_COLS + $output['addrow'];

        $output['authid'] = xarSecGenAuthKey();

        /**
         * Get everything from the input
         */
        if ($output['addrow'] && !$output['formSubmitted']) {
            if ($output['formcall'] == 'edit') {
                $output['custUserData'] = xarModAPIFunc(__ADDRESSBOOK__,'user','getcustfieldinfo',array('id'=>$output['id']));
            }
            else {
                $output['custUserData'] = xarModAPIFunc(__ADDRESSBOOK__,'user','getcustfieldinfo');
            }
        }

        if ($output['id'] > 0 && !$output['formSubmitted']) {
            // Get detailed values from database
            $details = xarModAPIFunc(__ADDRESSBOOK__,'user','getdetailvalues',array('id'=>$output['id']));
            foreach ($details as $key=>$value) {
                $output[$key] = $value;
            }
        }

        switch ($output['formcall']) {
            case 'edit':    $output['btnCommitID'] = "update"; $output['btnCommitTitle'] = "Update"; break;
            case 'insert':  $output['btnCommitID'] = "insert"; $output['btnCommitTitle'] = "Insert"; break;
        }

    /**
     * Format data that is displayed across all sub-templates
     */
        $output['cats'] = xarModAPIFunc(__ADDRESSBOOK__,'util','getitems',array('tablename'=>'categories'));

    /**
     * Perform custom processing per sub-template
     */
        switch ($output['action']) {
            case _AB_TEMPLATE_NAME:
                $output['prfxs'] = xarModAPIFunc(__ADDRESSBOOK__,'util','getitems',array('tablename'=>'prefixes'));

                /**
                 * Handle images
                 */
                if (xarModGetVar(__ADDRESSBOOK__,'use_img') && xarSecurityCheck('ReadAddressBook',0)) {
                    $modInfo = xarModGetInfo(xarModGetIDFromName(__ADDRESSBOOK__));
                    $handle = @opendir("modules/".$modInfo['directory']."/xarimages");
                    $output['imgFiles'][] = array('id'=>'','name'=>_AB_NOIMAGE);
                    while ($file = @readdir ($handle)) {
                        if (eregi("^\.{1,2}$",$file)) {
                            continue;
                        }
                        else {
                            $output['imgFiles'][] = array('id'=>$file, 'name'=>$file);
                        }
                    }
                    @closedir($handle);
                }

                /**
                 * Company auto fill dropdown
                 */
                $output['companies'] = xarModAPIFunc(__ADDRESSBOOK__,'user','getcompanies');

                break;

            case _AB_TEMPLATE_ADDR:
                if (!xarVarFetch('autoCompanyFill','int::',$autoCompanyFill,FALSE)) return;
                if (!xarVarFetch('comp_lookup',    'int::',$comp_lookup,FALSE)) return;
                if ($autoCompanyFill) {
                    if (!xarVarFetch('companyId','int::',$companyId,FALSE)) return;
                    if (!xarVarFetch ('comp_lookup','str::',$comp_id, FALSE)) return;
                    $compaddress = xarModAPIFunc(__ADDRESSBOOK__,'user','getcompanyaddress',array('id'=>$comp_id));

                    foreach($compaddress as $fieldName=>$value) {
                        $output[$fieldName] = $value;
                    }
                }

                break;

            case _AB_TEMPLATE_CONTACT:
                $output['labels'] = xarModAPIFunc(__ADDRESSBOOK__,'util','getitems',array('tablename'=>'labels'));
                break;

            case _AB_TEMPLATE_CUST:
                $output['dateformat_1'] = _AB_DATEFORMAT_1;
                $output['dateformat_2'] = _AB_DATEFORMAT_2;
                $output['textareawidth'] = xarModGetVar(__ADDRESSBOOK__,'textareawidth');
                break;

            case _AB_TEMPLATE_NOTE:
                break;

        } // END swtich

        /**
         * Preserve menu vars from page to page
         */
        $output['menuValues'] = array('catview'   =>$output['catview'],
                                      'menuprivate'=>$output['menuprivate'],
                                      'all'       =>$output['all'],
                                      'sortview'  =>$output['sortview'],
                                      'page'      =>$output['page'],
                                      'char'      =>$output['char'],
                                      'total'     =>$output['total']);

        // Get user id
        if (xarUserIsLoggedIn()) {
            $output['user_id'] = xarUserGetVar('uid');
        }
        // END custom field handling

    } // END SecurityCheck

    return xarModAPIFunc(__ADDRESSBOOK__,'util','handleexception',array('output'=>$output));

} // END insertedit

?>