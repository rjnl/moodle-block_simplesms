<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package    block_simplesms
 * @copyright  2012 Rajneel Totaram
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

    $settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_simplesms'),
            get_string('descconfig', 'block_simplesms')
        ));

    $settings->add(new admin_setting_configtext(
        'simplesms_host',
        get_string('simplesms_host', 'block_simplesms'),
        get_string('simplesms_hostdesc', 'block_simplesms'), ''
    ));

    $settings->add(new admin_setting_configtext(
        'simplesms_port',
        get_string('simplesms_port', 'block_simplesms'),
        get_string('simplesms_portdesc', 'block_simplesms'), '',
        PARAM_INT
    ));

    $settings->add(new admin_setting_configtext(
        'simplesms_username',
        get_string('simplesms_username', 'block_simplesms'),
        get_string('simplesms_usernamedesc', 'block_simplesms'), ''
    ));

    $settings->add(new admin_setting_configpasswordunmask(
        'simplesms_password',
        get_string('simplesms_password', 'block_simplesms'),
        get_string('simplesms_passworddesc', 'block_simplesms'), ''
    ));

    $settings->add(new admin_setting_configtext(
        'simplesms_url',
        get_string('simplesms_url', 'block_simplesms'),
        get_string('simplesms_urldesc', 'block_simplesms'), '/cgi-bin/sendsms'
    ));
