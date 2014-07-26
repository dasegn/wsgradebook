<?php

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
 * External Web Service Template
 *
 * @package    localwsgradebook
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_wsgradebook_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_gradebook_parameters() {
        return new external_function_parameters(
                array(
                    'userid' => new external_value(PARAM_INT, 'The user ID. By default in "nothing"'), 
                    'course' => new external_value(PARAM_INT, 'The course ID. By default in "nothing"'))
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_certificates_parameters() {
        return new external_function_parameters(
                array(
                    'userid' => new external_value(PARAM_INT, 'The user ID. By default in "nothing"')) 
        );
    }

    /**
     * Returns user gradebook
     * @return array
     */
    public static function get_gradebook($userid, $course) {
        global $CFG, $DB, $USER;

        $gradebook = array();

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::get_gradebook_parameters(),
                array('userid' => $userid, 'course' => $course));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }

        return $gradebook;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_gradebook_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array (
                    'id' => new external_value(PARAM_INT, 'certificate id'),
                    'id_curso' => new external_value(PARAM_INT, 'id of course'),
                    'id_alumno' => new external_value(PARAM_INT, 'id of student'),
                    'item' => new external_value(PARAM_RAW, 'concept of grade'),
                    'raw' => new external_value(PARAM_RAW, 'raw calification of item'),
                    'final' => new external_value(PARAM_RAW, 'final calification of item'),
                    'datecreated' => new external_value(PARAM_INT, 'the item date of creation'),
                    'datemodified' => new external_value(PARAM_INT, 'the item date of modification'),
                )
            )
        );
    }

    /**
     * Returns user certificates
     * @return array
     */
    public static function get_certificates($userid) {
        global $CFG, $DB, $USER;
        $certificates =                 array (
                    'id' => 0,
                    'id_curso' => 0,
                    'id_alumno' => 0,
                    'code' => 1,
                    'timecreated' => 1,
                );

        /*//Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::get_gradebook_parameters(),
                array('userid' => $userid, 'course' => $course));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }
        */

        return $certificates;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_certificates_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array (
                    'id' => new external_value(PARAM_INT, 'certificate id'),
                    'id_curso' => new external_value(PARAM_INT, 'id of course'),
                    'id_alumno' => new external_value(PARAM_INT, 'id of student'),
                    'code' => new external_value(PARAM_INT, 'code of certificate'),
                    'timecreated' => new external_value(PARAM_INT, 'time of certificate creation'),
                )
            )
        );
    }

}
