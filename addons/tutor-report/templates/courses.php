

<?php 

    use \TUTOR_REPORT\Analytics;

    global $wp_query, $wp;
    $user           = wp_get_current_user();
    $paged = 1;
    $url      = home_url( $wp->request );
    $url_path = parse_url($url, PHP_URL_PATH);
    $basename = pathinfo($url_path, PATHINFO_BASENAME);

    if ( isset($_GET['paged']) && is_numeric($_GET['paged']) ) {
        $paged = $_GET['paged'];
    } else {
        is_numeric( $basename ) ? $paged = $basename : '';
    }
    $per_page   = 10;
    $offset     = ($per_page * $paged) - $per_page;
    $orderby    = ( !isset( $_GET['orderby'] ) || $_GET['orderby'] !== 'earning' ) ? 'learner' : 'earning';
    $order      = ( !isset( $_GET['order'] ) || $_GET['order'] !== 'desc' ) ? 'asc' : 'desc';
    $sort_order = array('order' => $order == 'asc' ? 'desc' : 'asc');

    $learner_sort = http_build_query(array_merge($_GET, ( $orderby == 'learner' ? $sort_order : array() ), array( 'orderby' => 'learner' )));
    $earning_sort = http_build_query(array_merge($_GET, ( $orderby == 'earning' ? $sort_order : array() ), array( 'orderby' => 'earning' )));
    
    $learner_order_icon = $orderby == 'learner' ? (strtolower( $order ) == 'asc' ? 'up' : 'down') : 'up';
    $earning_order_icon = $orderby == 'earning' ? (strtolower( $order ) == 'asc' ? 'up' : 'down') : 'up';

    $search = isset( $_GET['search'] ) ? $_GET['search'] : '';
 
    $courses        = Analytics::get_courses_with_total_enroll_earning ( $user->ID, $sort_order['order'], is_null($orderby) ? '' : $orderby, $offset, $per_page, $search);
    $total_course   = Analytics::get_courses_with_search_by_user( $user->ID, $search );
    
?>
<div class="tutor-analytics-courses">
    <div class="search-wrapper">
        <form action="" method="get" id="tutor_analytics_search_form">
            <i class="tutor-icon-magnifying-glass-1" id="tutor_analytics_search_icon"></i>
            <input type="text" name="search" placeholder="<?php esc_attr_e( 'Search...', 'tutor-pro' ); ?>">
        </form>
    </div>
    <div class="tutor-table-responsive">
        <table class="table-all-courses tutor-table" style="--column-count:4">
            <thead>
                <th>
                    <span class="color-text-subsued text-regular-small">
                        <?php _e( 'Course', 'tutor-pro' ); ?>
                    </span>
                </th>
                <th>
                    <div class="inline-flex-center">
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Total Learners', 'tutor-pro' ); ?>
                        </span> 
                        <a href="?<?php echo $learner_sort; ?>">
                            <img src="<?php echo tutor_pro()->url; ?>addons/tutor-zoom/assets/images/order-<?php echo $learner_order_icon ?>.svg"/>
                        </a>
                    </div>
                </th>
                <th>
                    <div class="inline-flex-center">
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Earnings', 'tutor-pro' ); ?>
                        </span> 

                    </div>
                </th>
                <th>

                </th>
            </thead>
            <tbody>
                <?php if( count( $courses ) ): ?>
                    <?php foreach( $courses as $course ): ?>
                    <?php 
                        $course->lesson     = tutor_utils()->get_lesson_count_by_course($course->ID);
                        $course->quiz       = Analytics::get_all_quiz_by_course($course->ID);
                        $course->assignment = tutor_utils()->get_assignments_by_course($course->ID)->count;
                    ?>    
                        <tr>
                            <td>
                                <div class="color-text-primary td-course text-medium-body">
                                    <span>
                                        <?php esc_html_e( $course->post_title ); ?>
                                    </span>
                                    <div class="course-meta">
                                        <span class="color-text-subsued text-thin-caption">
                                            <?php _e( 'Lesson', 'tutor-pro' ); ?>
                                            <strong class="text-medium-caption">
                                                <?php esc_html_e( $course->lesson ); ?>
                                            </strong>
                                        </span>
                                        <span class="color-text-subsued text-thin-caption">
                                            <?php _e( 'Assignment', 'tutor-pro' ); ?>
                                            <strong class="text-medium-caption">
                                                <?php esc_html_e( $course->assignment ); ?>
                                            </strong>
                                        </span>
                                        <span class="color-text-subsued text-thin-caption">
                                            <?php _e( 'Quiz', 'tutor-pro' ); ?>
                                            <strong class="text-medium-caption">
                                                <?php esc_html_e( $course->quiz ); ?>
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="color-text-primary text-medium-caption">
                                    <?php esc_html_e( $course->learner ); ?>
                                </span>
                            </td>
                            <td>
                                <span class="color-text-primary text-medium-caption">
                                    <?php 
                                        $earnings = Analytics::get_earnings_by_user( $user->ID, '', '', '', $course->ID );
                                        echo wp_kses_post( tutor_utils()->tutor_price( $earnings['total_earnings'] ) ); ?>
                                </span>
                            </td>
                            <td>
                                <div class="td-action-btns inline-flex-center">
									<a href="<?php echo esc_url( tutor_utils()->tutor_dashboard_url().'analytics/course-details?course_id='.$course->ID ); ?>" class="tutor-btn btn-outline">
                                        <?php _e('Details', 'tutor-pro') ?>
                                    </a>
									<a href="<?php echo $course->guid; ?>">
										<i class="tutor-icon-detail-link"></i>
									</a>
								</div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="4">
                            <?php _e( 'No record found' ); ?>
                        </td>
                    </tr>
                <?php endif; ?>    
            </tbody>
        </table>
    </div>
    <?php if ( $total_course ): ?>
        <div class="tutor-pagination-wrapper">
                <div class="page-info">
                    <?php 
                        $total_page = ceil( $total_course / $per_page );
                        _e( "Page $paged of $total_page", 'tutor-pro'); 
                    ?>
                </div>
                <div class="pagination">
                    <?php
                $big = 999999;
                
                $url = esc_url( tutor_utils()->get_tutor_dashboard_page_permalink()."analytics/courses/?paged=%#%");
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
