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
 * Inplace editable
 *
 * @package   format_onetopic
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_onetopic\output\courseformat\content\cm;

use cm_info;
use core\output\inplace_editable;
use core_courseformat\base as course_format;
use lang_string;
use section_info;

/**
 * Override cm title class
 */
class title extends \core_courseformat\output\local\content\cm\title {

    /**
     * Constructor.
     *
     * @param course_format $format the course format
     * @param section_info $section the section info
     * @param cm_info $mod the course module ionfo
     * @param array $displayoptions optional extra display options
     * @param bool|null $editable force editable value
     */
    public function __construct(
        course_format $format,
        section_info $section,
        cm_info $mod,
        array $displayoptions = [],
        ?bool $editable = null
    ) {
        $this->format = $format;
        $this->section = $section;
        $this->mod = $mod;

        // Usually displayoptions are loaded in the main cm output. However when the user uses the inplace editor
        // the cmname output does not calculate the css classes.
        $this->displayoptions = $this->load_display_options($displayoptions);
        // SU_AMEND_START: Formative assignments do not allow inline editing of the title.
        $formative = false;
        $formative = \format_onetopic\solhelper::is_summative_assignment($mod->id);
        if ($editable === null) {
            $editable = $format->show_editor() && has_capability(
                'moodle/course:manageactivities',
                $mod->context
            ) && !$formative;
        }
        // SU_AMEND_END.
        $this->editable = $editable;

        // Setup inplace editable.
        inplace_editable::__construct(
            'core_course',
            'activityname',
            $mod->id,
            $this->editable,
            $mod->name,
            $mod->name,
            new lang_string('edittitle'),
            new lang_string('newactivityname', '', $mod->get_formatted_name())
        );
    }
}
