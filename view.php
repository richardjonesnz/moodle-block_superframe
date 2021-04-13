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
use \block_superframe\event\block_page_viewed;
require('../../config.php');
$blockid = required_param('blockid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$size = optional_param('size','none', PARAM_TEXT);

$def_config = get_config('block_superframe');
$PAGE->set_course($COURSE);
$PAGE->set_url('/blocks/superframe/view.php');
$PAGE->set_heading($SITE->fullname);
$PAGE->set_pagelayout($def_config->pagelayout);
$PAGE->set_title(get_string('pluginname', 'block_superframe'));
$PAGE->navbar->add(get_string('pluginname', 'block_superframe'));

require_login();

// Check the users permissions to see the view page.
$context = context_course::instance($COURSE->id);
require_capability('block/superframe:seeviewpage', $context);

// If we get here they have viewed the page.
// Log the page viewed event.
$event = \block_superframe\event\block_page_viewed::create(['context' => $PAGE->context]);
$event->trigger();

// Add a history record to our block's visits table.
// NOTE: We couldn't use history as the max table length is 26 characters.
$data = new stdClass();
$data->userid = $USER->id;
$data->courseid = $courseid;
$data->lastaccess = time();
$DB->insert_record('block_superframe_visits', $data);

// Get the instance configuration data from the database.
// It's stored as a base 64 encoded serialized string.
$configdata = $DB->get_field('block_instances', 'configdata', ['id' => $blockid]);

// If an entry exists, convert to an object.
if ($configdata) {
    $config = unserialize(base64_decode($configdata));
} else {
    // No instance data, use admin settings.
    // However, that only specifies height and width, not size.
   $config = $def_config;
   $config->size = 'custom';
}

// Check the size optional parameter.
if ($size == 'none') {
    // First visit to page, use config.
    $size = $config->size;
}

// URL - comes either from instance or admin.
$url = $config->url;

// Let's set up the iframe attributes.
switch ($size) {
    case 'custom':
        $width = $def_config->width;
        $height = $def_config->height;
        break;
    case 'small' :
        $width = 360;
        $height = 240;
        break;
    case 'medium' :
        $width = 600;
        $height = 400;
        break;
    case 'large' :
        $width = 1024;
        $height = 720;
        break;
}
$renderer = $PAGE->get_renderer('block_superframe');
$renderer->display_view_page($url, $width, $height, $courseid, $blockid);