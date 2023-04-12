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
 * Helper class with a bunch of functions used by SOL
 *
 * @package   format_onetopic
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_onetopic;

use core_course_category;
use Exception;

/**
 * Extra Solent specific functions.
 */
class solhelper {
    /**
     * Solent restricts which sections are moveable. This depends on the user, course category idnumber, and the section number.
     *
     * @param stdClass $course Course object
     * @param stdClass $section Section object
     * @return bool True/False draggable or not.
     */
    public static function isdraggable($course, $section): bool {
        $config = get_config('format_onetopic');
        if ($config->locksections === 0) {
            if ($section->section == 0) {
                // Section 0 is never draggable.
                return false;
            } else {
                // All other sections can be dragged.
                return true;
            }
        }
        // Site admin can always move sections.
        if (is_siteadmin()) {
            return true;
        }
        $coursecat = core_course_category::get($course->category, IGNORE_MISSING);
        $lockedcategory = false;
        if ($config->locksectioncategory == '') {
            $lockedcategory = true;
        } else {
            $lockedcategory = (preg_match('#' . $config->locksectioncategory . '#', $coursecat->idnumber) === 1);
        }
        if (!$lockedcategory) {
            return true;
        }

        if ($section->section <= $config->locksections) {
            return false;
        }
        return true;
    }

    /**
     * Given a coursemodule id, returns if this is a summative assignment.
     *
     * @param int $cmid Course module id
     * @return boolean
     */
    public static function is_summative_assignment($cmid) {
        try {
            [$course, $cm] = get_course_and_cm_from_cmid($cmid, 'assign');
            return ($cm->idnumber != '');
        } catch (Exception $ex) {
            return false;
        }
    }
}
