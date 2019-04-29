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
 * Local plugin "OER catalog" - Library
 *
 * @package    local_sharewith
 * @copyright  2017 Kathrin Osswald, Ulm University <kathrin.osswald@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__ . '/locallib.php');

/**
 * Allow plugins to provide some content to be rendered in the navbar.
 * The plugin must define a PLUGIN_render_navbar_output function that returns
 * the HTML they wish to add to the navbar.
 *
 * @return string HTML for the navbar
 */

function local_sharewith_render_navbar_output() {
    global $PAGE, $USER, $COURSE;

    $output = '';

    $sectioncopyenable = get_config('local_sharewith', 'sectioncopy');
    $activityhimselfcopyenable = get_config('local_sharewith', 'activityhimselfcopy');

    // Check permission.
    if (!local_sharewith_permission_allow($COURSE->id, $USER->id)) {
        $sectioncopyenable = 0;
        $activityhimselfcopyenable = 0;
    }

    $params = array(
            $sectioncopyenable,
            $activityhimselfcopyenable
    );

    $PAGE->requires->js_call_amd('local_sharewith/init', 'init', $params);
    return $output;
}

/**
 * Hook function to extend the course settings navigation. Call all context functions
 */

function local_sharewith_extend_navigation_course($parentnode, $course, $context) {
    global $USER, $COURSE;

    if (get_config('local_sharewith', 'coursecopy')) {
        if (local_sharewith_permission_allow($COURSE->id, $USER->id)) {
            $strmetadata = get_string('menucoursenode', 'local_sharewith');

            $url = 'Javascript:void(0)';
            $courseduplicatenode = \navigation_node::create($strmetadata, $url, \navigation_node::NODETYPE_LEAF,
                    'courseduplicate', 'courseduplicate', new \pix_icon('t/portfolioadd', $strmetadata)
            );
            $courseduplicatenode->add_class('selectCategory');

            $parentnode->add_node($courseduplicatenode);
        }
    }
}