<?php
/**
 * News Posts Section Template
 * 
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 */

wp_enqueue_style('vertical-sidebar-styles', get_template_directory_uri() . '/css/vertical-sidebar.css', array(), '1.0.0');

$sidebar_text = get_field('sidebar_text');
$section_title = get_field('section_title');
$layout_options = get_field('layout_options');
$color_settings = get_field('color_settings');
$sidebar_background_value = isset($color_settings['sidebar_background']) ? $color_settings['sidebar_background'] : '';
$small_leaf_color = (strpos($color_settings['primary_accent'], '#') === 0)
    ? $color_settings['primary_accent']
    : 'var(--' . $color_settings['primary_accent'] . ')';

$className = 'news-posts';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}
if (!empty($layout_options['additional_classes'])) {
    $className .= ' ' . $layout_options['additional_classes'];
}
if ($layout_options['enable_on_this_page']) {
    $className .= ' has-on-this-page-nav';
}
if ($layout_options['vertical_bar_visible']) {
    $className .= ' has-vertical-bar';
}


$post_categories = get_field('post_categories');
$posts_count_raw = get_field('#_of_posts');
$posts_per_page = 6;

if (is_string($posts_count_raw)) {
    $posts_count_raw = trim($posts_count_raw);
}

if (is_numeric($posts_count_raw)) {
    $posts_count_value = (int) $posts_count_raw;
    $posts_per_page = ($posts_count_value <= 0) ? -1 : $posts_count_value;
} elseif (is_string($posts_count_raw) && strtolower($posts_count_raw) === 'all') {
    $posts_per_page = -1;
}

$section_id = !empty($layout_options['section_id']) ? $layout_options['section_id'] : 'section-' . $block['id'];
if (!empty($block['anchor'])) {
    $section_id = $block['anchor'];
}

?>



<section id="<?php echo esc_attr($section_id); ?>" class="dr-full-width <?php echo esc_attr($className); ?> <?php echo esc_attr($section_id); ?>" style="background-color:<?php echo ($color_settings['section_background']); ?>;">
    <div class="news-posts__inner">
        <div class="sidebar-container" style="background-color: var(--<?php echo esc_attr($sidebar_background_value); ?>);">
            <!-- Debug sidebar background value: <?php echo esc_html($sidebar_background_value); ?> -->
            <div class="leafs-container">
                <div class="primary-color-leaf" style="background-color:<?php echo ($color_settings['primary_accent']); ?>"></div>
                <div class="secondary-color-leaf" style="background-color:<?php echo ($color_settings['secondary_accent']); ?>"></div>
            </div>
            <div class="sidebar-text-container">
                <p><?php echo $sidebar_text; ?></p>
            </div>
        </div>
        <div class="news-posts___content" style="--small-leaf-color: <?php echo esc_attr($small_leaf_color); ?>;">
            <h2 class="news-posts__title"><?php echo $section_title; ?></h2>
            <?php
            // Categories are the IDs of the selected categories
            if (!empty($post_categories)) {
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => $posts_per_page,
                    'category__in' => $post_categories,
                );
            } else {
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => $posts_per_page,
                );
            }
            $news_query = new WP_Query($args);
            $posts_shown = 0;
            if ($news_query->have_posts()) :
                echo '<div class="news-posts__grid">';
                while ($news_query->have_posts()) : $news_query->the_post();
                    $postCLass = 'news-posts__item';
                    $post_id = get_the_ID();
                    $image_position_x = get_post_meta($post_id, 'post_image_position_x', true);
                    $image_position_y = get_post_meta($post_id, 'post_image_position_y', true);

                    if ($image_position_x === '' && function_exists('get_field')) {
                        $image_position_x = get_field('post_image_position_x', $post_id, false);
                    }
                    if ($image_position_y === '' && function_exists('get_field')) {
                        $image_position_y = get_field('post_image_position_y', $post_id, false);
                    }

                    $image_position_x = is_numeric($image_position_x) ? (int) $image_position_x : 50;
                    $image_position_y = is_numeric($image_position_y) ? (int) $image_position_y : 50;
                    $image_position_x = max(0, min(100, $image_position_x));
                    $image_position_y = max(0, min(100, $image_position_y));
                    $image_style = sprintf('object-fit: cover; object-position: %d%% %d%%;', $image_position_x, $image_position_y);
                    if ($posts_shown >= 2) {
                        $postCLass = 'news-posts__item--hidden';
                        // Get the 
                    }
                    ?>
                    <div class="<?php echo esc_attr($postCLass); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php echo get_the_post_thumbnail(get_the_ID(), 'large', array(
                                'class' => 'news-posts__item-image',
                                'style' => $image_style,
                                'loading' => 'lazy',
                            )); ?>
                        <?php else : ?>
                            <div class="news-posts__item-image news-posts__item-image--empty" aria-hidden="true"></div>
                        <?php endif; ?>
                        <div class="news-posts__item-content">
                            <div class="news-posts__details">
                                <p class="news-posts__category">
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo esc_html($categories[0]->name);
                                    }
                                    ?>  •  <?php echo get_the_date(); ?></p>
                            </div>
                            <p class="news-posts__item-title"><?php echo get_the_title(); ?></p>
                            <a href="<?php echo get_permalink(); ?>" class="news-posts__read-more primary-button">Continiue Reading</a>
                        </div>
                    </div>
                    <?php
                    $posts_shown++;
                endwhile;
                echo '</div>';
                wp_reset_postdata();
            else :
                echo '<p>No posts found.</p>';
            endif;

            if ($posts_shown > 2) :
            ?>
            
            <div class="load-more-container">
                 <a class="secondary-button" id="load-more-posts">Load More <?php echo !empty($categories) ? esc_html($categories[0]->name) : 'Posts'; ?> </a>
            </div>
            <?php
            endif;
            ?>
        </div>
    </div>
</section>

<script>
    // document.addEventListener('DOMContentLoaded', () => {
    //     document.querySelectorAll('.news-posts').forEach(section => {
    //         const loadMoreBtn = section.querySelector('#load-more-posts');
    //         let postsToShow = 2;
    //         if (loadMoreBtn) {
    //             loadMoreBtn.addEventListener('click', () => {
    //                 let shown = 0;
    //                 // Show the next 2 hidden posts
    //                 const hiddenPosts = section.querySelectorAll('.news-posts__item--hidden');
    //                 while(shown < postsToShow) {
    //                     hiddenPosts[0].classList.remove('news-posts__item--hidden');
    //                     hiddenPosts[0].classList.add('news-posts__item');
    //                     shown++;
    //                 }
    //                 // If no more hidden posts, hide the load more button
    //                 if (section.querySelectorAll('.news-posts__item--hidden').length === 0) {
    //                     loadMoreBtn.style.display = 'none';
    //                 }
    //             });
    //         }
    //     });
    // })
</script>
