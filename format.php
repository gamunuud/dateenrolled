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
 * dateenrolled course format.  Display the whole course as "dateenrolled" made of modules.
 *
 * @package format_dateenrolled
 * @copyright 2006 The Open University
 * @author N.D.Freear@open.ac.uk, and others.
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/completionlib.php');

// Horrible backwards compatible parameter aliasing..
if ($week = optional_param('week', 0, PARAM_INT)) {
    $url = $PAGE->url;
    $url->param('section', $week);
    debugging('Outdated week param passed to course/view.php', DEBUG_DEVELOPER);
    redirect($url);
}
// End backwards-compatible aliasing..

// make sure all sections are created
//$course = course_get_format($course)->get_course();
//course_create_sections_if_missing($course, range(0, $course->numsections));
course_create_sections_if_missing($course, range(0,
        course_get_format($course)->get_visible_section_count() + 1)
);

if (!$PAGE->user_is_editing()) {
    $renderer = $PAGE->get_renderer('format_dateenrolled');
    if (!empty($displaysection)) {
        $renderer->print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection);
    } else {
        $renderer->print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused);
    }
} else {
    $renderer = $PAGE->get_renderer('format_weeks');

    if (!empty($displaysection)) {
        $renderer->print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection);
    } else {
        $renderer->print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused);
    }
}

$PAGE->requires->js('/course/format/dateenrolled/format.js');
