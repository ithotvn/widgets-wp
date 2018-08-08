<?php
/**
 * @package hbinstrap
 */

if (!function_exists('hbinstrap_latest_posts_by_cat_widget_demo')) :

function hbinstrap_latest_posts_by_cat_widget_demo($instance)
{
    $numof_display = isset($instance['numof_display']) ? (int) $instance['numof_display'] : 4;

    $catid = isset($instance['catid']) ? (int) $instance['catid'] : 0;

    if (!$catid) {
        return '';
    }

    $args = array(
        'numberposts' => $numof_display,
        'category' => $catid,
        'post_status' => 'publish',
    );

    $recent_posts = wp_get_recent_posts($args);

    foreach ($recent_posts as $i => $post) {
        setup_postdata($post);

        echo '<div class="col-lg-6 media">
            <a class="d-flex mr-3" href="' . get_permalink($post["ID"]) . '" title="' . $post["post_title"].'">
                ' . hbinstrap_get_the_post_thumbnail($post["ID"], 'small', true) . '
            </a>
            <div class="media-body">
                <h4 class="title_entry">
                    <a href="' . get_permalink($post["ID"]) . '" title="' . $post["post_title"] . '">' . $post["post_title"] . '</a>
                </h4>
                '.hbinstrap_get_posted_on().'
            </div>
        </div>';
    }
    wp_reset_postdata();
    wp_reset_query();
}

endif;

class hbinstrap_latest_posts_by_cat_class_demo extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'hbinstrap_latest_posts_by_cat_demo',
            __('HBINSTRAP: DEMO WIDGET ITHOT.VN', 'hbinstrap'),
            array( 'description' => __('Get latest posts by category.', 'hbinstrap'))
        );
    }

    public function widget($args, $instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : '';

        echo '
            <div id="hbinstrapLatestPostByCat">
        ';

        echo $args['before_widget'];

        if (! empty($title)) {
            echo $args['before_title'] . '<span class="text">'. $title . '</span>' . $args['after_title'];
        }

        echo hbinstrap_latest_posts_by_cat_widget_demo($instance);

        echo $args['after_widget'];

        echo '</div>';
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : '';

        $catid = isset($instance['catid']) ? $instance['catid'] : '';

        $numof_display = isset($instance['numof_display']) ? (int) $instance['numof_display'] : 5;
    ?>

        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>">
                <?php _e('Title (optional):', 'hbinstrap'); ?>
            </label>
            <input class="widefat"
                id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>"
                type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
        <?php

            $categories = get_categories();

        ?>
            <select name="<?php echo $this->get_field_name('catid'); ?>">

                <option value="0">No display</option>
                <?php
                    foreach ($categories as $category) :
                ?>

                <option
                    <?php if ($category->cat_ID == esc_attr($catid)) echo "selected" ; ?>
                    value="<?php echo $category->cat_ID; ?>">
                    <?php echo $category->name; ?>
                </option>

                <?php endforeach; ?>

            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_name('numof_display'); ?>">
                <?php _e('Num of display (1->xxx):', 'hbinstrap'); ?>
            </label>
            <input class="widefat"
                id="<?php echo $this->get_field_id('numof_display'); ?>"
                name="<?php echo $this->get_field_name('numof_display'); ?>"
                type="text" value="<?php echo esc_attr($numof_display); ?>" />
        </p>

    <?php
    }

    public function update($new_instance, $old_instance)
    {
        return array(
            'title' => (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '',
            'catid' => (!empty($new_instance['catid'])) ? strip_tags($new_instance['catid']) : 0,
            'numof_display' => (!empty($new_instance['numof_display'])) ? (int) strip_tags($new_instance['numof_display']) : 4,
        );
    }
}

function register_hbinstrap_latest_posts_by_cat_class_demo()
{
    register_widget('hbinstrap_latest_posts_by_cat_class_demo');
}

add_action('widgets_init', 'register_hbinstrap_latest_posts_by_cat_class_demo');
