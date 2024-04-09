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

function xmldb_block_simplesms_upgrade($oldversion) {
    global $DB;

    $result = true;

    $dbman = $DB->get_manager();


    if ($oldversion < 2012121203) {

         // Define field successcount to be added to block_simplesms_log
        $table = new xmldb_table('block_simplesms_log');
        $field = new xmldb_field('successcount', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'time');

        // Conditionally launch add field successcount
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // simplesms savepoint reached
        upgrade_block_savepoint(true, 2012121203, 'simplesms');
    }

    return $result;
}
