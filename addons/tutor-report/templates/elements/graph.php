<div class="tutor-analytics-graph">
    
    <?php if ( $data ): ?>
        <div class="tabs">
            <?php foreach( $data as $key => $value ): ?>
                <?php $active = $value['active']; ?>
                <div class="tab <?php esc_attr_e( $active ); ?>" data-toggle="<?php esc_attr_e( $value['data_attr']); ?>">
                    <p>
                        <?php esc_html_e( $value['tab_title']); ?>
                    </p>
                    <h4>
                        <?php if ( $value['price'] ): ?>
                            <?php echo  $value['tab_value'] ? wp_kses_post( tutor_utils()->tutor_price( $value['tab_value'] ) ) : '-' ; ?>
                        <?php else: ?>
                            <?php esc_html_e( $value['tab_value'] ? $value['tab_value'] : '-' ); ?>
                        <?php endif; ?>    
                    </h4>
                </div>
            <?php endforeach; ?>
        </div>
        <!--tab content -->
        <div class="chart-wrapper">
 
            <?php foreach( $data as $key => $value ): ?>

                <?php $active = $value['active']; ?>
                <div class="tab-content <?php esc_attr_e( $active ); ?>" id="<?php esc_attr_e( $value['data_attr']); ?>">
                    <h5 class="chart-title">
                        <?php esc_html_e( $value['content_title'] ) ;?>
                    </h5>
                    <canvas id="<?php esc_attr_e( $value['data_attr'].'_canvas' ); ?>"></canvas>
                </div>
            <?php endforeach; ?>
        </div>
        <!--tab content end -->

    <?php endif; ?>

</div>