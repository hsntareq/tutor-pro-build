<?php
/**
 * Handles All Notifications
 * 
 * @package tutor
 * 
 * @since 1.9.10
 */

namespace TUTOR_NOTIFICATIONS;

defined( 'ABSPATH' ) || exit;

/**
 * Tutor Notifications class
 */
class Tutor_Notifications {

    /**
     * Public $all_notifications
     * 
     * @var $all_notifications
     */
    public $all_notifications;

    /**
     * Constructor
     */
    public function __construct() {
        
		add_filter( 'tutor/options/attr', array( $this, 'add_options' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scrips' ) );
        add_action( 'tutor_dashboard/before_header_button', array( $this, 'load_notification_template' ) );
        add_action( 'tutor_announcement_editor/after', array( $this, 'notification_checkbox_for_announcement' ) );

        $this->all_notifications = new \TUTOR_NOTIFICATIONS\Utils();
    }

    /**
     * Load frontend scripts
     */
    public function load_scrips() {
        $dashboard_page_id = tutor_utils()->get_option( 'tutor_dashboard_page_id' );
        if ( is_page( (int) $dashboard_page_id ) ) {
            wp_enqueue_style( 'tutor-notifications', TUTOR_NOTIFICATIONS()->url . 'assets/css/tutor-notifications.css' );
            wp_enqueue_style( 'tutor-notifications-iconpack', TUTOR_NOTIFICATIONS()->url . 'assets/css/fonts/tutor-v2-icon/css/tutor-v2-iconpack.css' );
            wp_enqueue_style( 'tutor-notifications-main', TUTOR_NOTIFICATIONS()->url . 'assets/css/main.min.css' );
            wp_enqueue_script( 'tutor-notifications-main', TUTOR_NOTIFICATIONS()->url . 'assets/js/main.min.js', array( 'jquery' ), TUTOR_NOTIFICATIONS_VERSION, true );
            wp_enqueue_script( 'tutor-notifications', TUTOR_NOTIFICATIONS()->url . 'assets/js/tutor-notifications.js', array( 'wp-i18n' ), TUTOR_NOTIFICATIONS_VERSION, true );
    
            wp_localize_script( 'tutor-notifications', 'notifications_data', array(
                'ajax_url'      => admin_url( 'admin-ajax.php' ),
                'notifications' => $this->all_notifications->get_all_notifications_by_current_user(),
                'empty_image'   => TUTOR_NOTIFICATIONS()->url . 'assets/images/empty-state.svg',
            ) );
        }
    }

    /**
     * Add options
     */
	public function add_options( $attr ) {
		$attr['tutor_notifications'] = array(
			'label' => __( 'Notifications', 'tutor-pro' ),
			'sections'    => array(
				'general' => array(
					'label'  => __( 'Notification Events', 'tutor-pro' ),
					'desc'   => __( 'Notification recipient selections', 'tutor-pro' ),
					'fields' => array(
                        'tutor_notifications_to_students'   => array(
							'type'    => 'checkbox',
                            'label'   => 'Student Notifications',
                            'options' => array(
								'course_enrolled' 			  => __( 'Course Enrolled', 'tutor-pro' ),
								'remove_from_course' 		  => __( 'Cancel Enrollment', 'tutor-pro' ),
								'assignment_graded' 		  => __( 'Assignment Graded', 'tutor-pro' ),
                                'new_announcement_posted' 	  => __( 'New Announcement Posted', 'tutor-pro' ),
                                'after_question_answered' 	  => __( 'Q&A Message Answered', 'tutor-pro' ),
                                'feedback_submitted_for_quiz' => __( 'Feedback Submitted for Quiz Attempt', 'tutor-pro' ),
                            ),
							'desc'    => __( 'Notifications to send to students', 'tutor-pro' ),
                        ),
						'tutor_notifications_to_instructors' => array(
							'type'    => 'checkbox',
                            'label'   => 'Instructor Notifications',
							'options' => array(
								'instructor_application_accepted' => __( 'Instructor Application Accepted ', 'tutor-pro' ),
								'instructor_application_rejected' => __( 'Instructor Application Rejected', 'tutor-pro' ),
                            ),
							'desc'    => __( 'Notifications to send to instructors', 'tutor-pro' ),
						),
						'tutor_notifications_to_admin'       => array(
							'type'    => 'checkbox',
                            'label'   => 'Admin Notifications',
							'options' => array(
								'instructor_application_received' => __( 'Instructor Application Received', 'tutor-pro' ),
                            ),
							'desc'    => __( 'Notifications to send to admin', 'tutor-pro' ),
						),
					),
				),
			),
		);

		return  $attr;
	}

    /**
     * Load notification template
     */
    public function load_notification_template() {
        echo '<div id="tutor-notifications-wrapper"></div>';
    }

    /**
     * Add notification checkbox in announcement editor
     */
    public function notification_checkbox_for_announcement() {

		$notify_all_students = tutor_utils()->get_option( 'tutor_notifications_to_students.new_announcement_posted' );

		if ( $notify_all_students ) : ?>
			<div class="tutor-option-field-row">
				<div class="tutor-form-group">
					<label>
						<input type="checkbox" name="tutor_notify_all_students" checked="checked">
						<?php _e( 'Send notifications to all students of this course.', 'tutor-pro' ); ?>
					</label>
				</div>
			</div>
		<?php endif;
	}
}