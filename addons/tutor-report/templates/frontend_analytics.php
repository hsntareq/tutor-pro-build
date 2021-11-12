<?php
/**
 * Analytics template 
 * 
 * @since 1.9.8
 */
global $wp_query;
if ( !current_user_can( tutor()->instructor_role ) ) {
    return;
}
$query_vars         = $wp_query->query_vars;
$report_instance    = tutor_report_instance();

$current_page   = isset( $query_vars['tutor_dashboard_sub_page'] ) ? $query_vars['tutor_dashboard_sub_page'] : 'overview';
$sub_pages      = $report_instance->analytics->sub_pages();
$arr = explode('/', $current_page);
if ( count( $arr ) ) {
    if (array_key_exists( $arr[0], $sub_pages) ) {
        $current_page = $arr[0];
    }
} 

?>
<div class="tutor-analytics-wrapper">
    <?php 
        /**
         * Course details page design need to display as stand alone 
         * 
         * That is why it is not included as sub page
         * 
         * @since 1.9.9
         */
        if ( 'course-details' === $current_page ) {
            include_once TUTOR_REPORT()->path.'templates/course_details.php';
            return;
        }
        if ( 'student-details' === $current_page ) {
            include_once TUTOR_REPORT()->path.'templates/student_details.php';
            return;
        }
    ?>
    <!--sub pages menu-->
    <div class="tutor-dashboard-inline-links tutor-report-menu">
        <h3 class=="analytics-title">
            <?php _e( 'Analytics', 'tutor-pro' ); ?>
        </h3>
        <ul>
            <?php foreach($sub_pages as $key => $page): ?>
            <?php $active = $current_page === $key ? 'active' : ''  ;?>
            <li class="<?php esc_attr_e($active);?>">
                <a href="<?php echo $page['url'];?>">
                    <?php echo $page['title'];?>
                </a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
    <!--sub pages menu end-->

    <!--load sub pages -->
    <div class="sub-page-wrapper">
        <?php echo $report_instance->analytics->load_sub_page($current_page); ?>
    </div>
    <!--load sub pages end -->
</div>