<?php

/**
 * @param string $type
 * @param int $ref_id
 * @param int $user_id
 *
 * @return array|bool|null|object|void
 *
 * @since v.1.4.2
 */

if ( ! function_exists('get_generated_gradebook')) {
	function get_generated_gradebook( $type = 'final', $ref_id = 0, $user_id = 0 ) {
		global $wpdb;

		$user_id = tutils()->get_user_id( $user_id );

		$res = false;
		if ( $type === 'all' ) {
			$res = $wpdb->get_results( "SELECT {$wpdb->tutor_gradebooks_results} .*, grade_config FROM {$wpdb->tutor_gradebooks_results} 
					LEFT JOIN {$wpdb->tutor_gradebooks} ON {$wpdb->tutor_gradebooks_results}.gradebook_id = {$wpdb->tutor_gradebooks}.gradebook_id
					WHERE user_id = {$user_id} 
					AND course_id = {$ref_id} 
					AND result_for != 'final' " );

		} elseif ( $type === 'quiz' ) {

			$res = $wpdb->get_row( "SELECT {$wpdb->tutor_gradebooks_results} .*, grade_config FROM {$wpdb->tutor_gradebooks_results} 
					LEFT JOIN {$wpdb->tutor_gradebooks} ON {$wpdb->tutor_gradebooks_results}.gradebook_id = {$wpdb->tutor_gradebooks}.gradebook_id
					WHERE user_id = {$user_id} 
					AND quiz_id = {$ref_id} 
					AND result_for = 'quiz' " );

		} elseif ( $type === 'assignment' ) {
			$res = $wpdb->get_row( "SELECT {$wpdb->tutor_gradebooks_results} .*, grade_config FROM {$wpdb->tutor_gradebooks_results} 
					LEFT JOIN {$wpdb->tutor_gradebooks} ON {$wpdb->tutor_gradebooks_results}.gradebook_id = {$wpdb->tutor_gradebooks}.gradebook_id
					WHERE user_id = {$user_id} 
					AND assignment_id = {$ref_id} 
					AND result_for = 'assignment' " );
		}elseif ($type === 'final'){
			$res = $wpdb->get_row( "SELECT {$wpdb->tutor_gradebooks_results}.*, grade_config FROM {$wpdb->tutor_gradebooks_results} 
					LEFT JOIN {$wpdb->tutor_gradebooks} ON {$wpdb->tutor_gradebooks_results}.gradebook_id = {$wpdb->tutor_gradebooks}.gradebook_id
					WHERE user_id = {$user_id} 
					AND course_id = {$ref_id} 
					AND result_for = 'final' " );

		}elseif ($type === 'byID'){
			$res = $wpdb->get_row( "SELECT {$wpdb->tutor_gradebooks_results}.*, grade_config FROM {$wpdb->tutor_gradebooks_results} 
					LEFT JOIN {$wpdb->tutor_gradebooks} ON {$wpdb->tutor_gradebooks_results}.gradebook_id = {$wpdb->tutor_gradebooks}.gradebook_id
					WHERE gradebook_result_id = {$ref_id};" );
		}

		return $res;
	}
}

/**
 * @param int $course_id
 * @param int $user_id
 *
 * @return array|null|object|void
 *
 * Get assignment gradebook by course
 */

if ( ! function_exists('get_assignment_gradebook_by_course')) {
	function get_assignment_gradebook_by_course( $course_id = 0, $user_id = 0 ) {
		global $wpdb;

		$user_id = tutils()->get_user_id( $user_id );

		$res = $wpdb->get_row( "SELECT COUNT({$wpdb->tutor_gradebooks_results}.earned_percent) AS res_count, AVG({$wpdb->tutor_gradebooks_results}.earned_percent) as earned_percent,
                FORMAT(AVG({$wpdb->tutor_gradebooks_results}.grade_point), 2) as earned_grade_point,
                grade_config FROM {$wpdb->tutor_gradebooks_results} 
				LEFT JOIN {$wpdb->tutor_gradebooks} ON {$wpdb->tutor_gradebooks_results}.gradebook_id = {$wpdb->tutor_gradebooks}.gradebook_id
				WHERE course_id = {$course_id} AND user_id = {$user_id} AND result_for = 'assignment' " );

		$res_count = (int) $res->res_count;
		if ( ! $res_count){
			return false;
		}

		return $res;
	}
}

/**
 * @param int $course_id
 * @param int $user_id
 *
 * @return array|null|object|void
 *
 * Get quiz gradebook by course
 */

if ( ! function_exists('get_quiz_gradebook_by_course')) {
	function get_quiz_gradebook_by_course( $course_id = 0, $user_id = 0 ) {
		global $wpdb;

		$user_id = tutils()->get_user_id( $user_id );

		$res = $wpdb->get_row( "SELECT COUNT({$wpdb->tutor_gradebooks_results}.earned_percent) AS res_count, 
                AVG({$wpdb->tutor_gradebooks_results}.earned_percent) as earned_percent,
                FORMAT(AVG({$wpdb->tutor_gradebooks_results}.grade_point), 2) as earned_grade_point,
                grade_config FROM {$wpdb->tutor_gradebooks_results} 
				LEFT JOIN {$wpdb->tutor_gradebooks} ON {$wpdb->tutor_gradebooks_results}.gradebook_id = {$wpdb->tutor_gradebooks}.gradebook_id
				WHERE user_id = {$user_id} AND result_for = 'quiz' " );

		$res_count = (int) $res->res_count;
		if ( ! $res_count){
			return false;
		}

		return $res;

	}
}

/**
 * @param int $percent
 *
 * @return array|null|object|void
 *
 * Get gradebook by percent
 */
if ( ! function_exists('get_gradebook_by_percent')) {
	function get_gradebook_by_percent( $percent = 0 ) {
		global $wpdb;
		$gradebook = $wpdb->get_row( "SELECT * FROM {$wpdb->tutor_gradebooks} 
		WHERE percent_from <= {$percent} 
		AND percent_to >= {$percent} ORDER BY gradebook_id ASC LIMIT 1  " );

		return $gradebook;
	}
}

/**
 * @param int $point
 *
 * @return array|bool|null|object|void
 *
 * Get gradebook by point
 */
if ( ! function_exists('get_gradebook_by_point')) {
	function get_gradebook_by_point( $point = 0 ) {
		if ( ! $point ) {
			return false;
		}
		global $wpdb;
		$gradebook = $wpdb->get_row( "SELECT * FROM {$wpdb->tutor_gradebooks} WHERE grade_point <= '{$point}' ORDER BY grade_point DESC LIMIT 1 " );
		return $gradebook;
	}
}
/**
 * @param $grade
 *
 * @return mixed|void
 *
 * Generate Grade HTML
 */

if ( ! function_exists('tutor_generate_grade_html')) {
	function tutor_generate_grade_html( $grade, $style = 'bgfill' ) {
		if ( ! $grade){
			return;
		}

		if ( ! is_object( $grade ) ) {
			global $wpdb;

			$grade = $wpdb->get_row( "SELECT {$wpdb->tutor_gradebooks_results} .*, grade_config FROM {$wpdb->tutor_gradebooks_results} 
					LEFT JOIN {$wpdb->tutor_gradebooks} ON {$wpdb->tutor_gradebooks_results}.gradebook_id = {$wpdb->tutor_gradebooks}.gradebook_id
					WHERE gradebook_result_id = {$grade} " );
		}

		if (empty($grade->earned_grade_point)){
			return;
		}

		ob_start();

		if ( $grade ) {
			$config = maybe_unserialize( $grade->grade_config );
			
			$stat = tutor_gradebook_get_stats($grade);
			$stat['config'] ? $config=$stat['config'] : 0;

			$bgcolor = tutils()->array_get( 'grade_color', $config );
			if ($style === 'bgfill'){
				echo "<span class='gradename-bg {$style}' style='background-color: {$bgcolor};'>{$stat['gradename']}</span> ";
			}else{
				echo "<span class='gradename-outline {$style}' style='color: {$bgcolor};'>{$stat['gradename']}</span> ";
			}

			if ( $stat['gradepoint'] ) {
				echo "<span class='gradebook-earned-grade-point'>{$stat['gradepoint']}</span>";
			}
		}
		$output = apply_filters( 'tutor_gradebook_grade_output_html', ob_get_clean(), $grade );

		return $output;
	}
}

if(!function_exists('tutor_gradebook_get_stats')) {
	function tutor_gradebook_get_stats($grade) {

		$grade_name = '';
		$grade_point = '';
		$grade_point_only = '';
		$config = null;

		$gradebook_scale = get_tutor_option( 'gradebook_scale' );

		// Get grade name
		if ( ! empty($grade->grade_name)){
			$grade_name = $grade->grade_name;
		}else{
			$new_grade = get_gradebook_by_point($grade->earned_grade_point);
			if ($new_grade){
				$grade_name = $new_grade->grade_name;
				$config = maybe_unserialize( $new_grade->grade_config );
			}
		}

		// Get grade point
		if(get_tutor_option( 'gradebook_enable_grade_point' )) {
			$grade_point_only = !empty($grade->earned_grade_point) ? $grade->earned_grade_point : $grade->grade_point;
			$grade_point = $grade_point_only;
		}

		// Add scale
		if(get_tutor_option( 'gradebook_show_grade_scale' )) {
			$separator = get_tutor_option( 'gradebook_scale_separator', '/' );
			$grade_point = $grade_point . $separator . $gradebook_scale;
		}
		
		return array(
			'gradename' => $grade_name,
			'gradepoint' => $grade_point,
			'gradescale' => $gradebook_scale,
			'gradepoint_only' => $grade_point_only,
			'config' => $config
		);
	}
}

/**
 * @param $gradebook_id
 *
 * @return array|bool|null|object|void
 *
 * Get gradebook by gradebook id
 *
 *
 */

if ( ! function_exists('get_gradebook_by_id')) {
	function get_gradebook_by_id( $gradebook_id ) {
		global $wpdb;
		$gradebook = $wpdb->get_row( "SELECT * FROM {$wpdb->tutor_gradebooks} WHERE gradebook_id = {$gradebook_id} " );
		if ( $gradebook ) {
			$gradebook->grade_config = maybe_unserialize( tutils()->array_get( 'grade_config', $gradebook ) );

			return $gradebook;
		}

		return false;
	}
}

function get_grading_contents_by_course_id($course_id = 0){
	global $wpdb;

	$course_id = tutils()->get_post_id($course_id);
	$contents = $wpdb->get_results($wpdb->prepare("SELECT items.* FROM {$wpdb->posts} topic
				INNER JOIN {$wpdb->posts} items ON topic.ID = items.post_parent 
				WHERE topic.post_parent = %d 
				AND items.post_status = 'publish' 
				AND (items.post_type = 'tutor_quiz' || items.post_type = 'tutor_assignments') 
				order by topic.menu_order ASC, items.menu_order ASC;", $course_id));

	return $contents;
}

function get_generated_gradebooks($config = array()){
	global $wpdb;

	$default_attr = array(
		'course_id' => 0,
		'start' => '0',
		'limit' => '20',
		'order' => 'DESC',
		'order_by' => 'gradebook_result_id',
	);
	$attr = array_merge($default_attr, $config);
	extract($attr);

	$gradebooks = array(
		'count' => 0,
		'res' => false,
	);

	$term = sanitize_text_field(tutils()->array_get('s', $_REQUEST));
	$filter_sql = '';
	if ($course_id){
		$filter_sql .= " AND gradebook_result.course_id = {$course_id} ";
	}
	if ( $term){
		$filter_sql .= " AND (course.post_title LIKE '%{$term}%' OR student.display_name LIKE '%{$term}%' ) ";
	}

	$gradebooks['count'] = $wpdb->get_var("SELECT COUNT(gradebook_result.gradebook_result_id) total_res

FROM {$wpdb->tutor_gradebooks_results} gradebook_result
LEFT JOIN {$wpdb->posts} course ON gradebook_result.course_id = course.ID
LEFT  JOIN {$wpdb->users} student ON gradebook_result.user_id = student.ID
WHERE gradebook_result.result_for = 'final' {$filter_sql} ;");

	$gradebooks['res'] = $wpdb->get_results("SELECT gradebook_result.*, 

(SELECT COUNT(quizzes.quiz_id) FROM {$wpdb->tutor_gradebooks_results} quizzes WHERE quizzes.user_id = gradebook_result.user_id AND quizzes.course_id = gradebook_result.course_id AND quizzes.result_for = 'quiz') as quiz_count,

(SELECT COUNT(assignments.assignment_id) FROM {$wpdb->tutor_gradebooks_results} assignments WHERE assignments.user_id = gradebook_result.user_id AND assignments.course_id = gradebook_result.course_id AND assignments.result_for = 'assignment') as assignment_count,
grade_config,
student.display_name,
course.post_title as course_title

FROM {$wpdb->tutor_gradebooks_results} gradebook_result
LEFT JOIN {$wpdb->tutor_gradebooks} gradebook ON gradebook_result.gradebook_id = gradebook.gradebook_id
LEFT JOIN {$wpdb->posts} course ON gradebook_result.course_id = course.ID
LEFT  JOIN {$wpdb->users} student ON gradebook_result.user_id = student.ID

WHERE gradebook_result.result_for = 'final' {$filter_sql} LIMIT {$start}, {$limit} ");

	$gradebooks = (object) $gradebooks;

	return $gradebooks;
}