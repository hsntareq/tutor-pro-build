<?php
/**
 * Display Topics and Lesson lists for learn
 *
 * @since v.1.0.0
 * @author themeum
 * @url https://themeum.com
 *
 * @package TutorLMS/Templates
 * @version 1.4.3
 */

if ( ! defined( 'ABSPATH' ) )
	exit;
$course_id = sanitize_text_field( $_POST['course_id'] );
$post = get_post( $course_id );
$currentPost = $post;

?>
<style>
        i.tutor-done {
        background-color: #3E64DE !important;
        border: 1px solid #3E64DE !important;
        box-sizing: border-box;
    }
    i.tutor-icon-mark:not(.tutor-done) {
        background-color: #3E64DE !important; 
        border: 2px solid #C0C3CB !important;
        box-sizing: border-box;
    }
</style>
<div class="tutor-analytics-propress-popup">
    <div class="course-summary td-course-progress">
        <h4>
            <?php esc_html_e( $post->post_title ); ?>
        </h4>
        <div class="course-info">
            <div class="course-materials">
                <div class="info">
                    <label for="">
                        <?php _e( 'Lesson', 'tutor-pro' ); ?>
                    </label>
                    <span>
                        <?php esc_html_e( $_POST['completed_lesson'].'/'.$_POST['total_lesson'] ); ?>
                    </span>
                </div>
                <div class="info">
                    <label for="">
                        <?php _e( 'Assignment', 'tutor-pro' ); ?>
                    </label>
                    <span>
                        <?php esc_html_e( $_POST['completed_assignment'].'/'.$_POST['total_assignment'] ); ?>
                    </span>
                </div>
                <div class="info">
                    <label for="">
                        <?php _e( 'Quiz', 'tutor-pro' ); ?>
                    </label>
                    <span>
                        <?php esc_html_e( $_POST['completed_quiz'].'/'.$_POST['total_quiz'] ); ?>
                    </span>
                </div>
            </div>
            <div class="course-total-completed course-progress inline-flex-center">
                <div class="progress-bar" style="--progress-value:<?php esc_attr_e( $_POST['total_progress'].'%;'); ?>"> 
                    <div class="progress-value"></div> </div> 
                    <div class="color-text-primary progress-text text-medium-caption">
                        <?php esc_html_e( $_POST['total_progress'] ) ; ?>% <?php _e(' Complete', 'tutor')?>
                    </div> 
                </div> 
            </div>
        </div>
    </div>
    <div class="tutor-sidebar-tabs-content">

        <div id="tutor-lesson-sidebar-tab-content" class="tutor-lesson-sidebar-tab-item">
            <?php
            $topics = tutor_utils()->get_topics($course_id);
            $i = 0;
            if ($topics->have_posts()){
                while ($topics->have_posts()){ $topics->the_post();
                    $i++;
                    $topic_id = get_the_ID();
                    $topic_summery = get_the_content();
                    ?>

                    <div class="tutor-topics-in-single-lesson tutor-topics-<?php echo $topic_id; ?>">
                        <div class="tutor-topics-title <?php echo $topic_summery ? 'has-summery' : ''; ?>">
                            <h3>
                                <?php esc_html_e( "0.".$i); ?>
                                <?php esc_html_e( the_title() ); ?>
                            </h3>
                        </div>

                        <?php
                        if ($topic_summery){
                            ?>
                            <div class="tutor-topics-summery">
                                <?php echo wp_kses_post( $topic_summery ); ?>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="tutor-lessons-under-topic">
                            <?php
                            do_action('tutor/lesson_list/before/topic', $topic_id);

                            $lessons = tutor_utils()->get_course_contents_by_topic(get_the_ID(), -1);
                                
                            if ($lessons->have_posts()){

                                foreach( $lessons->posts as $post) {
                                
                                    if ($post->post_type === 'tutor_quiz') {
                                        $quiz = $post;
                                        ?>
                                        <div class="tutor-single-lesson-items quiz-single-item quiz-single-item-<?php echo $quiz->ID; ?> <?php echo ( $currentPost->ID === get_the_ID() ) ? 'active' : ''; ?>" data-quiz-id="<?php echo $quiz->ID; ?>">
                                            <a href="<?php echo get_permalink($quiz->ID); ?>" class="sidebar-single-quiz-a" data-quiz-id="<?php echo $quiz->ID; ?>">
                                                <i class="tutor-icon-doubt"></i>
                                                <span class="lesson_title"><?php echo $quiz->post_title; ?></span>
                                                <span class="tutor-lesson-right-icons">
                                                <?php
                                                do_action('tutor/lesson_list/right_icon_area', $post);

                                                $time_limit = tutor_utils()->get_quiz_option($quiz->ID, 'time_limit.time_value');
                                                if ($time_limit){
                                                    $time_type = tutor_utils()->get_quiz_option($quiz->ID, 'time_limit.time_type');
                                                    echo "<span class='quiz-time-limit'>{$time_limit} {$time_type}</span>";
                                                }
                                                ?>
                                                </span>
                                            </a>
                                        </div>
                                        <?php
                                    }elseif($post->post_type === 'tutor_assignments'){
                                        /**
                                         * Assignments
                                         * @since this block v.1.3.3
                                         */

                                        ?>
                                        <div class="tutor-single-lesson-items assignments-single-item assignment-single-item-<?php echo $post->ID; ?> <?php echo ( $currentPost->ID === get_the_ID() ) ? 'active' : ''; ?>"
                                                data-assignment-id="<?php echo $post->ID; ?>">
                                            <a href="<?php echo get_permalink($post->ID); ?>" class="sidebar-single-assignment-a" data-assignment-id="<?php echo $post->ID; ?>">
                                                <i class="tutor-icon-clipboard"></i>
                                                <span class="lesson_title"> <?php echo $post->post_title; ?> </span>
                                                <span class="tutor-lesson-right-icons">
                                                    <?php do_action('tutor/lesson_list/right_icon_area', $post); ?>
                                                </span>
                                            </a>
                                        </div>
                                        <?php

                                    }elseif($post->post_type === 'tutor_zoom_meeting'){
                                        /**
                                         * Zoom Meeting
                                         * @since this block v.1.7.1
                                         */

                                        ?>
                                        <div class="tutor-single-lesson-items zoom-meeting-single-item zoom-meeting-single-item-<?php echo $post->ID; ?> <?php echo ( $currentPost->ID === get_the_ID() ) ? 'active' : ''; ?>"
                                                data-assignment-id="<?php echo $post->ID; ?>">
                                            <a href="<?php echo get_permalink($post->ID); ?>" class="sidebar-single-zoom-meeting-a">
                                                <i class="tutor-icon-zoom"></i>
                                                <span class="lesson_title"> <?php echo $post->post_title; ?> </span>
                                                <span class="tutor-lesson-right-icons">
                                                    <?php do_action('tutor/lesson_list/right_icon_area', $post); ?>
                                                </span>
                                            </a>
                                        </div>
                                        <?php

                                    } else {

                                        /**
                                         * Lesson
                                         */
                                        $video = tutor_utils()->get_video_info( $post->ID );

                                        $play_time = false;
                                        if ( $video ) {
                                            $play_time = $video->playtime;
                                        }
                                        $is_completed_lesson = tutor_utils()->is_completed_lesson( $post->ID);
                                        ?>

                                        <div class="tutor-single-lesson-items <?php echo ( $post->ID ) ? 'active' : ''; ?>">
                                            <a href="<?php the_permalink(); ?>" class="tutor-single-lesson-a" data-lesson-id="<?php $post->ID; ?>">

                                                <?php
                                                $tutor_lesson_type_icon = $play_time ? 'youtube' : 'document';
                                                echo "<i class='tutor-icon-$tutor_lesson_type_icon'></i>";
                                                ?>
                                                <span class="lesson_title"><?php esc_html_e( $post->post_title ) ;?></span>
                                                <span class="tutor-lesson-right-icons">
                                                    <?php
                                                    do_action('tutor/lesson_list/right_icon_area', $post);
                                                    if ( $play_time ) {
                                                        echo "<i class='tutor-play-duration'>".tutor_utils()->get_optimized_duration($play_time)."</i>";
                                                    }
                                                    $lesson_complete_icon = $is_completed_lesson ? 'tutor-icon-mark tutor-done' : '';
                                                    echo "<i class='tutor-lesson-complete $lesson_complete_icon'></i>";
                                                    ?>
                                                </span>
                                            </a>
                                        </div>

                                        <?php
                                    }
                                }
                                $lessons->reset_postdata();
                            }
                            ?>

                            <?php do_action('tutor/lesson_list/after/topic', $topic_id); ?>
                        </div>
                    </div>

                    <?php
                }
                $topics->reset_postdata();
                wp_reset_postdata();
            }
            ?>
        </div>

    </div>

</div>
