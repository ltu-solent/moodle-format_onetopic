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
use renderer_base;
use stdClass;

/**
 * Class cm
 *
 * @package    format_onetopic
 * @copyright  2024 Southampton Solent University {@link https://www.solent.ac.uk}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cm extends StateCm {
    /**
     * Export this data so it can be used as state object in the course editor.
     *
     * @param renderer_base $output typically, the renderer that's calling this function
     * @return stdClass data context for a mustache template
     */
    public function export_for_template(renderer_base $output): stdClass {
        $data = parent::export_for_template($output);
        // SU_AMEND_START: Remove Font-Awesome from display in course index. Also affects backup.
        $data->name = preg_replace('/\[fa-[a-z0-9\-]+\]/i', '', $data->name);
        // SU_AMEND_END.
        return $data;
    }
}
