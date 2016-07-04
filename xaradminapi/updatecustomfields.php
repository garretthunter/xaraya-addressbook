<?php
/**
 * File: $Id: updatecustomfields.php,v 1.5 2004/11/16 05:40:47 garrett Exp $
 *
 * AddressBook admin functions
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
 * update the customfield settings
 *
 * @param passed in from modifycustomfields api
 * @return bool
 * @raise _AB_GLOBALPROTECTERROR, _AB_GRANTERROR, _AB_SORTERROR_1,
 *        _AB_SORTERROR_2, _AB_SPECIAL_CHARS_ERROR
 */
function addressbook_adminapi_updatecustomfields($args)
{

    /**
     * Security check
     */
    if (!xarSecurityCheck('AdminAddressBook',0)) return FALSE;

    extract($args);

    $invalid = array();
    if (!isset($id)) { $invalid[] = '*id*'; }
    if (!isset($del)) { $invalid[] = '*del*'; }
    if (!isset($custLabel)) { $invalid[] = '*custLabel*'; }
    if (!isset($custType)) { $invalid[] = '*custType*'; }
    if (!isset($custDisplay)) { $invalid[] = '*custDisplay*'; }
    if (!isset($custShortLabel)) { $invalid[] = '*custShortLabel*'; }
    if (count($invalid) > 0) {
        $msg = xarML('Invalid #(1) in function #(2)() in module #(3)',
                     join(', ',$invalid), 'updatelabels', __ADDRESSBOOK__);
        xarErrorSet(XAR_SYSTEM_EXCEPTION, 'BAD_PARAM',
                    new SystemException($msg));
        return FALSE;
    }

    if(is_array($del)) {
        $dels = implode(',',$del);
    }
    $modID = $modName = array();
    $modType = array();
    $modDel = array();
    $modDelType = array();
    $modShortLabel = array();
    $modDisplay = array();

    /**
     * we can resequence the custom fields OR make data updates
     * not both
     */
    if (isset($incID) && $incID > 0) {
        if (!xarModAPIFunc(__ADDRESSBOOK__, 'admin', 'inccustomfields', array('id' => $incID))) {
            return FALSE;
        }
    } elseif (isset($decID) && $decID > 0) {
        if (!xarModAPIFunc(__ADDRESSBOOK__, 'admin', 'deccustomfields', array('id' => $decID))) {
            return FALSE;
        }
    } else {

        // Update / Insert / Delete a custom field
        if (isset($id)) {
            foreach ($id as $k=>$custFieldID) {
                $found = false;
                if (isset($dels) && count($del)) {
                    foreach ($del as $d) {
                        if ($custFieldID == $d) {
                            $found = true;
                            array_push($modDel,$custFieldID);
                            array_push($modDelType,$custType[$k]);
                            break;
                        }
                    }
                }
                if (!$found) {
                    array_push ($modID,$custFieldID);
                    array_push ($modName,$custLabel[$k]);
                    array_push ($modType,$custType[$k]);
                    array_push ($modShortLabel,$custShortLabel[$k]);
                }

                // Handle the Display checkboxes
                $modDisplay[$k] = 0;
                if (!empty($custDisplay)) {
                    foreach ($custDisplay as $key=>$custDisplayID) {
                        if ($custFieldID == $custDisplayID) {
                            $modDisplay[$k] = 1;
                        }
                    }
                }
            }
        }

        xarModSetVar(__ADDRESSBOOK__, 'custom_tab',    $custom_tab);
        xarModSetVar(__ADDRESSBOOK__, 'textareawidth', $textareawidth);

        $xarTables =& xarDBGetTables();
        $cus_table = $xarTables['addressbook_customfields'];
        $adr_table = $xarTables['addressbook_address'];

        $updates = array();

        foreach($modID as $k=>$id) {
            array_push($updates,array ('sql'=>"UPDATE $cus_table
                                                  SET label=?, type=?, short_label=?, display=?
                                                WHERE nr=?"
                                        ,'bindvars'=>array($modName[$k],$modType[$k],$modShortLabel[$k],$modDisplay[$k],$id )));

            if (($modType[$k] != 'smallint default NULL') && ($modType[$k] != 'tinyint default NULL')) {
                array_push($updates,array('sql'=>"ALTER TABLE $adr_table CHANGE custom_".$id." custom_".$id." ".$modType[$k],'bindvars'=>array()));
            }
        }

        if(xarModAPIFunc(__ADDRESSBOOK__,'admin','updateitems',array('tablename'=>'customfields','updates'=>$updates))) {
            xarErrorSet(XAR_USER_EXCEPTION,
                        _AB_ERR_INFO,
                        new abUserException('UPDATE - '._AB_SUCCESS));
        } else {
            return FALSE;
        }

        if (count($modDel)) {
            if(xarModAPIFunc(__ADDRESSBOOK__,'admin','deletecustomfields',array('modDel'=>$modDel,'modDelType'=>$modDelType))) {
                xarErrorSet(XAR_USER_EXCEPTION,
                            _AB_ERR_INFO,
                            new abUserException('DELETE - '._AB_SUCCESS));
                if (!xarModAPIFunc(__ADDRESSBOOK__,'admin','resequencecustomfields')) {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        }
        if (isset($newtype) && ($newtype == 'tinyint default NULL')) {
            $newname = '[      ]';
            $newShortLabel = '[      ]';
            $newDisplay = 0;
        }
        if (isset($newtype) && ($newtype == 'smallint default NULL')) {
            $newname = '[------]';
            $newShortLabel = '[------]';
            $newDisplay = 0;
        }
        if( (isset($newname)) && ($newname != '') ) {
            $dbconn =& xarDBGetConn();
            $result = $dbconn->Execute("SELECT MAX(nr) FROM $cus_table");
            list($nextID) = $result->fields;
            $nextID++;
            $result->Close();
            $inserts = array();
            array_push($inserts,array ('sql'=>"INSERT INTO $cus_table (nr,label,type,position,short_label,display)
                                               VALUES (?,?,?,9999999999,?,?)"
                                      ,'bindvars'=>array ($nextID,$newname,$newtype,$newShortLabel,(int)$newDisplay)));

            array_push($inserts,array('sql'=>"ALTER TABLE $adr_table ADD custom_".$nextID." ".$newtype,'bindvars'=>array()));

            if(xarModAPIFunc(__ADDRESSBOOK__,'admin','addcustomfields',array('inserts'=>$inserts))) {
                xarErrorSet(XAR_USER_EXCEPTION,
                            _AB_ERR_INFO,
                            new abUserException('INSERT - '._AB_SUCCESS));
                if (!xarModAPIFunc(__ADDRESSBOOK__,'admin','resequencecustomfields')) {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } // END insert / update / delete

    } // end IF inc / dec field position

    return TRUE;
}

?>