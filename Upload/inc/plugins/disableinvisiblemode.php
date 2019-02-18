<?php

/***************************************************************************
 *
 *  Invisible User (/inc/plugins/changepostauthor.php)
 *  Author: Julio Hurtado
 *  Copyright: © 2019 Julio Hurtado
 *
 *  Website: https://juliohurtado.xyz
 *
 * Allows moderators to change post author
 * 
 * 
 ***************************************************************************

 ****************************************************************************
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ****************************************************************************/

// Die if IN_MYBB is not defined, for security reasons.
defined('IN_MYBB') or die('Direct initialization of this file is not allowed.');

// PLUGINLIBRARY
defined('PLUGINLIBRARY') or define('PLUGINLIBRARY', MYBB_ROOT . 'inc/plugins/pluginlibrary.php');

// Cache template
if (THIS_SCRIPT == 'usercp.php') {
    global $templatelist;

    if (!isset($templatelist)) {
        $templatelist = '';
    }

    $templatelist .= ',disableinvisiblemode';
}

function disableinvisiblemode_info()
{
    global $lang, $invisibleUser;
    return $invisibleUser->_info();
}
function disableinvisiblemode_activate()
{
    global $invisibleUser;

    return $invisibleUser->_activate();
}

function disableinvisiblemode_deactivate()
{
    global $invisibleUser;

    return $invisibleUser->_deactivate();
}

function disableinvisiblemode_install()
{
    global $invisibleUser;

    return $invisibleUser->_install();
}

function disableinvisiblemode_is_installed()
{
    global $invisibleUser;

    return $invisibleUser->_is_installed();

}

function disableinvisiblemode_uninstall()
{
    global $invisibleUser;

    return $invisibleUser->_uninstall();

}
require_once MYBB_ROOT . "inc/plugins/disableinvisiblemode/DisableInvisibleMode.php";

global $invisibleUser;
$invisibleUser = new DisableInvisibleMode();