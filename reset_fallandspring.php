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
 * Prints a particular instance of istudy
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_istudyresetsyear
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/reset_fallandspring_form.php');

global $DB, $USER;

/**
 * Course module id.
 */
$id = optional_param('id', 0, PARAM_INT);

/**
 * Activity instance id.
 */
$i = optional_param('i', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('istudyresetsyear', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('istudyresetsyear', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleinstance = $DB->get_record('istudyresetsyear', array('id' => $i), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('istudyresetsyear', $moduleinstance->id, $course->id, false, MUST_EXIST);
}
$istudyresetsyearid = $cm->instance;

require_login($course, true, $cm);

$modulecontext = \context_module::instance($cm->id);

$PAGE->set_url('/mod/istudyresetsyear/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

$advisorid = $USER->advisorid;
$formparams = array('advisorid' => $advisorid);

// Output starts here.
echo $OUTPUT->header();

// Display form.
$mform = new mod_istudyresetsyear_reset_fallandspring_form();
$mform->set_data($istudyresetsyearid);
if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url);
	return;
}
else if ($data = $mform->get_data()) {
	// Get the form data.
	$data = $mform->get_data();

	// Get the course data.
	if ($data->resetconfirmation == 'RESET') {
		echo $OUTPUT->notification('The Fall and Spring semesters have been reset', 'notifysuccess');
		mod_istudyresetsyear\utils::clear_fallandspring();
	}
	else {
		echo $OUTPUT->notification('Operation failed - The Fall and Spring semesters were not reset');
		$url = new moodle_url('/course/view.php', array('id' => $course->id));
		redirect($url, 'Operation Failed', 5);
		return;
	}
}
else {
	// Form has not been submitted or there was an error.
	// Just display the form.
	$mform->set_data(array('id' => $id));
}

// Finish the page.
$mform->display();
echo $OUTPUT->footer();