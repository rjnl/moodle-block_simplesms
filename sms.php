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

require_once('../../config.php');
require_once('lib.php');
require_once('sms_form.php');

require_login();

$courseid = required_param('courseid', PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('no_course', 'block_simplesms', '', $courseid);
}

$context = context_course::instance($courseid);

$has_permission = has_capability('block/simplesms:cansend', $context);

if (!$has_permission) {
    print_error('no_permission', 'block_simplesms');
}

$straction = get_string('composesms', 'block_simplesms');
$heading = $course->shortname . ': ' . $straction;

// Setting the Moodle page details
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->navbar->add($straction);
$PAGE->set_title($heading);
$PAGE->set_heading($heading);
$PAGE->set_url('/course/view.php', array('courseid' => $courseid));
$PAGE->set_pagetype('block-simplesms');

$PAGE->requires->js('/blocks/simplesms/js/selection.js');
$PAGE->requires->js('/blocks/simplesms/js/misc.js');

if(has_capability('moodle/site:accessallgroups', $context))
{
    $groups = groups_get_all_groups($courseid);
}
else
{
    $groups = groups_get_user_groups($courseid);
}

// Fill the course users by
$users = array();
$users_to_roles = array();
$users_to_groups = array();

$everyone = get_enrolled_users($context);

foreach ($everyone as $userid => $user) {
    $usergroups = groups_get_user_groups($courseid, $userid);

    $gids = array_values($usergroups['0']);

    $groupmapper = function($id) use ($groups) { return $groups[$id]; };

    $users_to_groups[$userid] = array_map($groupmapper, $gids);
    $users[$userid] = $user;
}

if (empty($users)) {
    print_error('no_users', 'block_simplesms');
}

$selected = array();
if (!empty($data->sendto)) {
    foreach(explode(',', $data->sendto) as $id) {
        $selected[$id] = $users[$id];
        unset($users[$id]);
    }
}

$form = new sms_form(null, array(
    'selected' => $selected,
    'users' => $users,
    'groups' => $groups,
    'users_to_groups' => $users_to_groups
));

$warnings = array();

// Redirect before outputting anything on page
if($data = data_submitted())
{
    if (isset($data->cancel))
    {
         redirect(new moodle_url('/course/view.php?id='.$courseid));
    }

    if (empty($data->go)) {
        redirect(new moodle_url('/blocks/simplesms/sms.php', array('courseid' => $courseid)));
    }
}

echo $OUTPUT->header();

if($data = data_submitted())
{
    if (empty($data->message)) {
        $warnings[] = get_string('no_message', 'block_simplesms');
    }

    if (empty($data->sendto)) {
        $warnings[] = get_string('no_users', 'block_simplesms');
    }

    if (empty($warnings))
    {
        $sms = new stdClass;
        $sms->id = null;
        $sms->courseid = $courseid;
        $sms->userid = $USER->id;
        $sms->message = substr($course->shortname, 0,5) . ': ' . $data->message;
        $sms->sendto = $data->sendto;
        $sms->time = time();

        // Now send sms to each user in the list
        $fail_count = 0; // Users with empty phone2 field.
        $success_count = 0;
        foreach (explode(',', $data->sendto) as $userid)
        {
            $success = false;

            if(!empty($everyone[$userid]->phone2) && simplesms_is_phone_number($everyone[$userid]->phone2))
            {
                $success = simplesms_sendsms($everyone[$userid]->phone2, $sms->message);
            }
            /*else
            {
                $fail_count++;
            }*/

            if(!$success) {
                $fail_count++;
                $warnings[] = get_string("no_mail", 'block_simplesms', $everyone[$userid]);
            }
            else
            {
                $success_count++;
            }
        }// foreach

        $sms->successcount = $success_count;
        $DB->insert_record('block_simplesms_log', $sms);

        $optionyes = new moodle_url('/blocks/simplesms/sms.php', array('courseid' => $courseid));
        $optionno = new moodle_url('/course/view.php', array('id' => $courseid));

        $msg = get_string('sendcomplete', 'block_simplesms') . '<br />';

        if($fail_count > 0)
        {
            $msg .= get_string("sendfail", 'block_simplesms', $fail_count) . '<br /><br />';
        }

        $msg .= get_string('sendanother', 'block_simplesms');

        echo $OUTPUT->confirm($msg, $optionyes, $optionno);
        exit;
    }
    else
    {
        foreach ($warnings as $type => $warning) {
            $class = ($type == 'success') ? 'notifysuccess' : 'notifyproblem';
            echo $OUTPUT->notification($warning, $class);
        }
    }
}
else
{
     if(has_capability('block/simplesms:cansend', $context)) {
        $history_link = html_writer::link(
            new moodle_url('history.php', array('courseid' => $COURSE->id)), get_string('messagehistory', 'block_simplesms'));
        $courselink = html_writer::link(
            new moodle_url('/course/view.php', array('id' => $COURSE->id)), get_string('backtocourse', 'block_simplesms'));

        echo $OUTPUT->box_start('', 'smsactions')
            . $history_link . ' ' . $courselink
            . $OUTPUT->box_end();
    }

    $form->display();
}

echo $OUTPUT->footer();
