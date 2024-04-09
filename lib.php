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

function simplesms_sendsms($in_phoneNumber, $in_msg)
{
    global $CFG;
    // Remove any whitespaces
    $in_phoneNumber = trim($in_phoneNumber);
    $in_msg = trim($in_msg);

   $url = $CFG->simplesms_url .'?username=' . $CFG->simplesms_username . '&password=' . $CFG->simplesms_password
        . '&to=' . urlencode(iconv('UTF-8', 'ASCII', $in_phoneNumber))
        . '&text=' . urlencode(iconv('UTF-8', 'ASCII', $in_msg));

   $results = file_get_contents( $CFG->simplesms_host . ':' . $CFG->simplesms_port . $url);

    return $results;
}

function simplesms_format_date($time)
{
    return date("d/m/Y, h:i A", $time);
}

function simplesms_list_entries($courseid, $page, $perpage, $userid, $count, $candelete)
{
    global $CFG, $DB, $OUTPUT;

    $dbtable = 'block_simplesms_log';

    $table = new html_table();
    $table->id = 'block_simplesms_list';

    if(has_capability('moodle/site:config', context_system::instance()))
    {
        $params = array('courseid' => $courseid);
    }
    else
    {
        $params = array('courseid' => $courseid, 'userid' => $userid);
    }

    $logs = $DB->get_records($dbtable, $params, 'time DESC', '*', $page * $perpage, $perpage * ($page + 1));

    $table->head = array(get_string('date'), get_string('message', 'block_simplesms'), get_string('action'));

    $table->data = array();

    foreach ($logs as $log)
    {
        $date = simplesms_format_date($log->time);
        $message = $log->message;

        $params = array(
            'courseid' => $log->courseid,
            'itemid' => $log->id,
            'action' => 'delete'
        );

        $delete_link = '';

        if($candelete)
        {
            $strmsg = get_string('deletemessage', 'block_simplesms');
            $delete_link = html_writer::link(new moodle_url('/blocks/simplesms/history.php', $params),
                $OUTPUT->pix_icon('t/delete', ''), array('title' => $strmsg)
            );
        }

        $table->data[] = array($date, $message, $delete_link);
    }

    $paging = $OUTPUT->paging_bar($count, $page, $perpage, '/blocks/simplesms/history.php?courseid='.$courseid);

    $html = $paging;
    $html .= html_writer::table($table);
    $html .= $paging;

    return $html;
}

function simplesms_delete_dialog($courseid, $itemid) {
    global $CFG, $DB, $USER, $OUTPUT;

    $data = $DB->get_record('block_simplesms_log', array('id' => $itemid));

    if (empty($data))
        print_error('not_valid_itemid', 'block_simplesms');

    $params = array('courseid' => $courseid);
    $yes_params = $params + array('itemid' => $itemid, 'action' => 'confirm');

    $optionyes = new moodle_url('/blocks/simplesms/history.php', $yes_params);
    $optionno = new moodle_url('/blocks/simplesms/history.php', $params);

    $table = new html_table();
    $table->head = array(get_string('date'), get_string('message', 'block_simplesms'));
    $table->data = array(
        new html_table_row(array(
            new html_table_cell(simplesms_format_date($data->time)),
            new html_table_cell($data->message))
        )
    );

    $msg = get_string('delete_confirm', 'block_simplesms', html_writer::table($table));

    $html = $OUTPUT->confirm($msg, $optionyes, $optionno);

    return $html;
}

function simplesms_delete_message($table, $itemid)
{
    global $DB;

    $result = ($DB->delete_records($table, array('id' => $itemid) ));

    return $result;
}

function simplesms_options($courseid, $sendpermission)
{
    $link = '';
    if($sendpermission)
    {
        $link .= html_writer::link(new moodle_url('sms.php', array('courseid' => $courseid)),
            get_string('composeanothersms', 'block_simplesms'));
        $link .= ' ';
    }
    $link .= html_writer::link(new moodle_url('/course/view.php', array('id' => $courseid)),
        get_string('backtocourse', 'block_simplesms'));

    return $link;
}

function simplesms_is_phone_number($phone)
{
    if (preg_replace("/[^0-9]/", "", $phone))
        return true;

    return false;
}
