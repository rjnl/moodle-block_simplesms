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

$string['pluginname'] = 'Simple SMS';
$string['blocktitle'] = 'Simple SMS';
$string['simplesms:addinstance'] = "Add a new SMS block";
$string['headerconfig'] = 'Kannel Gateway Settings';
$string['descconfig'] = 'Fill in the Kannel Gateway settings';

$string['simplesms:cansend'] = "Allow users to send SMS";
$string['simplesms:candelete'] = "Allow users to delete SMS";

$string['composesms'] = 'Send SMS';
$string['composeanothersms'] = 'Send another SMS';
$string['messagehistory'] = 'SMS History';
$string['no_course'] = 'Invalid Course with id of {$a}';
$string['no_group'] = 'Not in a group';
$string['all_groups'] = 'All Groups';
$string['potential_groups'] = 'Potential Groups';
$string['no_send'] = 'Could not send SMS to {$a->idnumber}: {$a->firstname} {$a->lastname}.';
$string['no_permission'] = 'You do not have permission to send SMS';
$string['no_users'] = 'There are no users that you can send SMSs to.';
$string['no_selected'] = 'You must select some users for send SMSs.';
$string['not_valid_user'] = 'You can not view other sms history.';
$string['not_valid_action'] = 'You must provide a valid action: {$a}';
$string['not_valid_typeid'] = 'Incorrect message id';
$string['from'] = 'From: ';
$string['selected'] = 'Selected Recipients';
$string['add_button'] = 'Add';
$string['remove_button'] = 'Remove';
$string['add_all'] = 'Add All';
$string['remove_all'] = 'Remove All';
$string['potential_groups'] = 'Course Groups';
$string['potential_users'] = 'Potential Recipents';
$string['delete_confirm'] = 'Do you really want to delete this message? {$a}';
$string['subject'] = 'Subject';
$string['message'] = 'Message';
$string['send'] = 'Send';
$string['actions'] = 'Actions';
$string['no_mail'] = 'Could not send SMS to {$a->firstname} {$a->lastname}.';

$string['openmessage'] = 'Open Message';
$string['deletemessage'] = 'Delete Message';

$string['required'] = 'Required field';
$string['message_success'] = '<b>SMS sent successfully.</b>';
$string['no_message'] = 'Please enter a message to send';
$string['sendanother'] = 'Send another SMS?';
$string['sendfail'] = '<i>[Could not send SMS to {$a} users.]</i>';
$string['backtocourse'] = 'Back to Course';
$string['sendcomplete'] = '<b>Sending SMS complete.</b>';
$string['char_remain'] = 'Characters remaining.';
$string['smstext'] = 'char';
$string['simplesms_host'] = 'Hostname';
$string['simplesms_hostdesc'] = 'Enter gateway hostname, eg: http://your-domain-name';
$string['simplesms_port'] = 'Port';
$string['simplesms_portdesc'] = 'Enter port number';
$string['simplesms_username'] = 'Username';
$string['simplesms_usernamedesc'] = 'Enter the username';
$string['simplesms_password'] = 'Password';
$string['simplesms_passworddesc'] = 'Enter the password';
$string['simplesms_url'] = 'URL';
$string['simplesms_urldesc'] = 'Enter the url, eg: /cgi-bin/sendsms';

$string['restrictedmsg'] = 'Oops! The SMS notifications service is available between 7am to 9pm daily. Please try again between these times.';
$string['err_msgrequired'] = 'Please enter a message to send.';
