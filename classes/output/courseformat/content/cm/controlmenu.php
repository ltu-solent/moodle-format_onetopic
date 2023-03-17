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
 * Contains the default activity control menu.
 *
 * @package   format_onetopic
 * @copyright 2020 Ferran Recio <ferran@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 namespace format_onetopic\output\courseformat\content\cm;

use action_menu;
use action_menu_link;
use cm_info;
use core\output\named_templatable;
use core_courseformat\base as course_format;
use core_courseformat\output\local\courseformat_named_templatable;
use renderable;
use section_info;
use stdClass;

/**
 * Base class to render a course module menu inside a course format.
 *
 * @package   format_onetopic
 * @copyright 2020 Ferran Recio <ferran@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class controlmenu extends \core_courseformat\output\local\content\cm\controlmenu {
    /**
     * Generate the edit control items of a course module.
     *
     * This method uses course_get_cm_edit_actions function to get the cm actions.
     * However, format plugins can override the method to add or remove elements
     * from the menu.
     *
     * @return array of edit control items
     */
    protected function cm_control_items() {
        $controlactions = parent::cm_control_items();
        // SU_AMEND_START: Hide dangerous actions for summative assignments.
        if (method_exists('\local_solsits\helper', 'is_summative_assignment')) {
            if (\local_solsits\helper::is_summative_assignment($this->mod->id) && !is_siteadmin()) {
                unset($controlactions['delete']);
                unset($controlactions['hide']);
            }
        }
        // SU_AMEND_END.
        return $controlactions;
    }
}
