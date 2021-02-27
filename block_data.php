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
 * Superframe page to list blocks.
 *
 * @package    block_superframe
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * Modified for use in MoodleBites for Developers Level 1 by Richard Jones & Justin Hunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use \block_superframe\local\block_data;
require('../../config.php');

$PAGE->set_url('/blocks/superframe/block_data.php');
require_login();
$PAGE->set_course($COURSE);
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout('popup');
$PAGE->set_title(get_string('pluginname', 'block_superframe'));
// Let's get some data about blocks.
$records = block_data::fetch_block_data();
$renderer = $PAGE->get_renderer('block_superframe');
$renderer->display_block_table($records);