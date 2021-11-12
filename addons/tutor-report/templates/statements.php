<?php
/**
 * Statements template
 * 
 * @since 1.9.9
 */
use TUTOR_REPORT\Analytics;

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
$per_page   = 10;
$offset     = ($per_page * $paged) - $per_page;

$course_id    = isset( $_GET['course-id'] ) ? $_GET['course-id'] : '';
$date_filter  = isset( $_GET['date'] ) && $_GET['date'] != '' ? tutor_get_formated_date( 'Y-m-d', $_GET['date'] ) : '';

$statements = Analytics::get_statements_by_user( $user->ID, $offset, $per_page, $course_id, $date_filter); 
$courses    = tutor_utils()->get_courses_by_instructor();
?>
<div class="tutor-analytics-statements">
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

    <div class="statements-wrapper">
        <div class="tutor-table-responsive">
            <table class="tutor-table" style="--column-count:4">
                <thead>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Statement Info' , 'tutor-pro'); ?>
                        </span>
                    </th>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Earning(%)' , 'tutor-pro'); ?>
                        </span>
                    </th>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Commission(%)' , 'tutor-pro'); ?>
                        </span>
                    </th>
                    <th>
                        <span class="color-text-subsued text-regular-small">
                            <?php _e( 'Fees' , 'tutor-pro'); ?>
                        </span>
                    </th>
                </thead>
                <tbody>
                    <?php if ( count( $statements['statements'] ) ): ?>
                        <?php foreach( $statements['statements'] as $statement ): ?>
                            <?php 
                                $order      = wc_get_order( $statement->order_id ); 
                                if ( !$order ) {
                                    continue;
                                }    
                                $customer   = $order->get_user();

                            ?>
                            <tr>
                                <td width="40%">
                                    <div class="td-statement-info">
                                        <div class="meta-data-1 inline-flex-center">
                                            <span class="tutor-badge-label label-<?php esc_attr_e( $statement->order_status == 'completed' ? 'success' : $statement->order_status ); ?>">
                                                <?php esc_html_e( ucfirst( $statement->order_status ) ); ?></span>
                                            <span class="text-regular-small color-text-primary">
                                                <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ) ,$statement->created_at ) ); ?>
                                            </span>
                                        </div>
                                        <p class="meta-data-2 text-medium-body color-text-primary">
                                            <?php esc_html_e( $statement->course_title ); ?>
                                        </p>
                                        <div class="meta-data-3 inline-flex-center text-regular-small color-text-title">
                                            <span><?php esc_html_e( 'Order ID: #'.$statement->order_id );?></span>
                                            <div class="inline-flex-center">
                                                <span>
                                                    <?php esc_html_e( 'Purchaser: '.$customer->display_name == '' ? $customer->user_nicename : $customer->display_name);?>
                                                </span>
                                            </div>
                                        </div>
								    </div>
                                </td>
                                <td>
                                    <?php 
                                        $instructor_commission_type = $statement->commission_type === 'percent' ? '%' : ''; 
                                    ?>
                                    <span class="text-medium-caption color-text-primary">
									    <?php echo tutor_utils()->tutor_price($statement->course_price_total); ?> <br />
                                        <span class="text-regular-small color-text-hints">
                                            <?php esc_html_e( 'As per '.$statement->instructor_rate.$instructor_commission_type, 'tutor-pro' ); ?>
                                        </span>
								    </span>
                                </td>
                                <td>
                                    <?php 
                                         $admin_rate_type = $statement->commission_type === 'percent' ? '%' : '';
                                    ?>
                                    <span class="text-medium-caption color-text-primary">
                                        <?php echo tutor_utils()->tutor_price($statement->admin_amount); ?> <br />
                                        <span class="text-regular-small color-text-hints">
                                            <?php esc_html_e( 'As per '.$statement->admin_rate.$admin_rate_type, 'tutor-pro' ); ?>
                                        </span>
								    </span>
                                </td>
                                <td>
                                    <?php 
                                        $service_rate_type = $statement->deduct_fees_type === 'percent' ? '%' : '';
                                    ?>
                                    <span class="text-medium-caption color-text-primary">
                                        <?php echo tutor_utils()->tutor_price($statement->deduct_fees_amount); ?> <br />
                                        <span class="text-regular-small color-text-hints">
                                            <?php esc_html_e( $statement->deduct_fees_name, 'tutor-pro' ); ?>
                                        </span>
								    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">
                                <?php _e( 'No records found', 'tutor-pro' ); ?>
                            </td>
                        </tr>
                    <?php endif; ?>    
                </tbody>
            </table>
        </div>
    </div>
    <?php if ( $statements['total_statements'] ): ?>
        <div class="tutor-pagination-wrapper">
                <div class="page-info">
                    <?php 
                        $total_page = ceil( $statements['total_statements'] / $per_page );
                        _e( "Page $paged of $total_page", 'tutor-pro'); 
                    ?>
                </div>
                <div class="pagination">
                    <?php
                $big = 999999;
                
                $url = esc_url( tutor_utils()->get_tutor_dashboard_page_permalink()."analytics/statements/?paged=%#%");
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