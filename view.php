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
 * Prints a particular instance of istudyresetsyear
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_istudyresetsyear
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace istudyresetsyear with the name of your module and remove this line.

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/choose_term_form.php');

global $DB;

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

// The user must be an administrator.
if (mod_istudyresetsyear\teacher_info::not_an_administrator()) {
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url, 'You must be an adminstrator to access this page...', 5);
}

// Output starts here.
echo $OUTPUT->header();

// Display form.
$mform = new mod_istudyresetsyear_choose_term_form();
$mform->set_data($istudyresetsyearid);
if ($mform->is_cancelled()) {
	// Cancelled forms redirect to the course main page.
	$url = new moodle_url('/course/view.php', array('id' => $course->id));
	redirect($url, 'Returning to Course Page', 1);
} else if ($data = $mform->get_data()) {
	$school_term = $data->schoolterm;
	if ($school_term == 1) {
		echo $OUTPUT->notification('You must choose one of the options.');
	}
	else if ($school_term == 2) {
		// Redirect to the Moodle -> users backup page.
		$url = new moodle_url('/admin/user/user_bulk.php', array('id' => $course->id));
		redirect($url);
	}
	else if ($school_term == 3) {
		$choose_fallandspring = new moodle_url('/mod/istudyresetsyear/reset_fallandspring.php', array('id' => $cm->id));
		redirect($choose_fallandspring);
	}
	else if ($school_term == 4) {
		$choose_summer = new moodle_url('/mod/istudyresetsyear/reset_summer.php', array('id' => $cm->id));
		redirect($choose_summer);
	}
	else if ($school_term == 5) {
		$choose_attendance = new moodle_url('/mod/istudyresetsyear/reset_dailyattendance.php', array('id' => $cm->id));
		redirect($choose_attendance);
	}
	else if ($school_term == 6) {
		$choose_attendance = new moodle_url('/mod/istudyresetsyear/transfer_falltospring.php', array('id' => $cm->id));
		redirect($choose_attendance);
	}
} else {
	// Form has not been submitted or there was an error.
	// Just display the form.
	$mform->set_data(array('id' => $id));
}

$mform->display();
echo $OUTPUT->footer();