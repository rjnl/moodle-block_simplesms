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

require_once($CFG->libdir . '/formslib.php');

class sms_form extends moodleform {

    private function reduce_users($in, $user) {
        return $in . '<option value="'.$this->option_value($user).'">'.
               $this->option_display($user).'</option>';
    }

    private function option_display($user) {
        $users_to_groups = $this->_customdata['users_to_groups'];

        if (empty($users_to_groups[$user->id])) {
            $groups = get_string('no_group', 'block_simplesms');
        } else {
            $only_names = function($group) {
                return (strlen($group->name)>20 ? (substr($group->name,0,17) . '...') : $group->name);
            };
            $groups = implode(',', array_map($only_names, $users_to_groups[$user->id]));
        }

        return sprintf("%s - %s (%s)", $user->idnumber, fullname($user), $groups);
    }

    private function option_value($user) {
        $users_to_groups = $this->_customdata['users_to_groups'];

        if (empty($users_to_groups[$user->id])) {
            $groups = 0;
        } else {
            $only_id = function($group) { return $group->id; };
            $groups = implode(',', array_map($only_id, $users_to_groups[$user->id]));
            $groups .= ',all';
        }

        return sprintf("%s %s", $user->id, $groups);
    }

    public function definition() {
        global $CFG, $USER, $COURSE, $OUTPUT;

        $limitchars = 153;

        $mform =& $this->_form;

        $mform->addElement('hidden', 'sendto', '');
        $mform->setType('sendto', PARAM_TEXT);
        $mform->addElement('hidden', 'userid', $USER->id);
        $mform->setType('userid', PARAM_INT);
        $mform->addElement('hidden', 'courseid', $COURSE->id);
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'go', '1');
        $mform->setType('go', PARAM_INT);

         $group_options = empty($this->_customdata['groups']) ? array() : array(
            'all' => get_string('all_groups', 'block_simplesms')
        );
        foreach ($this->_customdata['groups'] as $group) {
            $group_options[$group->id] = $group->name;
        }
        $group_options[0] = get_string('no_group', 'block_simplesms');

         $user_options = array();
        foreach ($this->_customdata['users'] as $user) {
            $user_options[$this->option_value($user)] = $this->option_display($user);
        }

        $links = array();

        $context= context_course::instance($COURSE->id);

        $html = '<div class="no-overflow"><table class="generaltable generalbox">'
                . ' <tr>'
                .   '<td>'
                .       get_string('selected', 'block_simplesms')
                .       '<img class="req" title="' . get_string('required', 'block_simplesms') . '" alt="' . get_string('required', 'block_simplesms') . '" src="'.$OUTPUT->image_url('req').'"/>' . '<br />'
                .       '<select id="send_users" multiple="multiple" size="20">'
                //.         '<option value="0"></option>'
                .           array_reduce($this->_customdata['selected'], array($this, 'reduce_users'), '')
                .       '</select>'
                .   '</td>'
                .   '<td id="col_option">'
                .       '<p><strong>' .  get_string('potential_groups', 'block_simplesms') . '</strong><br />'
                .           '<select id="groups" multiple="multiple" size="5">'
                .               (empty($this->_customdata['groups']) ? '' :
                                 '<option selected="selected" value="all">'.get_string('all_groups', 'block_simplesms')).'</option>'
                .               array_reduce($this->_customdata['groups'], function($in, $group) {
                                    return $in . '<option value="'.$group->id.'">'.$group->name.'</option>';
                                 }, '')
                .               '<option value="0">'.get_string('no_group', 'block_simplesms').'</option>'
                .           '</select>'
                .       '</p>'
                .       '<p><input type="button" id="add_button" value="'.$OUTPUT->larrow().' '
                .           get_string('add_button', 'block_simplesms') .'" disabled="disabled" /></p>'
                .       '<p><input type="button" id="remove_button" value="'
                .           get_string('remove_button', 'block_simplesms').' '.$OUTPUT->rarrow().'" disabled="disabled" /></p>'
                .       '<p><input type="button" id="add_all" value="'.get_string('add_all', 'block_simplesms').'"/></p>'
                .       '<p><input type="button" id="remove_all" value="'.get_string('remove_all', 'block_simplesms').'"/></p>'
                .   '</td>'
                .   '<td>'
                .       get_string('potential_users', 'block_simplesms') . '<br />'
                .       '<select id="from_users" multiple="multiple" size="20">'
                .           array_reduce($this->_customdata['users'], array($this, 'reduce_users'), '')
                .       '</select>'
                . '</td>'
                . '</tr>'
                . '</table></div>';

        $mform->addElement('html', $html);

        $mform->addElement('textarea', 'message', get_string('message', 'block_simplesms'), 'rows="5" cols="30"');
        $mform->setType('message', PARAM_TEXT);
        $mform->addRule('message', get_string('err_msgrequired', 'block_simplesms'), 'required', null, 'client');

        $html = '<span id="charlimitinfo">' . $limitchars . '</span> <span id="simplesmschars">'
            . get_string('char_remain', 'block_simplesms') . '</span>';
        $mform->addElement('static', 'chars','', $html);

        $this->add_action_buttons(true, get_string('send', 'block_simplesms'));
    }
}
