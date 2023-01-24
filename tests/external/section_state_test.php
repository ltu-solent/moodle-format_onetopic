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
 * Section state test
 *
 * @package   format_onetopic
 * @author    Mark Sharp <mark.sharp@solent.ac.uk>
 * @copyright 2022 Solent University {@link https://www.solent.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace format_onetopic\external;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/webservice/tests/helpers.php');

use core_courseformat\external\get_state;
use external_api;
use externallib_advanced_testcase;

/**
 * Section state tests
 *
 * @covers \format_onetopic\output\courseformat\state\section
 */
class section_state_test extends externallib_advanced_testcase {
    /**
     * Store list of sections
     *
     * @var array
     */
    private $sections;
    /**
     * Store list of activities
     *
     * @var array
     */
    private $activities;
    /**
     * Reset after test
     *
     * @return void
     */
    public function setUp(): void {
        $this->resetAfterTest();
        $this->sections = [];
        $this->activities = [];
    }

    /**
     * Test tearDown.
     */
    public function tearDown(): void {
        unset($this->sections);
        unset($this->activities);
    }

    /**
     * Setup to ensure that fixtures are loaded.
     */
    public static function setupBeforeClass(): void { // phpcs:ignore
        global $CFG;
        require_once($CFG->dirroot . '/course/lib.php');
        require_once($CFG->libdir . '/externallib.php');
    }

    /**
     * Test getting courseindex state data for draggable sections
     *
     * @dataProvider get_state_provider
     * @param string $catname
     * @return void
     */
    public function test_get_state($catname) {
        $this->resetAfterTest();
        $category = $this->getDataGenerator()->create_category([
            'idnumber' => $catname
        ]);
        $course = $this->getDataGenerator()->create_course([
            'numsections' => 10,
            'format' => 'onetopic',
            'category' => $category->id
        ]);
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->enrol_user(
            $user->id,
            $course->id,
            'editingteacher'
        );
        $this->setUser($user);
        $locksections = 4;
        $lockcategory = 'modules_';
        set_config('locksections', $locksections, 'format_onetopic');
        set_config('locksectioncategory', $lockcategory, 'format_onetopic');

        // Get course state for teacher.
        $result = get_state::execute($course->id);
        $result = external_api::clean_returnvalue(get_state::execute_returns(), $result);
        $result = json_decode($result);
        $lockedcat = (preg_match('#' . $lockcategory . '#', $catname) === 1);
        for ($x = 0; $x <= 10; $x++) {
            if ($x <= $locksections && $lockedcat) {
                $this->assertEquals(0, $result->section[$x]->isdraggable);
            } else {
                $this->assertEquals(1, $result->section[$x]->isdraggable);
            }
        }

        // Get course state for admin who can always drag sections.
        $this->setAdminUser();
        $result = get_state::execute($course->id);
        $result = external_api::clean_returnvalue(get_state::execute_returns(), $result);
        $result = json_decode($result);
        $lockedcat = (preg_match('#' . $lockcategory . '#', $catname) === 1);
        for ($x = 0; $x <= 10; $x++) {
            $this->assertEquals(1, $result->section[$x]->isdraggable);
        }
    }

    /**
     * Provider for test_get_state
     *
     * @return array List of tests.
     */
    public function get_state_provider() {
        return [
            'Restricted category' => [
                'catname' => 'modules_FAB'

            ],
            'Free for all category' => [
                'catname' => 'mycat'
            ]
        ];
    }
}
