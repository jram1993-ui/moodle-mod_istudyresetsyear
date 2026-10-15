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
 * The main istudyresetsyear configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_istudyresetsyear
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * Module instance settings form
 *
 * @package    mod_istudyresetsyear
 * @copyright  2024 Jesus Ramirez <jram1993@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_istudyresetsyear_choose_term_form extends \moodleform
{

    public function definition()
	{
		$mform = $this->_form; // Don't forget the underscore!
		$mform->addElement('html', '<link rel="stylesheet" type="text/css" href="styles-choose_term_form.css">');
		$mform->addElement('html', '<table><tr><th colspan="2"><h3>Start of Year - Reset Courses</h3>');
		$mform->addElement('html', '<h4>Remember - Backup Student Info First</h4></th></tr>');

		// Choose the term that you are printing from.
		$options = [
			1 => 'None',
			2 => 'Backup Student Info First',
			3 => 'Fall & Spring - Courses & Monthly Attendance',
			4 => 'Summer - Courses',
			5 => 'Daily Attendance - Fall, Spring, & Summer ',
			6 => 'Trasfer Fall courses & credits to Spring',
		];

		$mform->addElement('html', '<tr><td class="mycell2">Choose which term to reset</td><td>');
		$select = $mform->addElement('select', 'schoolterm', '', $options);
		// Set default to None.
		$select->setSelected(1);
		$mform->addElement('html', '</td></tr>');

		$mform->addElement('html', '</table>');

		$mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
		$mform->addElement('html', '<br><h3>  </h3>');

		// Add standard buttons, common to all modules.
		$mform->addElement('html', '<div class="buttonsetup">');
			$this->add_action_buttons(true, 'Submit');
		$mform->addElement('html', '</div>');
    }
}