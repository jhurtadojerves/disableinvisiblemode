<?php

class DisableInvisibleMode
{
    function __construct()
    {
        global $plugins;

        // Tell MyBB when to run the hookadmin_user_users_edit_commit 
        $plugins->add_hook('admin_formcontainer_end', array($this, 'hook_admin_formcontainer_end'));
        $plugins->add_hook('admin_user_users_edit_commit_start', array($this, 'hook_admin_user_users_edit_commit_start'));
        $plugins->add_hook('usercp_do_options_end', array($this, 'hook_usercp_do_options_end'));


    }
    function _info()
    {
        global $lang;
        $this->load_language();
        return array(
            'name' => 'Disable Invisible Mode',
            'description' => $lang->disableinvisibleuser_desc,
            'website' => 'https://github.com/jhurtadojerves/disableinvisiblemode',
            'author' => 'Julio Hurtado',
            'authorsite' => 'https://juliohurtado.xyz',
            'version' => '1.0.0',
            'versioncode' => 100,
            'compatibility' => '18*',
            'codename' => 'disableinvisiblemode',
        );
    }

    function _activate()
    {
        global $lang, $mybb;

        // Insert/update version into cache
        $plugins = $mybb->cache->read('juliens_plugins');
        if (!$plugins) {
            $plugins = array();
        }

        $this->_info();
        if (!isset($plugins['disableinvisiblemode'])) {
            $plugins['disableinvisiblemode'] = $this->_info['versioncode'];
        }

        $plugins['disableinvisiblemode'] = $this->_info['versioncode'];
        $mybb->cache->update('juliens_plugins', $plugins);
    }
    function _deactivate()
    {

    }
    function _install()
    {
        global $db, $cache;

        if (!$db->field_exists("caninvisible", "users")) {
            $db->add_column("users", "caninvisible", "int(1) NOT NULL default '0'");
        }
    }

    function _uninstall()
    {
        global $db, $cache;

        if ($db->field_exists("caninvisible", "users")) {
            $db->drop_column("users", "caninvisible", "int(1) NOT NULL default '0'");
        }
        // Delete version from cache
        $plugins = (array)$cache->read('juliens_plugins');

        if (isset($plugins['disableinvisiblemode'])) {
            unset($plugins['disableinvisiblemode']);
        }

        if (!empty($plugins)) {
            $cache->update('juliens_plugins', $plugins);
        } else {
            $PL->cache_delete('juliens_plugins');
        }
    }

    function _is_installed()
    {
        global $cache;

        $plugins = $cache->read('juliens_plugins');

        return isset($plugins['disableinvisiblemode']);
    }

    function load_language()
    {
        global $lang;

        $lang->load('disableinvisiblemode');
    }

    function hook_admin_formcontainer_end()
    {
        global $run_module, $form_container, $lang, $form, $mybb, $user;

        $this->load_language();
        $title = $lang->mod_options . ': ' . htmlspecialchars_uni($user['username']);
        if ($run_module == "user" && $form_container->_title == $title) {
            //echo 'ola k ase';
            $mp_options[] = $form->generate_check_box("caninvisible", 1, $lang->disableinvisibleuser_can_use, array("id" => "caninvisible", 'checked' => $mybb->input["caninvisible"]));
            $form_container->output_row($lang->disableinvisibleuser_can_use, '', '<div class="group_settings_bit">' . implode('</div><div class="group_settings_bit">', $mp_options) . '</div>');
        }
    }
    function hook_admin_user_users_edit_commit_start()
    {
        global $extra_user_updates, $mybb;
        $extra_user_updates['caninvisible'] = (int)$mybb->input['caninvisible'];
        if ((int)$mybb->input['caninvisible'] == 0)
            $extra_user_updates['invisible'] = (int)$mybb->input['caninvisible'];
    }
    function hook_usercp_do_options_end()
    {
        global $mybb, $user, $db;
        if ($mybb->user['caninvisible'] == 0) {
            $db->update_query('users', array('invisible' => 0), "uid = " . $mybb->user['uid']);
        }
    }
}
