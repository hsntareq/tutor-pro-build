<?php 
/**
 * Export Template
 */
use \TUTOR_REPORT\Analytics;

$analytics      = new Analytics;
$data           = $analytics->analytics_data();
$students       = $data['students'];
$earnings       = $data['earnings'];
$discounts      = $data['discounts'];
$refunds        = $data['refunds'];
$disabled       = true;
$disabled_class = 'btn-disabled';

if( count($students) || count($earnings) || count($discounts) || count($refunds) ) {
    $disabled       = false;
    $disabled_class = '';
}
?>
<div class="analytics-export-wrapper">
    <div class="content-image-wrapper">
        <div class="content">
            <h3>
                <?php _e( 'Detailed Report of Your Sales & Students', 'tutor-pro' ); ?>
            </h3>
            <p>
                <?php _e( 'Export to keep a copy of your analytics data.', 'tutor-pro' ); ?>
            </p>
            <div>
                <button type="button" id="download_analytics" class="<?php esc_attr_e( 'tutor-btn '.$disabled_class ); ?>" <?php esc_attr_e( $disabled ? 'disabled': '' ); ?>>
                    <i class="tutor-icon-download"></i> <?php _e( 'Download CSV' ); ?>
                </button>
            </div>
        </div>
        <div class="image" style="background-image: url(<?php echo esc_url( TUTOR_REPORT()->url.'assets/images/export-bg.svg')?>)">
           
        </div>
    </div>
</div>
