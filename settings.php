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
 * Settings for onetopic format
 *
 * @package   format_onetopic
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configcheckbox(
        'format_onetopic/disable_styling',
        new lang_string('disable_styling', 'format_onetopic'),
        new lang_string('disable_styling_desc', 'format_onetopic'),
        0
    ));

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
}
