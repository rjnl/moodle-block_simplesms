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

require_login();

$courseid = required_param('courseid', PARAM_INT);

$action = optional_param('action', null, PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 10, PARAM_INT);
$userid = optional_param('userid', $USER->id, PARAM_INT);
$itemid = optional_param('itemid', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('no_course', 'block_simplesms', '', $courseid);
}

$context = context_course::instance($courseid);

$permission = has_capability('block/simplesms:cansend', $context);
$candelete = has_capability('block/simplesms:candelete', $context);

if (!$permission) {
    print_error('no_permission', 'block_simplesms');
}

if (isset($action) and !in_array($action, array('delete', 'confirm'))) {
    print_error('not_valid_action', 'block_simplesms', '', $action);
}

if (isset($action) and empty($itemid)) {
    print_error('not_valid_itemid', 'block_simplesms', '', $action);
}

$straction = get_string('messagehistory', 'block_simplesms');
$heading = $course->shortname . ': ' . $straction;

// Setting the Moodle page details
$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->navbar->add($straction);
$PAGE->set_title($heading);
$PAGE->set_heading($heading);
$PAGE->set_url('/course/view.php', array('courseid' => $courseid));
$PAGE->set_pagetype('block-simplesms');

$params = array('userid' => $userid, 'courseid' => $courseid);

if(has_capability('moodle/site:config', context_system::instance()))
{
    $params = array('courseid' => $courseid);
}
else
{
    $params = array('userid' => $userid, 'courseid' => $courseid);
}

$count = $DB->count_records('block_simplesms_log', $params);

switch ($action)
{
    case "confirm":
        if(simplesms_delete_message('block_simplesms_log', $itemid)) {
            $url = new moodle_url('/blocks/simplesms/history.php', array('courseid' => $courseid ));
            redirect($url);
        } else
            print_error('delete_failed', 'block_simplesms', '', $itemid);
    case "delete":
        $html = simplesms_delete_dialog($courseid, $itemid);
        break;
    default:
        $html = simplesms_list_entries($courseid, $page, $perpage, $userid, $count, $candelete);
        break;
}

echo $OUTPUT->header();
//echo $OUTPUT->heading($straction);
echo $OUTPUT->box_start('', 'smsactions')
    . simplesms_options($courseid, $permission)
    . $OUTPUT->box_end();
echo $html;

echo $OUTPUT->footer();
