<?php
/**
 * File: $Id: getmenu.php,v 1.2 2004/03/28 23:23:16 garrett Exp $
 *
 * AddressBook user getMenu
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
 * Displays user menu
 */
function addressbook_userapi_getMenu($args)
{
    extract ($args);

    $output['authid'] = xarSecGenAuthKey();

    if (xarSecurityCheck('AddAddressBook',0)) {
        $output['show_menu_off'] = FALSE;
    }
    else {
        $menu_off = xarModGetVar(__ADDRESSBOOK__, 'menu_off');
        switch ($menu_off) {
            case 0:
                $output['show_menu_off'] = FALSE;
                break;
            case 1:
                $output['show_menu_off'] = TRUE;
                break;
            case 2:
                if (xarUserIsLoggedIn()) {
                    $output['show_menu_off'] = FALSE;
                } else {
                    $output['show_menu_off'] = TRUE;
                }
                break;
            default:
                $output['show_menu_off'] = TRUE;
                break;
        }
    }
    if ($output['show_menu_off']) {
        return $output;
    }
    else {
        // Start Category
        $output['cats'] = xarModAPIFunc(__ADDRESSBOOK__,'util','getitems',array('tablename'=>'categories'));

        $sortby_1 = xarModAPIFunc(__ADDRESSBOOK__,'user','getsortby',array('sort'=>1));
        $sortby_2 = xarModAPIFunc(__ADDRESSBOOK__,'user','getsortby',array('sort'=>2));
        $output['sortby'][] = array('id'=>0,'name'=>xarVarPrepHTMLDisplay($sortby_1));
        $output['sortby'][] = array('id'=>1,'name'=>xarVarPrepHTMLDisplay($sortby_2));

        if ((xarModGetVar(__ADDRESSBOOK__, 'menu_semi') != 1) || (xarSecurityCheck('AddAddressBook',0))) {

            // Removed display stuff

            /**
             * Do we show the private menu link
             */
            if (xarUserIsLoggedIn()) {

                if (((xarModGetVar(__ADDRESSBOOK__, 'globalprotect'))==1) && (!xarSecurityCheck('AddAddressBook',0))) {
                    $menuprivate_fl = 1;
                }
                else {
                    if ($output['menuprivate'] == 1) {
                        $menuprivate_fl = 0;
                    }
                    else {
                        $menuprivate_fl = 1;
                    }
                }

                $output['menuprivateParams'] =
                        array('authid'=>xarSecGenAuthKey()
                             ,'sortview'=>$output['sortview']
                             /*,'total'=>$output['total']*/
                             ,'catview'=>$output['catview']
                             ,'menuprivate'=>$menuprivate_fl
                             ,'all'=> $output['all']
                             ,'char'=>$output['char']);

                $output['viewPrivateTEXT'] = xarML(_AB_VIEWPRIVATE);
                if ($output['menuprivate']) {
                    $output['privateIndicator'] = 'X';
                } else {
                    $output['privateIndicator'] = '&nbsp;';
                }


            }
            // end private

            $output['azMenuTEXT'] = xarML(_AB_MENU_AZ);
            $output['allMenuTEXT'] = xarML(_AB_MENU_ALL);

            // Start A-Z/All
            if ($output['all'] == 1) {
                $output['azInidcator'] = '&nbsp;';
                $output['allInidcator']= 'X';
            }
            else {
                $output['azInidcator'] = 'X';
                $output['allInidcator']= '&nbsp;';
            }
            $output['azParams'] = array('authid'=>xarSecGenAuthKey()
                                ,'sortview'=>$output['sortview']
                                ,'catview'=>$output['catview']
                                ,'menuprivate'=>$output['menuprivate']
                                ,'all'=>0
                                ,'char'=>$output['char']);

            $output['allParams'] = array('authid'=>xarSecGenAuthKey()
                                ,'sortview'=>$output['sortview']
                                ,'catview'=>$output['catview']
                                ,'menuprivate'=>$output['menuprivate']
                                ,'all'=>1
                                ,'total'=>$output['total']
                                ,'page'=>$output['page']
                                ,'char'=>$output['char']);

            // End A-Z/all

            /**
             * New Address Link processing
             */

            // URL query params passed by new address link
            $output['menuValues']=array('formcall'=>'insert',
                                        'authid'=>xarSecGenAuthKey(),
                                        'catview'=>$output['catview'],
                                        'menuprivate'=>$output['menuprivate'],
                                        'all'=>$output['all'],
                                        'sortview'=>$output['sortview'],
                                        'page'=>$output['page'],
                                        'char'=>$output['char'],
                                        'total'=>$output['total']);

            $output['newAddrLinkTEXT'] = xarVarPrepHTMLDisplay (xarML(_AB_MENU_ADD));

            // End New Record

        }
    }

    return $output;
} // END getMenu

?>