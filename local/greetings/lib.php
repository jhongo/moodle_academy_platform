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
 * Callback implementations for GRETTINGS
 *
 * @package    local_greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


 /**
 * Insert a link to index.php on the site front page navigation menu.
 *
 * @param navigation_node $frontpage Node representing the front page in the navigation tree.
 */


//# Add plugin in second level of navigation
 function local_greetings_extend_navigation_frontpage(navigation_node $mainpage){

    $mainpage->add(
        get_string('pluginname', 'local_greetings'),
        new moodle_url('/local/greetings/index.php'), 
        navigation_node::TYPE_CUSTOM,
    );
 }

// function local_greetings_extend_navigation_global(global_navigation $nav){

//     $node = navigation_node::create(
//         get_string('pluginname', 'local_greetings'),
//         new moodle_url('/local/greetings/index.php'),
//     );
//     $node->showinflatnavigation = true;
//     $nav->add_node($node);

// }
