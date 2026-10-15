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
 * @package    mod_istudyresetsyear
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_istudyresetsyear;

use stdClass;

/**
 * Class utils
 *
 * @package    mod_istudyresetsyear
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class utils {

	// Get the user_info_field id to use in searching user_info_data.
	public static function get_fieldid($item_name) {
		global $DB;
		$item = $DB->get_record('user_info_field', ['shortname' => $item_name], 'id');
		return $item->id;
	}

	// Search user_info_data for student data using the student id and user_info_field id.
	public static function get_field_data($student_id, $itemid) {
		global $DB;
		if ($DB->record_exists('user_info_data', ['userid'=>$student_id, 'fieldid'=>$itemid])) {
			$search_results = $DB->get_record('user_info_data', ['userid'=>$student_id, 'fieldid'=>$itemid], 'data');
			return $search_results->data;
		}
		else {
			return '';
		}
	}

	// Fall & Spring - Search user_info_data for student data using the student id and user_info_field id.
	public static function clear_fallandspring() {
		global $DB, $OUTPUT;
		// Reset the fall semester --> a) set all courses to none, credits to zero, and term year to the new school year or 1929.
		$week_day[1] = 'monday';
		$week_day[2] = 'tuesday';
		$week_day[3] = 'wednesday';
		$week_day[4] = 'thursday';
		$week_day[5] = 'friday';
		$number_of_sessions = get_config('mod_istudy', 'number_of_sessions');

		// Reset the fall courses to None.
		for ($d = 1; $d <= 5; $d++) {
			for ($t = 1; $t <= $number_of_sessions; $t++) {
				$this_session_course = 'fall' . $week_day[$d] . 'session' . $t;
				$fall_session_id = utils::get_fieldid($this_session_course);
				// Get all.
				$fall_course = $DB->get_records('user_info_data', ['fieldid'=>$fall_session_id], '', '*');
				foreach ($fall_course as $this_fall_course) {
					$this_fall_course->data = 'None';
					// Write the new files.
					try {
						$transaction = $DB->start_delegated_transaction();
						$DB->update_record('user_info_data', $this_fall_course, false);
						// Assuming that one of the inserts worked, we get to the following line.
						$transaction->allow_commit();
					}
					catch(Exception $e) {
						$transaction->rollback($e);
						$this_student = student_info::get_single_student($this_fall_course->userid);
						$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
						echo $OUTPUT->notification($student_with_error);
					}
				}
			}
		}
		// Reset the fall course credits to Zero.
		for ($d = 1; $d <= 5; $d++) {
			for ($t = 1; $t <= $number_of_sessions; $t++) {
				$this_session_credits = 'fall' . $week_day[$d] . 'creditssession' . $t;
				$fall_credits_id = utils::get_fieldid($this_session_credits);
				// Get all.
				$fall_course_credits = $DB->get_records('user_info_data', ['fieldid'=>$fall_credits_id], '', '*');
				foreach ($fall_course_credits as $this_fall_credits) {
					$this_fall_credits->data = '0';
					// Write the new files.
					try {
						$transaction = $DB->start_delegated_transaction();
						$DB->update_record('user_info_data', $this_fall_credits, false);
						// Assuming that one of the inserts worked, we get to the following line.
						$transaction->allow_commit();
					}
					catch(Exception $e) {
						$transaction->rollback($e);
						$this_student = student_info::get_single_student($this_fall_credits->userid);
						$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
						echo $OUTPUT->notification($student_with_error);
					}
				}
			}
		}
		// Reset the spring courses to None.
		for ($d = 1; $d <= 5; $d++) {
			for ($t = 1; $t <= $number_of_sessions; $t++) {
				$this_session_course = 'spring' . $week_day[$d] . 'session' . $t;
				$spring_session_id = utils::get_fieldid($this_session_course);
				// Get all.
				$spring_course = $DB->get_records('user_info_data', ['fieldid'=>$spring_session_id], '', '*');
				foreach ($spring_course as $this_spring_course) {
					$this_spring_course->data = 'None';
					// Write the new files.
					try {
						$transaction = $DB->start_delegated_transaction();
						$DB->update_record('user_info_data', $this_spring_course, false);
						// Assuming that one of the inserts worked, we get to the following line.
						$transaction->allow_commit();
					}
					catch(Exception $e) {
						$transaction->rollback($e);
						$this_student = student_info::get_single_student($this_spring_course->userid);
						$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
						echo $OUTPUT->notification($student_with_error);
					}
				}
			}
		}
		// Reset the spring course credits to Zero.
		for ($d = 1; $d <= 5; $d++) {
			for ($t = 1; $t <= $number_of_sessions; $t++) {
				$this_session_credits = 'spring' . $week_day[$d] . 'creditssession' . $t;
				$spring_credits_id = utils::get_fieldid($this_session_credits);
				// Get all.
				$spring_course_credits = $DB->get_records('user_info_data', ['fieldid'=>$spring_credits_id], '', '*');
				foreach ($spring_course_credits as $this_spring_credits) {
					$this_spring_credits->data = '0';
					// Write the new files.
					try {
						$transaction = $DB->start_delegated_transaction();
						$DB->update_record('user_info_data', $this_spring_credits, false);
						// Assuming that one of the inserts worked, we get to the following line.
						$transaction->allow_commit();
					}
					catch(Exception $e) {
						$transaction->rollback($e);
						$this_student = student_info::get_single_student($this_spring_credits->userid);
						$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
						echo $OUTPUT->notification($student_with_error);
					}
				}
			}
		}
		// Reset the label for distance learning to NO. All students will start with NO for the new school year.
		$distancelearningonly_id = utils::get_fieldid('distancelearningonly');
		$all_distance_learners = $DB->get_records('user_info_data', ['fieldid'=>$distancelearningonly_id], '', '*');
		foreach ($all_distance_learners as $distance_learner) {
			if ($distance_learner->data == 'Yes') {
				$distance_learner->data = 'No';
				// Write the new files
				try {
					$transaction = $DB->start_delegated_transaction();
					$DB->update_record('user_info_data', $distance_learner, false);
					// Assuming that one of the inserts worked, we get to the following line.
					$transaction->allow_commit();
				}
				catch(Exception $e) {
					$transaction->rollback($e);
					$this_student = student_info::get_single_student($distance_learner->userid);
					$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
					echo $OUTPUT->notification($student_with_error);
				}
			}
		}
		// Delete all records in the istudy_monthatten database table - "DELETE FROM `yfqt_istudy_monthatten` WHERE `yfqt_istudy_monthatten`.`id` = 78".
		$DB->delete_records('istudy_monthatten', null);
		$DB->delete_records('istudy_dailyattend_enter', null);
	}

	public static function clear_daily_attendance() {
		global $DB;
		$DB->delete_records('istudy_dailyattend_enter', null);
	}

	// Summer - Search user_info_data for student data using the student id and user_info_field id.
	public static function clear_summer() {
		global $DB, $OUTPUT;
		// Reset the summer semester --> a) set all courses to none, credits to zero, and term year to the new school year or 1929.
		$week_day[1] = 'monday';
		$week_day[2] = 'tuesday';
		$week_day[3] = 'wednesday';
		$week_day[4] = 'thursday';
		$week_day[5] = 'friday';
		$number_of_sessions = get_config('mod_istudy', 'number_of_sessions');
		// Reset the summer courses to None.
		for ($d = 1; $d <= 5; $d++) {
			for ($t = 1; $t <= $number_of_sessions; $t++) {
				$this_session_course = 'summer' . $week_day[$d] . 'session' . $t;
				$summer_session_id = utils::get_fieldid($this_session_course);
				// Get all.
				$summer_course = $DB->get_records('user_info_data', ['fieldid'=>$summer_session_id], '', '*');
				foreach ($summer_course as $this_summer_course) {
					$this_summer_course->data = 'None';
					// Write the new files.
					try {
						$transaction = $DB->start_delegated_transaction();
						$DB->update_record('user_info_data', $this_summer_course, false);
						// Assuming that one of the inserts worked, we get to the following line.
						$transaction->allow_commit();
					}
					catch(Exception $e) {
						$transaction->rollback($e);
						$this_student = student_info::get_single_student($this_summer_course->userid);
						$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
						echo $OUTPUT->notification($student_with_error);
					}
				}
			}
		}
		// Reset the summer course credits to Zero.
		for ($d = 1; $d <= 5; $d++) {
			for ($t = 1; $t <= $number_of_sessions; $t++) {
				$this_session_credits = 'summer' . $week_day[$d] . 'creditssession' . $t;
				$summer_credits_id = utils::get_fieldid($this_session_credits);
				// Get all
				$summer_course_credits = $DB->get_records('user_info_data', ['fieldid'=>$summer_credits_id], '', '*');
				foreach ($summer_course_credits as $this_summer_credits) {
					$this_summer_credits->data = 0;
					// Write the new files.
					try {
						$transaction = $DB->start_delegated_transaction();
						$DB->update_record('user_info_data', $this_summer_credits, false);
						// Assuming that one of the inserts worked, we get to the following line.
						$transaction->allow_commit();
					}
					catch(Exception $e) {
						$transaction->rollback($e);
						$this_student = student_info::get_single_student($this_summer_credits->userid);
						$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
						echo $OUTPUT->notification($student_with_error);
					}
				}
			}
		}
	}

	// Fall & Spring - Search user_info_data for student data using the student id and user_info_field id.
	public static function transfer_falltospring_now() {
		global $DB, $OUTPUT;
		// Reset the fall semester --> a) set all courses to none, credits to zero, and term year to the new school year or 1929.
		$week_day[1] = 'monday';
		$week_day[2] = 'tuesday';
		$week_day[3] = 'wednesday';
		$week_day[4] = 'thursday';
		$week_day[5] = 'friday';
		$number_of_sessions = get_config('mod_istudy', 'number_of_sessions');
		$fall_course = new stdClass();
		$spring_course = new stdClass();
		$fall_course_credits = new stdClass();
		$spring_course_credits = new stdClass();
		// Find all active students.
		$allstudents = student_info::get_studentlist();
		// Reset the fall courses to None
		if ($allstudents <> NULL) {
			// Get the database session id for each fall and spring courses and credits.
			$fall_session_course[][] = array() ;
			$fall_session_credits[][] = array() ;
			$spring_session_course[][] = array() ;
			$spring_session_credits[][] = array() ;
			for ($d = 1; $d <= 5; $d++) {
				for ($t = 1; $t <= $number_of_sessions; $t++) {
					$fall_session_course = 'fall' . $week_day[$d] . 'session' . $t;
					$fall_session_courseid[$d][$t] = utils::get_fieldid($fall_session_course);
					$fall_session_credits = 'fall' . $week_day[$d] . 'creditssession' . $t;
					$fall_session_creditsid[$d][$t] = utils::get_fieldid($fall_session_credits);
					$spring_session_course = 'spring' . $week_day[$d] . 'session' . $t;
					$spring_session_courseid[$d][$t] = utils::get_fieldid($spring_session_course);
					$spring_session_credits = 'spring' . $week_day[$d] . 'creditssession' . $t;
					$spring_session_creditsid[$d][$t] = utils::get_fieldid($spring_session_credits);
				}
			}
			// Get the fall courses and credits -> then transfer them to the spring.
			foreach ($allstudents as $this_studentid) {
				for ($d = 1; $d <= 5; $d++) {
					for ($t = 1; $t <= $number_of_sessions; $t++) {
						// Get the fall course and transfer it to the spring - transfer for this day and session.
						if ($DB->record_exists('user_info_data', array ('fieldid'=>$fall_session_courseid[$d][$t], 'userid'=>$this_studentid))) {
							$fall_course = $DB->get_record('user_info_data', ['fieldid'=>$fall_session_courseid[$d][$t], 'userid'=>$this_studentid], 'id, userid, fieldid, data');
							if (is_null($fall_course->data)) {
								$this_course_name = 'None';
							}
							else {
								$this_course_name = $fall_course->data;
							}
						}
						else {
							$this_course_name = 'None';
						}
						// Write the new files.
						try {
							if ($DB->record_exists('user_info_data', array ('fieldid'=>$spring_session_courseid[$d][$t], 'userid'=>$this_studentid))) {
								$spring_course = $DB->get_record('user_info_data', ['fieldid'=>$spring_session_courseid[$d][$t], 'userid'=>$this_studentid], 'id, userid, fieldid, data');
								$spring_course->data = $this_course_name;
								$transaction = $DB->start_delegated_transaction();
								$DB->update_record('user_info_data', $spring_course, false);
								// Assuming that the update worked, we get to the following line.
								$transaction->allow_commit();
							}
							else {
								$spring_course->fieldid = $spring_session_courseid[$d][$t];
								$spring_course->userid = $this_studentid;
								$spring_course->data = $this_course_name;
								$DB->insert_record('user_info_data', $spring_course, true, false);
							}
						}
						catch(Exception $e) {
							$transaction->rollback($e);
							$this_student = student_info::get_single_student($this_studentid);
							$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
							echo $OUTPUT->notification($student_with_error);
						}
						// Get the fall course credits and transfer tehm to the spring - transfer for this day and session.
						// Get the fall course and transfer it to the spring - transfer for this day and session.
						if ($DB->record_exists('user_info_data', array ('fieldid'=>$fall_session_creditsid[$d][$t], 'userid'=>$this_studentid))) {
							$fall_course_credits = $DB->get_record('user_info_data', ['fieldid'=>$fall_session_creditsid[$d][$t], 'userid'=>$this_studentid], 'id, userid, fieldid, data');
							if (is_null($fall_course_credits->data)) {
								$this_course_credits = '0';
							}
							else {
								$this_course_credits = $fall_course_credits->data;
							}
						}
						else {
							$this_course_credits = '0';
						}
						// Write the new files.
						try {
							if ($DB->record_exists('user_info_data', array ('fieldid'=>$spring_session_creditsid[$d][$t], 'userid'=>$this_studentid))) {
								$spring_course_credits = $DB->get_record('user_info_data', ['fieldid'=>$spring_session_creditsid[$d][$t], 'userid'=>$this_studentid], 'id, userid, fieldid, data');
								$spring_course_credits->data = $this_course_credits;
								$transaction = $DB->start_delegated_transaction();
								$DB->update_record('user_info_data', $spring_course_credits, false);
								// Assuming that the update worked, we get to the following line.
								$transaction->allow_commit();
							}
							else {
								$spring_course_credits->fieldid = $spring_session_creditsid[$d][$t];
								$spring_course_credits->userid = $this_studentid;
								$spring_course_credits->data = $this_course_credits;
								$DB->insert_record('user_info_data', $spring_course_credits, true, false);
							}
						}
						catch(Exception $e) {
							$transaction->rollback($e);
							$this_student = student_info::get_single_student($this_studentid);
							$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
							echo $OUTPUT->notification($student_with_error);
						}
					}
				}
			}
		}
		else {
			return;
		}
		// Reset the label for distance learning to NO. All students will start the spring semester with NO for distance learner.
		$distancelearningonly_id = utils::get_fieldid('distancelearningonly');

		$table = 'user_info_data';
		$select = "fieldid = :fieldid AND data = :data";
		$params =['fieldid'=>$distancelearningonly_id, 'data'=>'Yes'];
		// If the course is active, return true.
		$all_distance_learners = $DB->get_records_select($table, $select, $params);

		foreach ($all_distance_learners as $distance_learner) {
			if ($distance_learner->data == 'Yes') {
				$distance_learner->data = 'No';
				// Write the new files.
				try {
					$transaction = $DB->start_delegated_transaction();
					$DB->update_record('user_info_data', $distance_learner, false);
					// Assuming that one of the inserts worked, we get to the following line.
					$transaction->allow_commit();
				}
				catch(Exception $e) {
					$transaction->rollback($e);
					$this_student = student_info::get_single_student($distance_learner->userid);
					$student_with_error = 'The entry for ' . $this_student->firstname . ' ' . $this_student->lastname . ' was not saved. An error was found in the assignment details.';
					echo $OUTPUT->notification($student_with_error);
				}
			}
		}
	}
}