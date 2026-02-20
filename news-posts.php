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

$className = 'news-posts';
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

$block_id = !empty($layout_options['section_id']) ? $layout_options['section_id'] : 'section-' . $block['id'];

?>



<section class="dr-full-width <?php echo esc_attr($className); ?> <? echo esc_attr($block_id); ?>" style="background-color:<?php echo ($color_settings['section_background']); ?>;">
    <div class="news-posts__inner">
        <div class="sidebar-container">
            <div class="leafs-container">
                <div class="primary-color-leaf" style="background-color:<?php echo ($color_settings['primary_accent']); ?>"></div>
                <div class="secondary-color-leaf" style="background-color:<?php echo ($color_settings['secondary_accent']); ?>"></div>
            </div>
            <div class="sidebar-text-container">
                <p><?php echo $sidebar_text; ?></p>
            </div>
        </div>
        <div class="news-posts___content">
            <h2 class="news-posts__title"><?php echo $section_title; ?></h2>
            <?php
            // Categories are the IDs of the selected categories
            if (!empty($post_categories)) {
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 6,
                    'category__in' => $post_categories,
                );
            } else {
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 6,
                );
            }
            $news_query = new WP_Query($args);
            $posts_shown = 0;
            if ($news_query->have_posts()) :
                echo '<div class="news-posts__grid "' . $categories . '>';
                while ($news_query->have_posts()) : $news_query->the_post();
                    $postCLass = 'news-posts__item';
                    if ($posts_shown >= 2) {
                        $postCLass = 'news-posts__item--hidden';
                        // Get the 
                    }
                    ?>
                    <div class="<?php echo esc_attr($postCLass); ?>">
                        <div class="news-posts__item-image" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>');"></div>
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
                <!-- <a class="secondary-button" id="load-more-posts">Load More Posts</a> -->
                 <a class="secondary-button" id="load-more-posts">Load More <? echo esc_html($categories[0]->name); ?> </a>
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