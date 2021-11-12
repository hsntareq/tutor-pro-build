<?php
/**
 * Overview tempate 
 * 
 * @since 1.9.9
 */
use \TUTOR_REPORT\Analytics;
use \TUTOR_REPORT\CourseAnalytics;

//global variables
$user           = wp_get_current_user();
$course_id      = isset( $_GET['course_id'] ) ? $_GET['course_id'] : 0;
$course_details = '';
if ( $course_id ) {
    $course_details = get_post( $course_id, OBJECT );
}
//if not valid course or not author of this course the return
if ( '' === $course_details || is_null( $course_details ) ) {
    return _e( 'Invalid course', 'tutor-pro' );
}
if ( $course_details->post_author != $user->ID ) {
    return _e( 'Invalid course', 'tutor-pro' );
}

$time_period  = $active = isset( $_GET['period'] ) ? $_GET['period'] : '';
$start_date   = isset( $_GET['start_date']) ? sanitize_text_field( $_GET['start_date'] ) : '';
$end_date     = isset( $_GET['end_date']) ? sanitize_text_field( $_GET['end_date'] ) : '';
if ( '' !== $start_date ) {
    $start_date = tutor_get_formated_date( 'Y-m-d', $start_date);
} 
if ( '' !== $end_date ) {
    $end_date = tutor_get_formated_date( 'Y-m-d', $end_date);
} 
$previous_url = esc_url( tutor_utils()->tutor_dashboard_url().'courses' );

?>
<div class="analytics-course-details">
    <div class="back-summary-wrapper">
        <div class="back-wrapper">
            <a href="<?php echo esc_url( tutor_utils()->tutor_dashboard_url().'analytics/courses' );?>">
                <i class="tutor-icon-next-2"></i> <?php _e( 'Back', 'tutor-pro' ); ?>
            </a>
        </div>
        <div class="course-summary">
            <h4>
                <?php esc_html_e( $course_details->post_title ); ?>
            </h4>
            <div class="summary">
                <div class="label-value">
                    <label>
                        <?php _e( 'Published Date', 'tutor-pro' ); ?>
                    </label>
                    <span>
                        <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $course_details->post_date ) ); ?>
                    </span>
                </div>
                <div class="label-value" style="display: flex;justify-content: flex-start;">
                    <img src="<?php echo esc_url( TUTOR_REPORT()->url.'assets/images/last-update.svg'); ?>" alt="" style="width: 20px;margin-right: 3px;align-items: center;padding-top: 5px;height: 22px;">
                    <label>
                        <?php _e( 'Last Update', 'tutor-pro' ); ?>
                    </label>
                    <span>
                        <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $course_details->post_modified ) ); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- box cards -->
    <?php 
        $card_template    = TUTOR_REPORT()->path.'templates/elements/box-card.php';
        $total_student    = CourseAnalytics::course_enrollments_with_student_details( $course_id );
        $total_ratings    = tutor_utils()->get_course_rating($course_id);
        $total_qa         = CourseAnalytics::course_question_answer( $course_id );
        $total_assignment = CourseAnalytics::submitted_assignment_by_course( $course_id );

        $data  = array(
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M34.947 11.003c-2.791.158-8.338.735-11.763 2.83a.783.783 0 0 0-.37.671v18.531c0 .588.643.96 1.186.687 3.523-1.773 8.618-2.257 11.138-2.39.86-.045 1.528-.734 1.528-1.56V12.565c0-.902-.781-1.616-1.72-1.563Zm-14.132 2.83c-3.424-2.095-8.97-2.671-11.762-2.83-.938-.053-1.72.661-1.72 1.563v17.206c0 .827.669 1.516 1.529 1.561 2.52.133 7.618.617 11.141 2.391.54.273 1.182-.098 1.182-.685V14.495a.767.767 0 0 0-.37-.661Z" fill="#3E64DE"/></svg>',
                'title'     => esc_html__( $total_student['total_enrollments'] ),
                'sub_title' => __( 'Total Student', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M23.203 26.299a2.482 2.482 0 0 1-2.405 0L7.821 18.332a.828.828 0 0 1-.487-.716.898.898 0 0 1 .487-.745l13.006-6.47a2.44 2.44 0 0 1 2.406 0l12.977 6.453a.896.896 0 0 1 .457.762.826.826 0 0 1-.487.716l-12.977 7.967Zm.687 1.431 8.741-5.327c.358-.135.54 0 .54.429v5.497a3.162 3.162 0 0 1-.886 2.065 8.038 8.038 0 0 1-2.376 1.848 14.666 14.666 0 0 1-3.555 1.302A17.538 17.538 0 0 1 22 34.06c-1.467.011-2.93-.162-4.353-.516a14.666 14.666 0 0 1-3.556-1.302 8.039 8.039 0 0 1-2.346-1.848 3.162 3.162 0 0 1-.892-2.095v-5.21c0-.533.258-.733.774-.586l8.507 5.245c.279.157.576.277.886.358a3.968 3.968 0 0 0 2.006 0c.302-.089.592-.215.863-.376Zm11.733 2.406a.148.148 0 0 0 .059.129l.088.07c.225.13.411.319.54.546a1.524 1.524 0 0 1-2.39 1.849 1.52 1.52 0 0 1-.444-1.075c.007-.278.097-.547.258-.774.132-.22.313-.407.528-.546a.387.387 0 0 0 .059-.07.17.17 0 0 0 .04-.13v-7.96a.916.916 0 0 1 .177-.587c.122-.11.255-.207.399-.288.146-.088.299-.164.457-.228.118-.077.194 0 .229.258V30.136Z" fill="#3E64DE"/></svg>',
                'title'     => esc_html__( $total_student['total_inprogress'] ),
                'sub_title' => __( 'In Progress Courses', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M34.382 9.62a1.364 1.364 0 0 1 .996.373A1.364 1.364 0 0 1 35.752 11v3.3a6.347 6.347 0 0 1-.402 2.255 6.516 6.516 0 0 1-1.171 1.881 6.199 6.199 0 0 1-1.727 1.364 6.65 6.65 0 0 1-2.2.698v.133a8.251 8.251 0 0 1-3.9 7.045c-.788.485-1.65.84-2.552 1.05l.952 2.888h3.168c.285-.001.561.098.78.28.221.175.38.416.452.688l1.1 3.168h-16.5l1.1-3.168c.076-.271.238-.51.462-.682.219-.182.495-.281.78-.28h3.158l.94-2.872a8.593 8.593 0 0 1-2.552-1.05 8.053 8.053 0 0 1-2.057-1.848 8.356 8.356 0 0 1-1.342-2.415 8.251 8.251 0 0 1-.49-2.804v-.133a6.65 6.65 0 0 1-2.2-.698 6.2 6.2 0 0 1-1.732-1.37 6.517 6.517 0 0 1-1.166-1.88 6.347 6.347 0 0 1-.401-2.25V11a1.364 1.364 0 0 1 .374-1.007 1.364 1.364 0 0 1 .995-.373h4.13V8.25h16.5v1.37h4.131Zm-20.63 2.766h-2.75V14.3a3.3 3.3 0 0 0 .82 2.2 3.817 3.817 0 0 0 1.93 1.232v-5.346Zm11 9.614-.671-2.75 2.068-2.75h-2.91l-1.237-2.75-1.238 2.75h-2.898l2.068 2.75-.682 2.75 2.75-1.502L24.752 22Zm8.25-9.614h-2.75v5.346a3.146 3.146 0 0 0 1.947-1.199 3.47 3.47 0 0 0 .803-2.233v-1.914Z" fill="#3E64DE"/></svg>',
                'title'     => esc_html__( $total_student['total_completed'] ),
                'sub_title' => __( 'Completed Courses', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M7.224 12.149a.77.77 0 0 1-.587-.258.768.768 0 0 1-.217-.605 5.55 5.55 0 0 1 .516-2.006A4.875 4.875 0 0 1 8.11 7.737a5.033 5.033 0 0 1 1.678-.991 6.165 6.165 0 0 1 2.048-.329 6.606 6.606 0 0 1 2.135.329 5.315 5.315 0 0 1 1.707.962c.501.411.902.93 1.174 1.52.256.593.382 1.236.37 1.882.001.372-.042.742-.13 1.103a4.598 4.598 0 0 1-.387 1.045 5.75 5.75 0 0 1-.716.973 9.829 9.829 0 0 1-1.114 1.062 8.353 8.353 0 0 0-.963.898 4.107 4.107 0 0 0-.299.416 2.4 2.4 0 0 0-.129.476c-.056.374-.08.753-.07 1.132a.769.769 0 0 1-.23.586.78.78 0 0 1-.586.23H10.89a.793.793 0 0 1-.587-.23.74.74 0 0 1-.24-.586c-.007-.59.034-1.178.123-1.76.08-.43.22-.847.417-1.238.197-.356.43-.692.692-1.003.32-.376.673-.722 1.056-1.033.325-.28.632-.58.92-.897.103-.113.197-.232.282-.358a1.534 1.534 0 0 0 .176-.716c0-.234-.04-.466-.117-.686a1.369 1.369 0 0 0-.37-.546 1.678 1.678 0 0 0-.586-.381 2.147 2.147 0 0 0-.774-.13c-.26-.012-.52.022-.769.1a1.449 1.449 0 0 0-.534.37 1.76 1.76 0 0 0-.434.587c-.113.3-.182.613-.205.933a.821.821 0 0 1-.288.486.757.757 0 0 1-.51.206H7.224Zm3.666 11.428a.791.791 0 0 1-.587-.23.75.75 0 0 1-.24-.586v-1.677a.757.757 0 0 1 .27-.587.762.762 0 0 1 .586-.247h1.72a.756.756 0 0 1 .586.247.792.792 0 0 1 .229.557v1.719a.78.78 0 0 1-.804.804h-1.76Zm6.453 2.516H27.88l2.346-6.154v-9.28a2.488 2.488 0 0 1 .745-1.819 2.422 2.422 0 0 1 1.807-.763 2.458 2.458 0 0 1 1.83.763 2.487 2.487 0 0 1 .745 1.819v9.738c.002.163-.012.326-.04.487-.028.147-.07.29-.13.428l-3.267 8.683a.836.836 0 0 1-.082.21c-.041.071-.077.13-.118.189v4.552a.78.78 0 0 1-.803.804H13.988a.78.78 0 0 1-.804-.804v-4.693a4.152 4.152 0 0 1 4.183-4.183l-.024.023Zm2.752-1.66a6.42 6.42 0 0 1-1.907-1.285 6.084 6.084 0 0 1-1.76-4.241 5.574 5.574 0 0 1 .487-2.347 5.867 5.867 0 0 1 1.273-1.9 6.253 6.253 0 0 1 1.907-1.303 5.866 5.866 0 0 1 2.346-.463 5.795 5.795 0 0 1 2.318.463c.71.312 1.357.754 1.906 1.303.545.543.978 1.19 1.273 1.9.33.738.497 1.539.487 2.347a6.083 6.083 0 0 1-1.76 4.241 6.418 6.418 0 0 1-1.906 1.285 5.894 5.894 0 0 1-2.318.458 5.978 5.978 0 0 1-2.323-.458h-.023Z" fill="#3E64DE"/></svg>',
                'title'     => esc_html__( $total_qa['total_q_a'] ) ,
                'sub_title' => __( 'Questions', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M21.741 7.333c.225.002.447.043.656.123.226.072.434.189.613.343.184.173.33.382.43.613l3.69 7.503 8.35 1.227c.239.03.469.111.673.239.196.124.373.275.528.447.13.187.23.394.294.613.06.203.09.414.092.625.003.228-.048.452-.147.656-.102.2-.234.382-.393.54l-6.075 5.928 1.435 8.349c.046.222.046.452 0 .674-.05.218-.138.425-.258.613a1.585 1.585 0 0 1-.447.497 1.839 1.839 0 0 1-1.287.33 1.576 1.576 0 0 1-.613-.226l-7.54-3.893-7.516 3.893a1.575 1.575 0 0 1-.613.227 1.839 1.839 0 0 1-1.287-.331 1.586 1.586 0 0 1-.448-.497 1.98 1.98 0 0 1-.258-.613 1.654 1.654 0 0 1 0-.674l1.435-8.35-6.1-5.897a2.232 2.232 0 0 1-.373-.57 1.876 1.876 0 0 1-.166-.656c0-.217.03-.434.092-.643.065-.225.176-.433.325-.613a1.91 1.91 0 0 1 .496-.448c.206-.122.437-.197.675-.22l8.349-1.226 3.74-7.54c.097-.221.24-.418.422-.577.18-.154.388-.27.613-.343.197-.075.404-.116.613-.123Z" fill="#3E64DE"/></svg>',
                'title'     => esc_html__( $total_ratings->rating_avg ),
                'sub_title' => esc_html__( $total_ratings->rating_count.' Reviews ', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M29.717 13.097c.041.095.065.196.07.299V35.81a.88.88 0 0 1-.856.857H8.178a.823.823 0 0 1-.587-.259.822.822 0 0 1-.258-.587V8.178a.822.822 0 0 1 .258-.587.822.822 0 0 1 .587-.258h15.576c.104.006.205.03.3.07.112.041.214.105.3.188l5.158 5.218c.088.08.158.178.205.288ZM17.392 14a.845.845 0 0 0 .259-.587.82.82 0 0 0-.259-.587.815.815 0 0 0-.586-.259.798.798 0 0 0-.587.259l-2.84 2.834-1.116-1.115a.816.816 0 0 0-.587-.258.8.8 0 0 0-.587.258.822.822 0 0 0-.258.587.845.845 0 0 0 .258.587l1.72 1.72c.07.09.164.158.27.2a.968.968 0 0 0 .663 0 .674.674 0 0 0 .27-.2l3.38-3.44Zm0 6.878a.839.839 0 0 0 0-1.21.828.828 0 0 0-.586-.251.815.815 0 0 0-.587.252l-2.84 2.84-1.157-1.103a.83.83 0 0 0-.587-.252.817.817 0 0 0-.587.252.84.84 0 0 0-.03 1.174l1.72 1.76a.64.64 0 0 0 .27.2c.216.07.448.07.664 0a.675.675 0 0 0 .27-.2l3.45-3.462Zm0 6.873a.82.82 0 0 0 .259-.587.845.845 0 0 0-.259-.587.834.834 0 0 0-.586-.258.823.823 0 0 0-.587.258l-2.882 2.811-1.115-1.121a.834.834 0 0 0-.587-.258.821.821 0 0 0-.587.258.845.845 0 0 0-.258.587.82.82 0 0 0 .258.587l1.72 1.72a.587.587 0 0 0 .27.205c.215.07.447.07.663 0a.64.64 0 0 0 .27-.206l3.421-3.41Zm8.71-10.312a.82.82 0 0 0 .258-.587.88.88 0 0 0-.845-.875h-5.282a.88.88 0 0 0-.857.857.88.88 0 0 0 .857.863h5.282a.82.82 0 0 0 .587-.258Zm0 6.902a.863.863 0 0 0-.587-1.468h-5.282a.863.863 0 0 0 0 1.72h5.282a.816.816 0 0 0 .587-.252Zm0 6.872a.8.8 0 0 0 .258-.587.88.88 0 0 0-.845-.898h-5.282a.88.88 0 0 0-.857.863.88.88 0 0 0 .857.857h5.282a.797.797 0 0 0 .587-.258v.023Zm.763-18.675-2.236-2.236v2.236h2.236Zm9.044.757a2.488 2.488 0 0 1 .757 1.82v4.302h-5.158v-4.302a2.576 2.576 0 0 1 4.401-1.82ZM31.508 28.01h5.158v-6.873h-5.158v6.873Zm5.158 1.72v.862c.01.068.01.137 0 .206a1.32 1.32 0 0 1-.058.182l-1.72 3.439a.857.857 0 0 1-1.544 0l-1.72-3.44a1.307 1.307 0 0 1-.058-.181.776.776 0 0 1 0-.188v-.88h5.1Zm-1.72-14.614a.82.82 0 0 0-.258-.587.826.826 0 0 0-1.203 0 .823.823 0 0 0-.258.587v.862h1.72v-.862Z" fill="#3E64DE"/></svg>',
                'title'     => $total_assignment['total_assignments'],
                'sub_title' => __( 'Assignment Submit', 'tutor-pro' ),
                'price'     => false
            )
        );

        tutor_load_template_from_custom_path($card_template, $data);
    ?>
    <!-- box cards end -->
    <!--filter buttons tabs-->
    <?php 
        /**
         * Prepare filter period buttons
         * 
         * Array structure is required as below
         * 
         * @since 1.9.8
         */
        $filter_period = array(
            array(
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url()."analytics/course-details?course_id=$course_id&period=today" ),
                'title' => __( 'Today', 'tutor-pro' ),
                'class' => 'tutor-analytics-period-button',
                'type'  => 'today'
            ),
            array(
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url()."analytics/course-details?course_id=$course_id&period=monthly" ),
                'title' => __( 'Monthly', 'tutor-pro' ),
                'class' => 'tutor-analytics-period-button',
                'type'  => 'monthly'
            ),
            array(
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url()."analytics/course-details?course_id=$course_id&period=yearly" ),
                'title' => __( 'Yearly', 'tutor-pro' ),
                'class' => 'tutor-analytics-period-button',
                'type'  => 'yearly'
            ),
        );

        /**
         * Calendar date buttons
         * 
         * Array structure is required as below
         * 
         * @since 1.9.8
         */
        
        $filter_period_calendar = array(
            'filter_period'     => $filter_period,
            'filter_calendar'   => true
        );

        $filter_period_calendar_template = TUTOR_REPORT()->path.'templates/elements/period-calendar.php';
        tutor_load_template_from_custom_path($filter_period_calendar_template, $filter_period_calendar);
    ?>
    <!--filter button tabs end-->
    <!--analytics graph -->
    <?php
        /**
         * Get analytics data
         * 
         * @since 1.9.9
         */
        $earnings       = Analytics::get_earnings_by_user( $user->ID, $time_period, $start_date, $end_date, $course_id ); 
        $discounts      = Analytics::get_discounts_by_user( $user->ID, $time_period, $start_date, $end_date, $course_id ); 
        $refunds        = Analytics::get_refunds_by_user( $user->ID, $time_period, $start_date, $end_date, $course_id); 
        $content_title  = ''; 
        if (  'today' === $time_period ) {
            $day = date('l');
            $content_title = __( "for today ($day) ", 'tutor-pro' );
        } else if ( 'monthly' === $time_period ) {
            $month = date('F');
            $content_title = __( "for this month ($month) ", 'tutor-pro' );
        } else if ( 'yearly' === $time_period ) {
            $year = date('Y');
            $content_title = __( "for this year ($year) ", 'tutor-pro' );
        }
        $graph_tabs = array(
            array(
                'tab_title'     => __( 'Total Earning', 'tutor-pro' ),
                'tab_value'     => $earnings['total_earnings'],
                'data_attr'     => 'ta_total_earnings',
                'active'        => 'active',
                'price'         => true,
                'content_title' => __( 'Earning chart '.$content_title, 'tutor-pro' )
            ),
            array(
                'tab_title'     => __( 'Discount', 'tutor-pro' ),
                'tab_value'     => $discounts['total_discounts'],
                'data_attr'     => 'ta_total_discount',
                'active'        => '',
                'price'         => true,
                'content_title' => __( 'Discount chart '.$content_title, 'tutor-pro' )
            ),
            array(
                'tab_title'     => __( 'Refund', 'tutor-pro' ),
                'tab_value'     => $refunds['total_refunds'],
                'data_attr'     => 'ta_total_refund',
                'active'        => '',
                'price'         => true,
                'content_title' => __( 'Refund chart '.$content_title, 'tutor-pro' )
            ),
        );
        $graph_template = TUTOR_REPORT()->path.'templates/elements/graph.php';
        tutor_load_template_from_custom_path($graph_template, $graph_tabs);
    ?>
    <!--analytics graph end -->     
</div>