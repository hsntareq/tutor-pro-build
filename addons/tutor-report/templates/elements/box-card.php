<?php if ( is_array( $data ) && count( $data ) ): ?>
    <div class="tutor-analytics-info-cards">
        <?php foreach( $data as $key => $value ): ?>
            <div class="tutor-dashboard-info-card">
                <p>
                    <span class="tutor-svg-icon tutor-round-icon">
                        <?php echo $value['icon']; ?>
                    </span>
                    <?php if ( $value['price'] ): ?>
                        <span class="tutor-dashboard-info-val">
                            <?php echo $value['title'] ? wp_kses_post(tutor_utils()->tutor_price( $value['title'] )) : '-'; ?>
                        </span>
                    <?php else: ?>
                        <span class="tutor-dashboard-info-val">
                            <?php echo $value['title'] ? esc_html($value['title']) : '-'; ?>
                        </span>
                    <?php endif; ?>    
                    <span>
                        <?php echo esc_html($value['sub_title']); ?> 
                    </span>
                </p>
            </div>
        <?php endforeach; ?>
    </div> 
<?php endif; ?>       