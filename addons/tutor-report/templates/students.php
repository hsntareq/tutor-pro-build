<?php
global $wp_query, $wp;
$user       = wp_get_current_user();
$paged      = 1;
$url        = home_url( $wp->request );
$url_path   = parse_url($url, PHP_URL_PATH);
$basename   = pathinfo($url_path, PATHINFO_BASENAME);

if ( isset($_GET['paged']) && is_numeric($_GET['paged']) ) {
    $paged = $_GET['paged'];
} else {
    is_numeric( $basename ) ? $paged = $basename : '';
}
$per_page       = 10;
$offset         = ($per_page * $paged) - $per_page;
$course_id      = isset( $_GET['course-id'] ) ? $_GET['course-id'] : '';
$date_filter    = isset( $_GET['date'] ) && $_GET['date'] != '' ? $_GET['date'] : '';

$orderby    = '';
if ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'registration_date' ) {
    $orderby = 'registration_date';
} else if ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'course_taken' ) {
    $orderby = 'course_taken';
} else {
    $orderby = 'student';
}

$order      = ( !isset( $_GET['order'] ) || $_GET['order'] !== 'desc' ) ? 'asc' : 'desc';
$sort_order = array('order' => $order == 'asc' ? 'desc' : 'asc');

$student_sort            = http_build_query(array_merge($_GET, ( $orderby == 'student' ? $sort_order : array() ), array( 'orderby' => 'student' )));
$registration_date_sort  = http_build_query(array_merge($_GET, ( $orderby == 'registration_date' ? $sort_order : array() ), array( 'orderby' => 'registration_date' )));
$course_taken_sort       = http_build_query(array_merge($_GET, ( $orderby == 'course_taken' ? $sort_order : array() ), array( 'orderby' => 'course_taken' )));

$student_sort_icon      = $orderby == 'student' ? (strtolower( $order ) == 'asc' ? 'up' : 'down') : 'up';
$register_sort_icon     = $orderby == 'registration_date' ? (strtolower( $order ) == 'asc' ? 'up' : 'down') : 'up';
$course_taken_sort_icon = $orderby == 'course_taken' ? (strtolower( $order ) == 'asc' ? 'up' : 'down') : 'up';

$search_filter  = isset( $_GET['search'] ) ? $_GET['search'] : '';

$students   = tutor_utils()->get_students_by_instructor( $user->ID, $offset, $per_page, $search_filter, $course_id, $date_filter, $sort_order, $order); 
$courses    = tutor_utils()->get_courses_by_instructor();
?>
<div class="tutor-analytics-students">

    <div class="tutor-dashboard-announcement-sorting-wrap">
        <div class="tutor-form-group">
            <form action="" method="get" id="tutor_analytics_search_form" style="padding-top:37px;">
                <i class="tutor-icon-magnifying-glass-1" id="tutor_analytics_search_icon" style="position: absolute;top: 50%;left: 10px;"></i>
                <input type="text" name="search" placeholder="<?php esc_attr_e( 'Search...', 'tutor-pro' ); ?>" style="padding-left: 20px;">
            </form>
        </div>
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

    <div class="students-wrapper">
        <div class="tutor-table-responsive">
            <table class="tutor-table">
                <thead>
                    <tr>
                        <th>
                            <div class="inline-flex-center">
                                <span class="color-text-subsued text-regular-small">
                                    <?php _e( 'Student', 'tutor-pro' ); ?>
                                </span>
                                <a href="?<?php echo $student_sort; ?>">
                                    <img src="<?php echo esc_url( tutor_pro()->url ) ; ?>assets/images/order-<?php echo $student_sort_icon; ?>.svg"/>
                                </a>
                            </div>
                        </th>
                        <th>
                            <div class="inline-flex-center">
                                <span class="color-text-subsued text-regular-small">
                                    <?php _e( 'Registration Date', 'tutor-pro' ); ?>
                                </span>
                                <a href="?<?php echo $registration_date_sort; ?>">
                                    <img src="<?php echo esc_url( tutor_pro()->url ) ; ?>assets/images/order-<?php echo $register_sort_icon; ?>.svg"/>
                                </a>
                            </div>
                        </th>
                        <th>
                            <div class="inline-flex-center">
                                <span class="color-text-subsued text-regular-small">
                                    <?php _e( 'Course Taken', 'tutor-pro' ); ?>
                                </span>
                                <a href="?<?php echo $course_taken_sort; ?>">
                                    <img src="<?php echo esc_url( tutor_pro()->url ); ?>assets/images/order-<?php echo $course_taken_sort_icon; ?>.svg"/>
                                </a>
                            </div>
                        </th>
                        <th class="tutor-shrink"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( count( $students['students'] ) ): ?>
                        <?php foreach( $students['students'] as $student ): ?>
                            <tr>
                                <td>
                                    <?php 
                                        $first_name = get_user_meta( $student->ID, 'first_name', true );
                                        $last_name  = get_user_meta( $student->ID, 'last_name', true );
                                        $name       = esc_html__( $student->display_name );
                                        if ( '' === $name ) {
                                            $name = $first_name.' '.$last_name; 
                                        }
                                    ?>
                                    <div class="td-avatar">
                                        <?php 
                                            echo tutor_utils()->get_tutor_avatar( $student->ID );
                                        ?>
                                        <div>
                                            <p class="color-text-primary text-medium-body">
                                                <?php echo $name; ?>
                                            </p>
                                            <p class="color-text-primary text-medium-body">
                                                <?php esc_html_e( $student->user_email ); ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="color-text-primary text-regular-caption">
                                        <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $student->user_registered ) ); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="color-text-primary text-regular-caption">
                                        <?php esc_html_e( $student->course_taken ); ?>
                                    </span>
                                </td>
                                <td class="td-action-btns">
                                    <a href="<?php echo esc_url( tutor_utils()->tutor_dashboard_url()."analytics/student-details?student_id=$student->ID" ); ?>" class="btn-outline tutor-btn">
                                        <?php _e( 'Details', 'tutor-pro' ); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php else: ?>
                            <tr>
                                <td colspan="4">
                                    <?php _e( 'No record found', 'tutor-pro' ); ?>
                                </td>
                            </tr>
                    <?php endif;?>    
                </tbody>
            </table>
        </div>
    </div>
    <?php if ( $students['total_students'] ): ?>
        <div class="tutor-pagination-wrapper">
            
                <div class="page-info">
                    <?php 
                        $total_page = ceil( $students['total_students'] / $per_page );
                        _e( "Page $paged of $total_page", 'tutor-pro'); 
                    ?>
                </div>
                <div class="pagination">
                    <?php
                $big = 999999;
                
                $url = esc_url( tutor_utils()->get_tutor_dashboard_page_permalink()."analytics/students/?paged=%#%");
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