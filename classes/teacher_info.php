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

/**
 * Class teacher_info
 *
 * @package    mod_istudyresetsyear
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class teacher_info {

	// Only allow an administrator to reset the courses.
	public static function not_an_administrator() {
		global $DB, $USER;
		$advisorid = $USER->id;
		$itemid = utils::get_fieldid('whichteacher');
		$search_results = $DB->get_record('user_info_data', ['userid'=>$advisorid, 'fieldid'=>$itemid], 'data');
		if ($search_results->data == 'Administrator') {
			return false;
		}
		else {
			return true;
		}
	}
}