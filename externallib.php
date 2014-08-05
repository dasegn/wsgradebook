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
/*require_once($CFG->libdir . "/datalib.php");
require_once $CFG->libdir.'/gradelib.php';
require_once $CFG->dirroot.'/grade/lib.php';
require_once $CFG->dirroot.'/grade/report/grader/lib.php';*/


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

        /*$ccc = get_courses('all', 'c.id ASC', 'c.id,c.shortname,c.visible');

        foreach ($ccc as $course) {
            $context = context_course::instance($course->id, IGNORE_MISSING);
            $gpr = new grade_plugin_return(array('type'=>'report', 'plugin'=>'grader', 'courseid'=>$course->id, 'page'=> 0));
            $report = new grade_report_grader($course->id, $gpr, $context, 0, 0);
            // final grades MUST be loaded after the processing
            $report->load_users();
            $numusers = $report->get_numusers();
            echo "Course ID:".$course->id. " Usuarios: ".$numusers."\n";
            $report->load_final_grades();
            if($numusers > 0){
                //print_r($report->users);
                //print_r($report->gtree->items);
                //print_r($report->grades);
            }
        }*/


        $gradebooks = array();
        $gb_sql = "SELECT gg.id AS gid, 
<<<<<<< HEAD
                    cu.id AS course_id,
=======
                    cu.idnumber AS course_id,
                    cu.id AS course_idnum,
>>>>>>> FETCH_HEAD
                    usr.id as student_id,
                    gi.itemname AS itemname,
                    gi.itemtype AS itemtype,
                    gi.itemmodule AS itemmodule,
                    gg.rawgrade AS rawgrade,
                    gg.finalgrade AS finalgrade,
                    gg.usermodified AS usermodified,
                    gg.timecreated AS date_created,
                    gg.timemodified AS date_modified
                    FROM {grade_grades} AS gg
<<<<<<< HEAD
                    JOIN {user} AS usr ON gg.userid = usr.id
                    JOIN {grade_items} AS gi ON gg.itemid = gi.id
                    JOIN {course} AS cu ON gi.courseid = cu.id 
                    ORDER BY course_id, student_id";
        $gbrecords = $DB->get_records_sql($gb_sql, $params=null, $limitfrom=0, $limitnum=0);

=======
                    LEFT JOIN {user} AS usr ON gg.userid = usr.id
                    LEFT JOIN {grade_items} AS gi ON gg.itemid = gi.id
                    LEFT JOIN {course} AS cu ON gi.courseid = cu.id";
        $gbrecords = $DB->get_records_sql($gb_sql);
>>>>>>> FETCH_HEAD
        if ($gbrecords) {
            foreach ($gbrecords as $gb) {
                array_push($gradebooks, array(
                    'id' => $gb->course_id,
                    'id_curso' => $gb->course_id,
                    'id_alumno' => $gb->student_id,
                    'itemname' => $gb->itemname,
                    'itemtype' => $gb->itemtype,
                    'itemmodule' => $gb->itemmodule,
                    'rawgrade' => $gb->rawgrade,
                    'finalgrade' => $gb->finalgrade,
                    'usermodified' => $gb->usermodified,
                    'datecreated' => $gb->date_created,
                    'datemodified' => $gb->date_modified
                ));
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
                    'itemname' => new external_value(PARAM_RAW, 'concept of grade'),
                    'itemtype' => new external_value(PARAM_RAW, 'concept of grade'),
                    'itemmodule' => new external_value(PARAM_RAW, 'Module of grade'),
                    'rawgrade' => new external_value(PARAM_RAW, 'raw calification of item'),
                    'finalgrade' => new external_value(PARAM_RAW, 'final calification of item'),
                    'usermodified' => new external_value(PARAM_INT, 'User of the modification to item'),
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
