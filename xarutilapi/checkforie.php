<?php
/**
 * File: $Id: checkforie.php,v 1.4 2004/11/16 05:40:47 garrett Exp $
 *
 * AddressBook util checkForIE
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
 * Invoke the sniffer API and check if the request comes from an IE browser
 *
 * @param none
 * @return bool (true - client uses Internet Explorer / false - no IE
 */
function addressbook_utilapi_checkforie() 
{
    if (xarModLoad('sniffer','user')) {
        xarModAPIFunc('sniffer','user','sniff');

        if (!stristr(xarSessionGetVar('browsername'),'internet explorer')) {
            return FALSE;
        }
        if (xarSessionGetVar('browserversion') < 5) {
            return FALSE;
        }
        return TRUE;
    } else {
        xarErrorFree();
        xarCoreExceptionFree();    
    }
    return FALSE;

} //END checkForIE

?>