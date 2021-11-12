<?php
/**
 * Overview tempate 
 * 
 * @since 1.9.9
 */
use \TUTOR_REPORT\Analytics;

//global variables
$user         = wp_get_current_user();
$time_period  = $active = isset( $_GET['period'] ) ? $_GET['period'] : '';
$start_date   = isset( $_GET['start_date']) ? sanitize_text_field( $_GET['start_date'] ) : '';
$end_date     = isset( $_GET['end_date']) ? sanitize_text_field( $_GET['end_date'] ) : '';
if ( '' !== $start_date ) {
    $start_date = tutor_get_formated_date( 'Y-m-d', $start_date);
} 
if ( '' !== $end_date ) {
    $end_date = tutor_get_formated_date( 'Y-m-d', $end_date);
} 
?>
<div class="tutor-analytics-overview">

    <?php 
        /**
         * Overview card info
         * 
         * @since 1.9.9
         */
        $card_template   = TUTOR_REPORT()->path.'templates/elements/box-card.php';
        $user       = wp_get_current_user();
        $data  = array(
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M23.203 26.299a2.482 2.482 0 0 1-2.405 0L7.821 18.332a.828.828 0 0 1-.487-.716.898.898 0 0 1 .487-.745l13.006-6.47a2.44 2.44 0 0 1 2.406 0l12.977 6.453a.896.896 0 0 1 .457.762.826.826 0 0 1-.487.716l-12.977 7.967Zm.687 1.431 8.741-5.327c.358-.135.54 0 .54.429v5.497a3.162 3.162 0 0 1-.886 2.065 8.038 8.038 0 0 1-2.376 1.848 14.666 14.666 0 0 1-3.555 1.302A17.538 17.538 0 0 1 22 34.06c-1.467.011-2.93-.162-4.353-.516a14.666 14.666 0 0 1-3.556-1.302 8.039 8.039 0 0 1-2.346-1.848 3.162 3.162 0 0 1-.892-2.095v-5.21c0-.533.258-.733.774-.586l8.507 5.245c.279.157.576.277.886.358a3.968 3.968 0 0 0 2.006 0c.302-.089.592-.215.863-.376Zm11.733 2.406a.148.148 0 0 0 .059.129l.088.07c.225.13.411.319.54.546a1.524 1.524 0 0 1-2.39 1.849 1.52 1.52 0 0 1-.444-1.075c.007-.278.097-.547.258-.774.132-.22.313-.407.528-.546a.387.387 0 0 0 .059-.07.17.17 0 0 0 .04-.13v-7.96a.916.916 0 0 1 .177-.587c.122-.11.255-.207.399-.288.146-.088.299-.164.457-.228.118-.077.194 0 .229.258V30.136Z" fill="#3E64DE"/></svg>',
                'title'     => count(tutor_utils()->get_courses_by_instructor($user->ID)),
                'sub_title' => __( 'Total Course', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M11.194 14.543H9.002a1.614 1.614 0 0 1-1.181-.49 1.678 1.678 0 0 1 0-2.364 1.612 1.612 0 0 1 1.181-.49h2.192V9.001a1.613 1.613 0 0 1 .49-1.181 1.678 1.678 0 0 1 2.363 0A1.612 1.612 0 0 1 14.537 9v2.192h2.245a1.672 1.672 0 0 1 1.654 1.678 1.672 1.672 0 0 1-1.654 1.672h-2.239v2.239a1.672 1.672 0 0 1-1.671 1.654 1.672 1.672 0 0 1-1.678-1.654v-2.24Zm11.047 14.91a5.808 5.808 0 0 1 2.038.55 6.156 6.156 0 0 1 3.043 3.202c.308.748.454 1.554.43 2.363l-.058 1.099H8.6l.06-1.182a5.949 5.949 0 0 1 1.624-4.135 6.64 6.64 0 0 1 1.772-1.312 6.013 6.013 0 0 1 2.192-.59 6.261 6.261 0 0 1-1.46-1.974 5.777 5.777 0 0 1-.53-2.469 5.907 5.907 0 0 1 1.771-4.242 6.464 6.464 0 0 1 1.92-1.293 5.82 5.82 0 0 1 2.363-.461 5.73 5.73 0 0 1 2.304.46c.713.313 1.364.751 1.92 1.294a5.807 5.807 0 0 1 1.235 1.926c.31.739.466 1.533.46 2.334a5.777 5.777 0 0 1-.53 2.47 6.091 6.091 0 0 1-1.46 1.96Zm12.855-5.074a5.73 5.73 0 0 1 1.311 1.99c.303.751.456 1.554.45 2.364l-.06 7.934h-7.171l.059-1.01v-.06a7.68 7.68 0 0 0-.591-3.101 8.376 8.376 0 0 0-1.713-2.611 13.529 13.529 0 0 0-.957-.804 5.878 5.878 0 0 0-1.034-.638c.25-.53.449-1.084.59-1.654.138-.58.205-1.176.201-1.772 0-.79-.12-1.575-.36-2.328a8.092 8.092 0 0 0-.98-2.08 7.486 7.486 0 0 0-1.53-1.671 6.91 6.91 0 0 0-1.933-1.182 5.908 5.908 0 0 1 .591-2.191 6.289 6.289 0 0 1 1.312-1.773 5.908 5.908 0 0 1 6.404-1.146 5.872 5.872 0 0 1 1.902 1.27 6.215 6.215 0 0 1 1.3 1.903c.311.748.468 1.552.46 2.363a5.862 5.862 0 0 1-1.99 4.442 5.806 5.806 0 0 1 2.038.55 6.13 6.13 0 0 1 1.7 1.205Z" fill="#3E64DE"/></svg>',
                'title'     => tutor_utils()->get_total_students_by_instructor($user->ID),
                'sub_title' => __( 'Total Student', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44"><path d="M21.741 7.333c.225.002.447.043.656.123.226.072.434.189.613.343.184.173.33.382.43.613l3.69 7.503 8.35 1.227c.239.03.469.111.673.239.196.124.373.275.528.447.13.187.23.394.294.613.06.203.09.414.092.625.003.228-.048.452-.147.656-.102.2-.234.382-.393.54l-6.075 5.928 1.435 8.349c.046.222.046.452 0 .674-.05.218-.138.425-.258.613a1.585 1.585 0 0 1-.447.497 1.839 1.839 0 0 1-1.287.33 1.576 1.576 0 0 1-.613-.226l-7.54-3.893-7.516 3.893a1.575 1.575 0 0 1-.613.227 1.839 1.839 0 0 1-1.287-.331 1.586 1.586 0 0 1-.448-.497 1.98 1.98 0 0 1-.258-.613 1.654 1.654 0 0 1 0-.674l1.435-8.35-6.1-5.897a2.232 2.232 0 0 1-.373-.57 1.876 1.876 0 0 1-.166-.656c0-.217.03-.434.092-.643.065-.225.176-.433.325-.613a1.91 1.91 0 0 1 .496-.448c.206-.122.437-.197.675-.22l8.349-1.226 3.74-7.54c.097-.221.24-.418.422-.577.18-.154.388-.27.613-.343.197-.075.404-.116.613-.123Z" fill="#3E64DE"/></svg>',
                'title'     => tutor_utils()->get_reviews_by_instructor($user->ID)->count,
                'sub_title' => __( 'Reviews', 'tutor-pro' ),
                'price'     => false
            )
        );
       
        tutor_load_template_from_custom_path($card_template, $data);
    ?>
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
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url().'analytics?period=today' ),
                'title' => __( 'Today', 'tutor-pro' ),
                'class' => 'tutor-analytics-period-button',
                'type'  => 'today'
            ),
            array(
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url().'analytics?period=monthly' ),
                'title' => __( 'Monthly', 'tutor-pro' ),
                'class' => 'tutor-analytics-period-button',
                'type'  => 'monthly'
            ),
            array(
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url().'analytics?period=yearly' ),
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
        $earnings       = Analytics::get_earnings_by_user( $user->ID, $time_period, $start_date, $end_date ); 
        $enrollments    = Analytics::get_total_students_by_user( $user->ID, $time_period, $start_date, $end_date ); 
        $discounts      = Analytics::get_discounts_by_user( $user->ID, $time_period, $start_date, $end_date ); 
        $refunds        = Analytics::get_refunds_by_user( $user->ID, $time_period, $start_date, $end_date); 
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
                'content_title' => __('Earnings chart '.$content_title, 'tutor-pro')
            ),
            array(
                'tab_title'     => __( 'Course Enrolled', 'tutor-pro' ),
                'tab_value'     => $enrollments['total_enrollments'],
                'data_attr'     => 'ta_total_course_enrolled',
                'active'        => '',
                'price'         => false,
                'content_title' => __('Course enrolled Chart '.$content_title, 'tutor-pro')
            ),
            array(
                'tab_title'     => __( 'Total Refund', 'tutor-pro' ),
                'tab_value'     => $refunds['total_refunds'],
                'data_attr'     => 'ta_total_refund',
                'active'        => '',
                'price'         => true,
                'content_title' => __('Refund chart '.$content_title, 'tutor-pro')
            ),
            array(
                'tab_title'     => __( 'Total Discount', 'tutor-pro' ),
                'tab_value'     => $discounts['total_discounts'],
                'data_attr'     => 'ta_total_discount',
                'active'        => '',
                'price'         => true,
                'content_title' => __('Discount chart '.$content_title, 'tutor-pro')
            )
        );
        $graph_template = TUTOR_REPORT()->path.'templates/elements/graph.php';
        tutor_load_template_from_custom_path($graph_template, $graph_tabs);
    ?>
    <!--analytics graph end -->

    <!--most popular courses-->
    <div class="tutor-table-responsive tutor-analytics-most-popular-courses">
        <?php 
            $popular_courses = tutor_utils()->most_popular_courses( $limit = 7, get_current_user_id() );
        ?>
        <h4>
            <?php _e( 'Most Popular Courses', 'tutor-pro' ); ?>
        </h4>
        <table class="tutor-table table-popular-courses" style="--column-count: 3">
            <thead>
                <th>
                    <span class="text-regular-small color-text-subsued">
                        <?php _e( 'Course Name', 'tutor-pro' ); ?>
                    </span>
                </th>
                <th class="tutor-analytics-js-sorting" data-order="down" data-id="total_enrolled_sort" data-icon="<?php echo esc_url( tutor_pro()->url.'addons/tutor-zoom/assets/images/order-' ); ?>">
                    <div class="inline-flex-center">
                        <span class="text-regular-small colo-text-subsued">
                            <?php _e( 'Total Enrolled', 'tutor-pro' ); ?>
                        </span>
                        <a>
                            <img src="<?php echo esc_url( tutor_pro()->url.'addons/tutor-zoom/assets/images/order-up.svg' ); ?>" id="total_enrolled_sort"/>
                        </a>
                    </div>
                </th>
                <th class="tutor-analytics-js-sorting" data-order="down" data-id="total_rating_sort" data-icon="<?php echo esc_url( tutor_pro()->url.'addons/tutor-zoom/assets/images/order-' ); ?>">
                    <div class="inline-flex-center">
                        <span class="text-regular-small color-text-subsued">
                            <?php _e( 'Rating', 'tutor-pro' ); ?>
                        </span>
                        <a data-order="up">
                            <img src="<?php echo esc_url( tutor_pro()->url.'addons/tutor-zoom/assets/images/order-down.svg' ); ?>" id="total_rating_sort"/>
                        </a>
                    </div>
                </th>
            </thead>
            <tbody>
                <?php if ( count( $popular_courses ) ):?>
                    <?php foreach( $popular_courses as $course ): ?>
                    <tr>
                        <td>
                            <div class="td-course text-medium-body color-text-primary">
                                <?php esc_html_e( $course->post_title ); ?>
                            </div>
                        </td>
                        <td>
                            <span class="text-medium-caption color-text-primary">
                                <?php esc_html_e( $course->total_enrolled ); ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                $rating = tutor_utils()->get_course_rating( $course->ID );
                                $avg_rating = !is_null( $rating) ? $rating->rating_avg : 0;
                                tutor_utils()->star_rating_generator( $avg_rating, true);
                                
                            ?>
                            <span class="popular-course-rating">
                                <?php esc_html_e( $avg_rating ); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td>
                        <?php _e( 'Course not found' ); ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!--most popular courses end-->

    <!--reviews-->
    <div class="tutor-table-responsive tutor-analytics-course-reviews">
        <?php
            $reviews = tutor_utils()->get_reviews_by_instructor($instructor_id = $user->ID, $offset = 0, $limit = 7);
        ?>
        <h4>
            <?php _e( 'Reviews', 'tutor-pro' );?>
        </h4>
        <table class="tutor-table table-reviews" style="--column-count: 3">
            <thead>
                <th>
                    <span class="text-regular-small color-text-subsued">
                        <?php _e( 'Student', 'tutor-pro' ); ?>
                    </span>
                </th>
                <th>
                    <span class="text-regular-small color-text-subsued">
                        <?php _e( 'Date', 'tutor-pro' ); ?>
                    </span>
                </th>
                <th>
                    <span class="text-regular-small color-text-subsued">
                        <?php _e( 'Feedback', 'tutor-pro' ); ?>
                    </span>
                </th>
            </thead>
            <tbody>
                <?php if ( !is_null( $reviews ) && count( $reviews->results ) ) :?>
                    <?php foreach( $reviews->results as  $key => $review ): ?>

                    <tr>
                        <td>
                            <div class="td-avatar">
                                <?php 
                                    echo tutor_utils()->get_tutor_avatar( $review->user_id );
                                ?>
                                <p class="text-medium-body color-text-primary">
                                    <?php esc_html_e( $review->display_name ); ?>
                                </p>
							</div>
                        </td>
                        <td>
                            <span class="color-text-primary text-medium-caption">
                                <?php 
                                    $date_time_format = get_option( 'date_format' ).' '.get_option( 'time_format' );
                                    $date = tutor_get_formated_date( $date_time_format, $review->comment_date );
                                    esc_html_e( $date ); 
                                ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                tutor_utils()->star_rating_generator( $review->rating, true);
                            ?>
                            <span class="popular-course-rating">
                                <?php esc_html_e( number_format($review->rating, 2) ); ?>
                            </span>
                            <p class="color-text-subsued review-text">
                                <?php  esc_html_e( $review->comment_content ); ?>
                            </p>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td>
                        <?php _e( 'Course not found' ); ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>        
    </div>
    <!--reviews end-->

</div>