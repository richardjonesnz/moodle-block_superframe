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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>;.
/**
 * block_superframe main file
 *
 * @package   block_superframe
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Modified for use in MoodleBites for Developers Level 1
 * by Richard Jones & Justin Hunt.
 */
namespace block_superframe\local;
defined('MOODLE_INTERNAL') || die();
/**
 * Class to fetch data from the database
 *
 * @package block_superframe
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see https://moodle.org/mod/forum/discuss.php?d=330687
 */
class block_data {
    public static function fetch_block_data() {
        global $DB;
        $sql = "SELECT b.id, cat.id AS catid, cat.name AS catname,
                b.blockname, c.shortname
                FROM {context} x
                JOIN {block_instances} b ON b.parentcontextid = x.id
                JOIN {course} c ON c.id = x.instanceid
                JOIN {course_categories} cat ON cat.id = c.category
                WHERE x.contextlevel <= :clevel
                ORDER BY b.blockname DESC";
        return $DB->get_records_sql($sql, ['clevel' => 80]);
    }

    public static function fetch_history() {
        global $DB;
        $sql = "SELECT v.id, v.lastaccess, u.firstname, u.lastname, c.shortname
                FROM {block_superframe_visits} v
                JOIN {user} u ON v.userid = u.id
                JOIN {course} c ON courseid = c.id";

        return $DB->get_records_sql($sql);
    }
}