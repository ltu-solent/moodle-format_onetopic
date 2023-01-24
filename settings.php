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
 * Settings for format.
 *
 * @package format_onetopic
 * @copyright 2023 David Herney Bernal - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configcheckbox('format_onetopic/enablecustomstyles',
                                                    get_string('enablecustomstyles', 'format_onetopic'),
                                                    get_string('enablecustomstyles_help', 'format_onetopic'), 1));
    // SU_AMEND_START: Settings to control locked sections.
    $settings->add(
        new admin_setting_heading(
            'format_onetopic/locksectionsheading',
            new lang_string('locksectionsheading', 'format_onetopic'),
            new lang_string('locksectionsheading_desc', 'format_onetopic')
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'format_onetopic/locksections',
            new lang_string('locksections', 'format_onetopic'),
            new lang_string('locksections_desc', 'format_onetopic'),
            0,
            PARAM_INT
        )
    );

    $settings->add(
        new admin_setting_configtext(
            'format_onetopic/locksectioncategory',
            new lang_string('locksectioncategory', 'format_onetopic'),
            new lang_string('locksectioncategory_desc', 'format_onetopic'),
            'modules_',
            PARAM_RAW
        )
    );
    // SU_AMEND_END.
}
