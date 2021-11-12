<?php 
/**
 * Analytics reviews template
 * 
 * @since 1.9.9
 */
global $wp_query, $wp;
$user           = wp_get_current_user();

$courses        = tutor_utils()->get_courses_by_instructor( $user->ID );
$course_id      = isset( $_GET['course-id'] ) ? $_GET['course-id'] : '';
$date_filter    = isset( $_GET['date'] ) ? $_GET['date'] : '';
$paged      = 1;
$url        = home_url( $wp->request );
$url_path   = parse_url($url, PHP_URL_PATH);
$basename   = pathinfo($url_path, PATHINFO_BASENAME);

if ( isset($_GET['paged']) && is_numeric($_GET['paged']) ) {
    $paged = $_GET['paged'];
} else {
    is_numeric( $basename ) ? $paged = $basename : '';
}
$per_page   = 10;
$offset     = ($per_page * $paged) - $per_page;
$reviews    = tutor_utils()->get_reviews_by_instructor( $user->ID, $offset, $per_page, $course_id, $date_filter );
?>
<div class="tutor-analytics-reviews">
    <div class="tutor-dashboard-announcement-sorting-wrap">
        <div class="tutor-form-group">
            <label for="">
                <?php _e('Courses', 'tutor'); ?>
            </label>
            <select class="tutor-report-category tutor-announcement-course-sorting ignore-nice-select">
            
                <option value=""><?php _e('All', 'tutor'); ?></option>
            
                <?php if ($courses) : ?>
                    <?php foreach ($courses as $course) : ?>
                        <option value="<?php echo esc_attr($course->ID) ?>" <?php selected($course_id, $course->ID, 'selected') ?>>
                            <?php echo $course->post_title; ?>
                        </option>
                    <?php endforeach; ?>
                <?php else : ?>
                    <option value=""><?php _e('No course found', 'tutor'); ?></option>
                <?php endif; ?>
            </select>
        </div>

        <div class="tutor-form-group tutor-announcement-datepicker">
            <label><?php _e('Date', 'tutor'); ?></label>
            <input type="text" class="tutor_date_picker tutor-announcement-date-sorting" id="tutor-announcement-datepicker" value="<?php echo $date_filter; ?>" autocomplete="off" />
            <i class="tutor-icon-calendar"></i>
        </div>
    </div>
    <div class="reviews-content-wrapper">
        <div class="tutor-table-responsive">
            <table class="tutor-table">
                <thead>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Student', 'tutor-pro' ); ?>
                        </span>
                    </th>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Date', 'tutor-pro' ); ?>
                        </span>
                    </th>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Feedback', 'tutor-pro' ); ?>
                        </span>
                    </th>
                </thead>
                <tbody>
                    <?php if ( count( $reviews->results ) ): ?>
                        <?php foreach( $reviews->results as $review): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $student    = get_userdata( $review->user_id );
                                        $first_name = get_user_meta( $review->user_id, 'first_name', true );
                                        $last_name  = get_user_meta( $review->user_id, 'last_name', true );
                                        $name       = esc_html__( $first_name.' '.$last_name );
                                        if ( '' === $first_name && '' === $last_name ) {
                                            $name = $student->display_name == '' ? esc_html__( $student->user_nicename ) : esc_html__( $student->display_name ); 
                                        }
                                    ?>
                                    <div class="td-avatar">
                                        <?php echo tutor_utils()->get_tutor_avatar( $review->user_id ); ?>
                                        <div>
                                            <p class="color-text-primary text-medium-body">
                                                <?php echo $name; ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="color-text-primary text-regular-caption">
                                        <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ).' '.get_option( 'time_format' ), $review->comment_date ) ); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="rating">
                                        <?php 
                                            $rating = esc_html( $review->rating) ;
                                            tutor_utils()->star_rating_generator( $rating );
                                        ?>
                                        <span>
                                            <?php esc_html_e( $rating); ?>
                                        </span>
                                    </div>
                                    <div class="content">
                                        <?php esc_html_e( $review->comment_content); ?>
                                    </div>
                                    <div class="course">
                                        <strong>
                                            <?php _e( 'Course: ' ); ?>
                                        </strong>
                                        <span>
                                            <?php esc_html_e( $review->course_title); ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">
                                <?php _e( 'No record found' ); ?>
                            </td>
                        </tr>
                    <?php endif; ?>    
                </tbody>
            </table>
        </div>
    </div>
    <?php if ( $reviews->count ): ?>
        <div class="tutor-pagination-wrapper">
                <div class="page-info">
                    <?php 
                        $total_page = ceil( $reviews->count / $per_page );
                        _e( "Page <strong>$paged</strong> of <strong>$total_page</strong>", 'tutor-pro'); 
                    ?>
                </div>
                <div class="pagination">
                    <?php
                $big = 999999;
                
                $url = esc_url( tutor_utils()->get_tutor_dashboard_page_permalink()."analytics/reviews/?paged=%#%");
                echo paginate_links( array(
                    'base'      => str_replace( 1, '%#%', $url ),
                    'current'   => sanitize_text_field( $paged ),
                    'format'    => '?paged=%#%',
                    'total'     => $total_page,
                    'prev_text' => __( "<i class='tutor-icon-angle-left'></i>", 'tutor-pro' ),
                    'next_text' => __( "<i class='tutor-icon-angle-right'></i>", 'tutor-pro' )
                ) );
                    ?>
                </div>
        </div>
    <?php endif; ?>
</div>