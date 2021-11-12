<h4>
    <?php _e( 'Earnings Graph', 'tutor-pro' ); ?>
</h4>
<div class="tutor-analytics-filter-tabs">
    <?php 
        $active     = isset( $_GET['period'] ) ? sanitize_text_field( $_GET['period'] ) : '';
        $start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : '';
        $end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';
    ?>

    <?php if( count( $data['filter_period'] ) ): ?>

        <div class="periods-filter">
            <?php foreach( $data['filter_period'] as $key => $value ): ?>
                <?php 
                    $active_class = $active === $value['type'] ? 'active' : '';    
                ?>
                <a href="<?php echo $value['url']; ?>" class="<?php esc_attr_e($value['class'].' '.$active_class); ?>">
                    <?php esc_html_e( $value['title'] ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php if ( $data['filter_calendar'] ): ?>
        <form action="" type="get">
            <?php if ( isset($_GET['course_id']) && '' !== $_GET['course_id'] ): ?>
                <input type="hidden" name="course_id" value="<?php esc_html_e( $_GET['course_id']); ?>">
            <?php endif; ?>    
            <div class="calendar-filter">
                <div class="tutor-analytics-form-group">
                    <input type="text" name="start_date" class="tutor_date_picker tutor-analytics-date-range" autocomplete="off" placeholder="<?php echo get_option( 'date_format' ); ?>"  value="<?php esc_attr_e( $start_date ); ?>" required>
                    <i class="tutor-icon-calendar"></i>
                </div>
                <span>-</span>
                <div class="tutor-analytics-form-group">
                    <input type="text" name="end_date" class="tutor_date_picker tutor-analytics-date-range" autocomplete="off" placeholder="<?php echo get_option( 'date_format' ); ?>" value="<?php esc_attr_e( $end_date ); ?>" required>
                    <i class="tutor-icon-calendar"></i>
                </div>   
                <button type="submit" class="tutor-btn bordered-btn">
                    <?php _e( 'Apply', 'tutor-pro' ); ?>
                </button>
            </div>
        </form>
    <?php endif; ?>
    
</div>