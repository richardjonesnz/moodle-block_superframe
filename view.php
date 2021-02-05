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
 * Superframe view page
 *
 * @package    block_superframe
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * Modified for use in MoodleBites for Developers Level 1 by Richard Jones & Justin Hunt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../config.php');
$config = get_config('block_superframe');
$PAGE->set_course($COURSE);
$PAGE->set_url('/blocks/superframe/view.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout($config->pagelayout);
$PAGE->set_title(get_string('pluginname', 'block_superframe'));
$PAGE->navbar->add(get_string('pluginname', 'block_superframe'));
require_login();

// Start output to browser.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'block_superframe'), 5);

// Build and display an iframe.
$attributes = ['src' => $config->url,
               'width' => $config->width,
               'height' => $config->height];
echo html_writer::start_tag('iframe', $attributes);
echo html_writer::end_tag('iframe');

// Send footer out to browser.
echo $OUTPUT->footer();