<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will   be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is a one page wonder table manager
 *
 * @package    block_superframe
 * @copyright  2015 Flash Gordon http://www.flashgordon.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir . '/formslib.php');

/**
 * A function that will display any set of records from the $DB
 * (as long as each record has an id field)
 */
function block_superframe_display_in_table($data){

    // If we do not have any data, lets just return a string to that effect.
    if(!$data || empty($data)){
        return 'No records found';
    }

    // Make sure that we are an array.
    if(!is_array($data)){
         $data = array($data);
    }

    $head = false;
    $baselink = '/blocks/superframe/tablemanager.php';
    $table = new html_table();
    foreach($data as $onedata){
        $onearray = get_object_vars($onedata);

        // Build the head row.
        if(!$head){
            $head = true;
            $table->head = array_keys($onearray);
            $table->head[] = get_string('edit');
            $table->head[] = get_string('delete');
        }
        // Build all the other rows, adding links at the end.
        $rowdata = array_values($onearray);
        $editlink = html_writer::link(new moodle_url($baselink, ['id' => $onearray['id'],
                'action' => 'edit']), get_string('edit'));
        $rowdata[] = $editlink;

        $deletelink = html_writer::link( new moodle_url($baselink,['id' => $onearray['id'],
                'action' => 'delete']), get_string('delete'));
        $rowdata[] = $deletelink;

        $table->data[]=$rowdata;
    }

    return html_writer::table($table);

 } // End of display in table.

/**
 * Define a form that acts on just one field, e.g "name", in an existing table
 */
class superframe_tablemanager_form extends moodleform {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Adding the standard "name" field.
        $fieldname ="fullname";
        $mform->addElement('text', $fieldname, $fieldname,
                array('size'=>'64'));
        $mform->setType($fieldname, PARAM_TEXT);
        $mform->addRule($fieldname, null, 'required', null, 'client');
        $mform->addRule($fieldname, get_string('maximumchars', '', 255),
                'maxlength', 255, 'client');

        $mform->addElement('hidden','action','update');
        $mform->setType('action',PARAM_TEXT);
        $mform->addElement('hidden','id');
        $mform->setType('id',PARAM_INT);
        $this->add_action_buttons();
    }
}

// The table to work with.
$tablename = "course";
$fieldname = "fullname";  // This should match the name in the function definition
$fieldlist =   'id, fullname, shortname';  // Used to select which fields to return.
$pagetitle = "Table Manager: ";

// Fetch URL parameters.
$action = optional_param('action','list',PARAM_TEXT);
$actionitem = optional_param('id',0,PARAM_INT);


// Set course related variables.
$PAGE->set_course($COURSE);
$course = $DB->get_record('course', array('id' => $COURSE->id), '*', MUST_EXIST);
$coursecontext = context_course::instance($course->id);

// Set up the page.
$PAGE->set_url('/blocks/superframe/tablemanager.php', array());
$PAGE->set_context($coursecontext);
$PAGE->set_pagelayout('course');
$renderer = $PAGE->get_renderer('block_superframe');

//=========================================
// Form processing begins here
//=========================================

// Get the tablemanager form.
$mform = new superframe_tablemanager_form();

//if the cancel button was pressed, we are out of here
if ($mform->is_cancelled()) {
    redirect($PAGE->url,get_string('cancelled'),2);
    exit;
}

//if we have data, then our job here is to save it and return
if ($data = $mform->get_data()) {
    $DB->update_record($tablename,$data);
    redirect($PAGE->url,get_string('updated','core',$data->{$fieldname}),2);
}

//=========================================
// Page output begins here
//=========================================
echo $renderer->header();

// If the action is specified as "edit" then we show the edit form
if ($action == "edit"){

    // Create some data for our form and set it to the form.
    $data = new stdClass();
    $data = $DB->get_record($tablename,array('id'=>$actionitem));

    if (!$data) { // In case there isn't any data in your chosen table.
        redirect($PAGE->url, 'nodata', 2);
    }

    $mform->set_data($data);
    // Header for the page.

    echo $renderer->heading($pagetitle  . $tablename,2);

    // Output page and form.
    $mform->display();
}

//===============================
// List of items here begins here.
//================================

echo $renderer->heading($pagetitle  . $tablename, 2);

// Just take some relevant fields, if we are in edit mode, just get one matching record.
if ($action == "edit") {
    $alldata = $DB->get_records($tablename, ['id' => $actionitem], null, $fieldlist);
}
else  // Get all the records in the table.
{
    $alldata = $DB->get_records($tablename, [], null, $fieldlist);
}

// Call the function at the top of the page to display an html table.
echo block_superframe_display_in_table($alldata);
echo $renderer->footer();

return;