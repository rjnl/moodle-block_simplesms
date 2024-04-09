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

class block_simplesms extends block_list {
    public function init() {
        $this->title = get_string('blocktitle', 'block_simplesms');
    }

    public function get_content() {
        global $CFG, $COURSE, $OUTPUT;

        if ($this->content !== null) {
          return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        // Restrict access between the following hours
        $from = '06';
        $to = '21';

        $context = context_course::instance($COURSE->id);
        $permission = has_capability('block/simplesms:cansend', $context);

        if ($permission)
        {
            if(!has_capability('moodle/site:config', context_system::instance()) && ((date('H') < $from) || (date('H') >= $to)))
            {
                $this->content->items[]  = '<div style="background:#FF9999;font-size:9pt; padding:3px; border:1px solid #C0311E;">'
                    . get_string('restrictedmsg', 'block_simplesms')
                    . '</div>';
                return $this->content;
            }

            $cparam = array('courseid' => $COURSE->id);

            $str = get_string('composesms', 'block_simplesms');

            //$icon = '<img src="'.$OUTPUT->pix_url('i/email') . '" class="icon" alt="" />&nbsp;';
            $icon = $OUTPUT->pix_icon('sms', '', 'block_simplesms'); // RT - new icon added

            $this->content->items[] = html_writer::link(new moodle_url('/blocks/simplesms/sms.php', $cparam), $icon . $str);

            //$this->content->items[] = html_writer::link(new moodle_url('/blocks/simplesms/sms.php', $cparam), $str);
            //$this->content->icons[] = $OUTPUT->pix_icon('i/email', $str);

            $str = get_string('messagehistory', 'block_simplesms');

            //$icon = '<img src="'.$OUTPUT->pix_url('i/log') . '" class="icon" alt="" />&nbsp;';
            $icon = $OUTPUT->pix_icon('i/log', '');
            $this->content->items[] = html_writer::link(new moodle_url('/blocks/simplesms/history.php', $cparam), $icon . $str);

            //$this->content->items[] = html_writer::link(new moodle_url('/blocks/simplesms/history.php', $cparam), $str);
            //$this->content->icons[] = $OUTPUT->pix_icon('i/log', $str);

        } // if($permission)

        return $this->content;
    } // get_content()

    // Allow configuration option in courses
    // Not needed when instance_allow_multiple() is set to true
    public function instance_allow_config() {
        return false;
    }

    public function instance_allow_multiple() {
        return false;
    }

    /* RT 17012013 - PHP strict standards error
    public function specialization() {
        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        } else {
            $this->config->title =  get_string('blocktitle', 'block_simplesms');
        }

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }

    }   // specialization()
    */

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('course-view' => true, 'site' => false, 'my' => false);
    }

    public function hide_header() {
        return false;
    }
}
