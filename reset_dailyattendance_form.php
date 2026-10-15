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
 * The main istudy configuration form used for entering attendance credit
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * Module instance settings form
 *
 * @package    mod_istudy
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_istudyresetsyear_reset_summer_form extends \moodleform
{
    public function definition()
	{
        $mform    = $this->_form;

		$mform->addElement('html', '<link rel="stylesheet" type="text/css" href="styles-reset_dailyattendance_form.css"><div class="mydiv">');
		$mform->addElement('html', '<h3>Reset before Start of School Year</h3>');
		$mform->addElement('html', '<h4>Daily Attendance</h4><br>');

		$mform->addElement('html',  '<br><h5>Reset the DAILY ATTENDANCE...</h5><br>');
		$mform->addElement('textarea', 'resetconfirmation', 'Type RESET to comfirm', 'wrap="virtual" rows="1" cols="10"');
		$mform->setDefault('resetconfirmation', '');
		$mform->setType('resetconfirmation', PARAM_TEXT);

		// Hidden id number for sending the data back.
		$mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

		// Add standard buttons, common to all modules.
		$mform->addElement('html', '<br><h3>  </h3>');
        $this->add_action_buttons(true, 'RESET Daily Attendance');
		$mform->addElement('html', '</div>');
	}
}