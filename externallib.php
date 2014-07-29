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

                )
        );
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_certificates_parameters() {
        return new external_function_parameters(
                array(

                ) 
        );
    }

    /**
     * Returns user gradebook
     * @return array
     */
    public static function get_gradebook() {
        global $CFG, $DB, $USER;
        $gradebooks = array();
        $gb_sql = "SELECT cu.idnumber AS course_id,
                    cu.id AS course_idnum,
                    usr.id as student_id,
                    gi.itemname AS gradebookitem,
                    gg.rawgrade AS raw_grade,
                    gg.finalgrade AS final_grade,
                    gg.timecreated AS date_created,
                    gg.timemodified AS date_modified
                    FROM {grade_grades} AS gg
                    LEFT JOIN {user} AS usr ON gg.userid = usr.id
                    LEFT JOIN {grade_items} AS gi ON gg.itemid = gi.id
                    LEFT JOIN {course} AS cu ON gi.courseid = cu.id";
        $gbrecords = $DB->get_records_sql($gb_sql)
        if ($gbrecords) {
            foreach ($gbrecords as $gb) {
                $gradebooks[] = array(
                    'id' => $gb->course_id,
                    'id_curso' => $gb->course_idnum,
                    'id_alumno' => $gb->student_id,
                    'item' => $gb->gradebookitem,
                    'raw' => $gb->raw_grade,
                    'final' => $gb->final_grade,
                    'datecreated' => $gb->date_created,
                    'datemodified' => $gb->date_modified
                );
            }

        }
        

        return $gradebooks;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_gradebook_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_RAW, 'course id number'),
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
    public static function get_certificates() {
        global $CFG, $DB, $USER;
        $certificates = array();
        $certificate = array();

        $cer_sql = "SELECT ce.id, ci.userid, ce.course, ce.name, ci.code, ci.timecreated 
                    FROM {certificate_issues} as ci
                    JOIN {certificate} as ce on ce.id = ci.certificateid";

        if ($cerecords = $DB->get_records_sql($cer_sql)) {
            foreach ($cerecords as $cert) {
                $certificate['id'] = $cert->id;
                $certificate['id_curso'] = $cert->course; 
                $certificate['id_alumno'] = $cert->userid;
                $certificate['code'] = $cert->code;
                $certificate['timecreated'] = $cert->timecreated; 
                $certificates[] = $certificate;
            }

        }

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
                array(
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
