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

namespace format_onetopic\output\courseformat\state;

use core_courseformat\output\local\state\cm as StateCm;
use core_courseformat\base as course_format;
use completion_info;
use renderer_base;
use section_info;
use cm_info;
use renderable;
use stdClass;
use core_availability\info_module;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/completionlib.php');

/**
 * Contains the ajax update course module structure.
 *
 * @package   format_onetopic
 * @copyright 2021 Ferran Recio <ferran@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cm extends StateCm {

    /**
     * Export this data so it can be used as state object in the course editor.
     *
     * @param renderer_base $output typically, the renderer that's calling this function
     * @return stdClass data context for a mustache template
     */
    public function export_for_template(renderer_base $output): stdClass {
        global $USER, $CFG;
        require_once($CFG->libdir . '/externallib.php');

        $format = $this->format;
        $section = $this->section;
        $cm = $this->cm;
        $course = $format->get_course();
        $name = external_format_string($cm->name, $cm->context, true);
        $name = preg_replace('/\[fa-[a-z0-9\-]+\]/i', '', $name);
        if (empty($name)) {
            $name = get_string_manager()->string_exists('pluginname', $cm->modname)
                ? get_string('pluginname', $cm->modname)
                : ucwords($cm->modname);
        }
        $data = (object)[
            'id' => $cm->id,
            'anchor' => "module-{$cm->id}",
            'name' => $name,
            'visible' => !empty($cm->visible),
            'stealth' => $cm->is_stealth(),
            'sectionid' => $section->id,
            'sectionnumber' => $section->section,
            'uservisible' => $cm->uservisible,
            'hascmrestrictions' => $this->get_has_restrictions(),
        ];

        // Check the user access type to this cm.
        $info = new info_module($cm);
        $data->accessvisible = ($data->visible && $info->is_available_for_all());

        // Add url if the activity is compatible.
        $url = $cm->url;
        if ($url) {
            $data->url = $url->out();
        }

        if ($this->exportcontent) {
            $data->content = $output->course_section_updated_cm_item($format, $section, $cm);
        }

        // Completion status.
        $completioninfo = new completion_info($course);
        $data->istrackeduser = $completioninfo->is_tracked_user($USER->id);
        if ($data->istrackeduser && $completioninfo->is_enabled($cm)) {
            $completiondata = $completioninfo->get_data($cm);
            $data->completionstate = $completiondata->completionstate;
        }

        return $data;
    }
}
