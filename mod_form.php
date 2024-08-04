<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * The main mod_courseinformation configuration form.
 *
 * @package     mod_courseinformation
 * @copyright   2024 eLearning Team <el@umt.edu.my>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form.
 *
 * @package     mod_courseinformation
 * @copyright   2024 eLearning Team <el@umt.edu.my>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_courseinformation_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $COURSE;

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('courseinformationname', 'mod_courseinformation'), array('size' => '64'));
        $mform->setDefault('name', 'Course Information');

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Adding the standard "intro" and "introformat" fields.

        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }
        
        $mform->addElement('text','coursename', get_string('coursename','mod_courseinformation', array('size' => '64')));
        $mform->setDefault('coursename', $COURSE->fullname);
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('coursename', PARAM_TEXT);
        } else {
            $mform->setType('coursename', PARAM_CLEANHTML);
        }

        $mform->addRule('coursename', null, 'required', null, 'client');
        $mform->addRule('coursename', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $mform->addElement('text','courseshortname', get_string('courseshortname','mod_courseinformation', array('size' => '64')));
        $mform->setDefault('courseshortname', $COURSE->shortname);
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('courseshortname', PARAM_TEXT);
        } else {
            $mform->setType('courseshortname', PARAM_CLEANHTML);
        }

        $mform->addRule('courseshortname', null, 'required', null, 'client');
        $mform->addRule('courseshortname', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        // Adding the rest of mod_courseinformation settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.

        //course format information with blended learning information
        $mform->addElement('header', 'courseinformationfieldset', get_string('courseinformationfieldset', 'mod_courseinformation'));

        $optionsFormat = array(
            'blended-sokongan' => get_string('blended-sokongan','courseinformation'),
            'blended-gantian' => get_string('blended-gantian','courseinformation')
        );

        $mform->addElement('select', 'courseformat', get_string('courseformat','courseinformation'), $optionsFormat);

        $mform->addElement('text','material_hour', get_string('material_hour', 'courseinformation'),array('size' => '4'));
        $mform->setType('material_hour', PARAM_RAW);
        $mform->addRule('material_hour', null, 'numeric', null, 'client');
        $mform->hideIf('material_hour','courseformat','eq','blended-sokongan');

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }
}
