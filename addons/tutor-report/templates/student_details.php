<?php
/**
 * Student details template
 * 
 * @since 1.9.9
 */
use TUTOR_REPORT\Analytics;

$user               = wp_get_current_user();
$student_id         = isset( $_GET['student_id'] ) ? sanitize_text_field( $_GET['student_id'] ) : 0;
$student_details    = get_userdata( $student_id );
if ( !$student_id || !$student_details ) {
    return _e( 'Invalid student', 'tutor-pro' );
}
$courses = tutor_utils()->get_courses_by_student_instructor_id( $student_id, $user->ID );
?>

<div class="analytics-student-details tutor-user-public-profile tutor-user-public-profile-pp-circle">
    <div class="back-wrapper">
        <a href="<?php echo esc_url( tutor_utils()->tutor_dashboard_url().'analytics/students' );?>">
            <i class="tutor-icon-next-2"></i> <?php _e( 'Back', 'tutor-pro' ); ?>
        </a>
    </div>
    <div class="tutor-container photo-area">
        <div class="cover-area">
            <div style="background-image:url(<?php echo esc_url( tutor_utils()->get_cover_photo_url($student_id) ); ?>); height: 268px"></div>
            <div></div>
        </div>
        <div class="pp-area">
            <div class="profile-pic" style="background-image:url(<?php echo esc_url( get_avatar_url($student_id, array('size' => 150)) ); ?>)">
            </div>
            <div class="profile-name">
                <h3>
                   <?php esc_html_e( $student_details->display_name ); ?>
                </h3>
                <span>
                    <span>
                       <?php _e( 'Email: ', 'tutor-pro'); ?>
                    </span>
                    <?php esc_html_e( $student_details->user_email ); ?>
                </span>
                <span>
                    <span>
                        <?php _e( 'Registration Date: '); ?>
                    </span>
                    <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $student_details->user_registered ) ); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="student-details-table-wrapper">
        <h4>
            <?php _e( 'Course Overview', 'tutor-pro' );?>
        </h4>
        <div class="tutor-table-responsive">
            <table class="tutor-table">
                <thead>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Date', 'tutor-pro' ); ?>
                        </span>
                    </th>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Course', 'tutor-pro' ); ?>
                        </span>
                    </th>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Progress', 'tutor-pro' ); ?>
                        </span>
                    </th>
                    <th class="tutor-shrink"></th>
                </thead>
                <tbody>
                    <?php if ( count($courses) ): ?>
                            <?php foreach( $courses as $course): ?>
                                <?php 
                                    $completed_count      = tutor_utils()->get_course_completed_percent( $course->ID, $student_id );  
                                    $total_lessons        = tutor_utils()->get_lesson_count_by_course( $course->ID );
                                    $completed_lessons    = tutor_utils()->get_completed_lesson_count_by_course( $course->ID, $student_id );  
                                    $total_assignments    = tutor_utils()->get_assignments_by_course( $course->ID )->count;
                                    $completed_assignment = tutor_utils()->get_completed_assignment( $course->ID, $student_id );
                                    $total_quiz           = Analytics::get_all_quiz_by_course( $course->ID );
                                    $completed_quiz       = tutor_utils()->get_completed_quiz( $course->ID, $student_id );
                                ?>
                                <tr>
                                    <td>
                                        <span class="text-regular-caption color-text-primary">
                                            <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $course->post_date ) ); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="color-text-primary td-course text-medium-body">
                                            <span>
                                                <?php esc_html_e( $course->post_title ); ?>
                                            </span>
                                            <div class="course-meta">
                                                <span class="color-text-subsued text-thin-caption">
                                                    <?php _e( 'Lesson: ', 'tutor-pro' ); ?> 
                                                    <strong class="text-medium-caption">
                                                        <?php esc_html_e( $completed_lessons.'/'.$total_lessons); ?>
                                                    </strong>
                                                </span>
                                                <span class="color-text-subsued text-thin-caption">
                                                    <?php _e( 'Assignment: ', 'tutor-pro' ); ?> 
                                                    <strong class="text-medium-caption">
                                                        <?php esc_html_e( $completed_assignment.'/'.$total_assignments); ?>
                                                    </strong>
                                                </span>
                                                <span class="color-text-subsued text-thin-caption">
                                                    <?php _e( 'Quiz: ', 'tutor-pro' ); ?> 
                                                    <strong class="text-medium-caption">
                                                        <?php esc_html_e( $completed_quiz.'/'.$total_quiz); ?>
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="td-progress inline-flex-center">
                                            <div class="progress-bar" style="--progress-value: <?php esc_attr_e( $completed_count );?>%">
                                                <div class="progress-value"></div>
                                            </div>
                                            <div class="progress-text text-medium-caption color-text-primary">
                                                <?php esc_html_e( $completed_count ); ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-action-btns">
                                        <button type="button" id="open_progress_modal" data-tutor-modal-target="modal-sticky-1" class="btn-outline tutor-btn analytics_view_course_progress" data-course_id="<?php echo esc_attr_e( $course->ID ); ?>" data-total_progress="<?php echo esc_attr_e( $completed_count ); ?>" data-total_lesson="<?php echo esc_attr_e( $total_lessons ); ?>" data-completed_lesson="<?php echo esc_attr_e( $completed_lessons ); ?>" data-total_assignment="<?php echo esc_attr_e( $total_assignments ); ?>" data-completed_assignment="<?php echo esc_attr_e( $completed_assignment ); ?>" data-total_quiz="<?php echo esc_attr_e( $total_quiz ); ?>" data-completed_quiz="<?php echo esc_attr_e( $completed_quiz ); ?>">
                                            <?php _e( 'View Progress', 'tutor-pro' ); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">
                                    <?php _e( 'No record found', 'tutor-pro' ); ?>
                                </td>
                            </tr>
                    <?php endif; ?>    
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--modal -->
<div id="modal-sticky-1" class="modal-course-overview tutor-modal"> 
    <span class="tutor-modal-overlay"></span> 
    <div class="tutor-modal-root">
        <div class="tutor-modal-inner"> 
            <button data-tutor-modal-close="" class="tutor-modal-close"> 
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M16.7147 18.9978C16.0946 18.3692 16.0946 17.35 16.7147 16.7214C17.3347 16.0929 18.3399 16.0929 18.96 16.7214L29.9996 27.9135L41.0393 16.7215C41.6593 16.0929 42.6645 16.0929 43.2846 16.7215C43.9046 17.3501 43.9046 18.3692 43.2846 18.9978L32.2449 30.1898L42.9099 41.0021C43.53 41.6307 43.53 42.6498 42.9099 43.2784C42.2899 43.907 41.2846 43.907 40.6646 43.2784L29.9996 32.4662L19.3346 43.2784C18.7146 43.907 17.7093 43.907 17.0893 43.2784C16.4693 42.6499 16.4693 41.6307 17.0893 41.0021L27.7543 30.1898L16.7147 18.9978Z" fill="white"></path> </svg>
            </button> 
            <div class="tutor-modal-body" id="tutor_progress_modal_content"> 
               
            </div> 
        </div> 
    </div> 
</div>
<!--modal end-->