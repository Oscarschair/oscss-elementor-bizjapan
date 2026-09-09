<?php
/**
 * BizJapan News & Insights Posts Module
 * Powers the "其他資訊" section with dynamic WordPress Posts, Category Badges & Conversion CTA.
 *
 * @package oscss-elementor-bizjapan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Render Dynamic News Cards HTML
 */
function bizjapan_render_news_section( $limit = 3 ) {
	$args = [
		'post_type'      => 'post',
		'posts_per_page' => $limit,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	];

	$query = new WP_Query( $args );

	ob_start();
	?>
	<div class="bizjapan-news-container">
		<div class="bizjapan-news-grid">
			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php
					$categories = get_the_category();
					$cat_name   = ! empty( $categories ) ? $categories[0]->name : '最新資訊';
					$date_str   = get_the_date( 'Y年m月d日' );
					?>
					<article class="bizjapan-news-card">
						<div class="news-card-header">
							<span class="news-cat-badge"><?php echo esc_html( $cat_name ); ?></span>
							<time class="news-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( $date_str ); ?></time>
						</div>
						<h3 class="news-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="news-excerpt">
							<?php echo wp_trim_words( get_the_excerpt(), 45, '...' ); ?>
						</div>
						<div class="news-footer">
							<a href="<?php the_permalink(); ?>" class="news-read-more">
								<span>閱讀更多 (Read More)</span>
								<span class="arrow">➔</span>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<p class="bizjapan-no-news">目前尚無最新文章。</p>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'bizjapan_news_list', 'bizjapan_render_news_section' );

/**
 * 2. Replace static Testimonial block in "其他資訊" on front page with dynamic News Cards
 */
function bizjapan_replace_other_info_with_news( $content ) {
	if ( is_front_page() || is_home() ) {
		// Target the entire container and sibling widgets of the old testimonials (3e87c6c2 through 7ff56a9e)
		$pattern = '/<div[^>]*class="[^"]*elementor-element-3e87c6c2[^"]*"[^>]*>.*?<div[^>]*class="[^"]*elementor-element-7ff56a9e[^"]*"[^>]*>.*?<\/div>\s*<\/div>\s*<\/div>/is';
		if ( preg_match( $pattern, $content ) ) {
			$news_html = bizjapan_render_news_section( 3 );
			// Wrap in clean elementor container
			$replacement = '<div class="elementor-element elementor-element-3e87c6c2 e-con-full e-flex e-con e-child" data-id="3e87c6c2" data-element_type="container">' . $news_html . '</div>';
			$content = preg_replace( $pattern, $replacement, $content );
		}
	}
	return $content;
}
add_filter( 'the_content', 'bizjapan_replace_other_info_with_news', 25 );
add_filter( 'elementor/frontend/the_content', 'bizjapan_replace_other_info_with_news', 25 );



/**
 * 3. Append Conversion CTA Box at the bottom of Single Post articles
 */
function bizjapan_append_single_post_cta( $content ) {
	if ( is_singular( 'post' ) && in_the_loop() && is_main_query() ) {
		$cta_html = '
		<div class="bizjapan-article-cta-box">
			<div class="article-cta-badge">日本商業落地專屬支援</div>
			<h3 class="article-cta-title">準備拓展日本市場或申請政府資助？</h3>
			<p class="article-cta-desc">商業代辦服務 by OSCAR 團隊為您提供日本公司註冊、.jp域名、日語官網、簽證及 BUD / EMF 基金申請報價一站式支援服務。</p>
			<a href="' . esc_url( home_url( '/contact-us/' ) ) . '" class="article-cta-btn">
				<span>免費商業諮詢 (Online Inquiry)</span>
				<span class="cta-arrow">➔</span>
			</a>
		</div>';
		$content .= $cta_html;
	}
	return $content;
}
add_filter( 'the_content', 'bizjapan_append_single_post_cta', 90 );
