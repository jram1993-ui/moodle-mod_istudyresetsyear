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
 * @package    mod_istudyprintdailyatt
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_istudyresetsyear;

/**
 * Class student_info
 *
 * @package    mod_istudyresetsyear
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class student_info {

	// Get the student info for a single student.
	public static function get_single_student($studentid) {
		global $DB;
		$sql = "SELECT * FROM {user} WHERE id = " . $studentid;
		$params = null;
		$student_record = $DB->get_record_sql($sql, $params, 0, 0);
		$this_student = new stdClasss();
		$this_student->firstname = $student_record->firstname;
		$this_student->lastname = $student_record->lastname;
		return $this_student;
	}

	// Get a list of students using the search criteria.
	public static function get_studentlist() {
		global $DB;
		$params = null;
		$sql = "SELECT id, firstname FROM {user} WHERE deleted = 0 AND suspended = 0";
		$mystudent_lists = $DB->get_records_sql($sql, $params, 0, 0);
		$count = 0;
		$students_were_found = false;
		// Search for active students only.
		foreach ($mystudent_lists as $mystudent_list) {
			$record_found = true;
			$student_id = $mystudent_list->id;
			// Check for admin user, guest user, anon user, deleted, or suspended. If found, skip.
			if (($mystudent_list->firstname == 'Admin') OR (substr($mystudent_list->firstname, 0, 4) == 'anon')
				OR (substr($mystudent_list->firstname, 0, 5) == 'Guest')){
				$record_found = false;
			}
			// Get the year and term according to the search criteria.
			if ($record_found == true) {
				$whichuser_status = utils::get_field_data($student_id, utils::get_fieldid('whichteacher'));
				// Check to see if user is an administrator, advisor, or parent.  If so, exclude user.
				if ($whichuser_status <> 'Student') {
						$record_found = false;
				}
			}
			if ($record_found == true) {
				$students_were_found = true;
				$count = $count +1;
				// Student id, last name, & first name
				$students_found[$count] = $student_id;
			}
		}
		if ($students_were_found == false){
			return NULL;
		}
		else {
			return $students_found;
		}
	}
}