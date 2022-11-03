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
 * Contains the default section controls output class.
 *
 * @package   format_onetopic
 * @copyright 2022 David Herney Bernal - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_onetopic\output\courseformat\content\section;

use context_course;
use core_courseformat\output\local\content\section\controlmenu as controlmenu_base;
use format_onetopic\solhelper;

/**
 * Base class to render a course section menu.
 *
 * @package   format_onetopic
 * @copyright 2022 David Herney Bernal - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class controlmenu extends controlmenu_base {

    /** @var course_format the course format class */
    protected $format;

    /** @var section_info the course section class */
    protected $section;

    /**
     * Generate the edit control items of a section.
     *
     * This method must remain public until the final deprecation of section_edit_control_items.
     *
     * @return array of edit control items
     */
    public function section_control_items() {
        global $USER;
        $format = $this->format;
        $section = $this->section;
        $course = $format->get_course();
        $sectionreturn = $format->get_section_number();
        // SU_AMEND_START: Extra vars to manage menu items.
        $numsections = $format->get_last_section_number();
        $usecomponents = $format->supports_components();
        $isstealth = $section->section > $numsections;
        $user = $USER;

        $baseurl = course_get_url($course, $sectionreturn);
        $baseurl->param('sesskey', sesskey());
        // SU_AMEND_END.

        $coursecontext = context_course::instance($course->id);

        if ($sectionreturn) {
            $url = course_get_url($course, $section->section);
        } else {
            $url = course_get_url($course);
        }
        $url->param('sesskey', sesskey());

        $controls = [];
        if ($section->section && has_capability('moodle/course:setcurrentsection', $coursecontext)) {
            if ($course->marker == $section->section) {  // Show the "light globe" on/off.
                $url->param('marker', 0);
                $highlightoff = get_string('highlightoff');
                $controls['highlight'] = [
                    'url' => $url,
                    'icon' => 'i/marked',
                    'name' => $highlightoff,
                    'pixattr' => ['class' => ''],
                    'attr' => [
                        'class' => 'editing_highlight',
                        'data-action' => 'removemarker'
                    ],
                ];
            } else {
                $url->param('marker', $section->section);
                $highlight = get_string('highlight');
                $controls['highlight'] = [
                    'url' => $url,
                    'icon' => 'i/marker',
                    'name' => $highlight,
                    'pixattr' => ['class' => ''],
                    'attr' => [
                        'class' => 'editing_highlight',
                        'data-action' => 'setmarker'
                    ],
                ];
            }
        }
        // SU_AMEND_START: Locked sections.
        $isdraggable = solhelper::isdraggable($course, $section);
        // Only allow move on items that are draggable.
        if ($section->section && $isdraggable && $usecomponents) {
            $url = clone($baseurl);
            if (!$isstealth) {
                if (has_capability('moodle/course:movesections', $coursecontext, $user)) {
                    // This tool will appear only when the state is ready.
                    $url = clone ($baseurl);
                    $url->param('movesection', $section->section);
                    $url->param('section', $section->section);
                    $controls['movesection'] = [
                        'url' => $url,
                        'icon' => 'i/dragdrop',
                        'name' => get_string('move', 'moodle'),
                        'pixattr' => ['class' => ''],
                        'attr' => [
                            'class' => 'icon move waitstate',
                            'data-action' => 'moveSection',
                            'data-id' => $section->id,
                        ],
                    ];
                }
            }
        }
        // SU_AMEND_END.

        $parentcontrols = parent::section_control_items();

        // If the edit key exists, we are going to insert our controls after it.
        if (array_key_exists("edit", $parentcontrols)) {
            $merged = [];
            // We can't use splice because we are using associative arrays.
            // Step through the array and merge the arrays.
            foreach ($parentcontrols as $key => $action) {
                $merged[$key] = $action;
                if ($key == "edit") {
                    // If we have come to the edit key, merge these controls here.
                    $merged = array_merge($merged, $controls);
                }
            }
            // SU_AMEND_START: Prevent hiding or deleting non-draggable sections.
            if (!solhelper::isdraggable($course, $section)) {
                unset($merged['visiblity']); // Yes this is a typo.
                unset($merged['visibility']); // Just in case they correct the typo.
                unset($merged['delete']);
            }
            // SU_AMEND_END.

            return $merged;
        } else {
            return array_merge($controls, $parentcontrols);
        }
    }
}
