<?php
/**
 * Template Name: News Archive Page
 * Template for displaying the News & Insights listing (/news/)
 *
 * @package oscss-elementor-bizjapan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Handle category filter query param
$current_cat = isset( $_GET['cat_filter'] ) ? sanitize_text_field( $_GET['cat_filter'] ) : '';
$paged       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$query_args = [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
];

if ( ! empty( $current_cat ) ) {
	$query_args['category_name'] = $current_cat;
}

$news_query = new WP_Query( $query_args );

// Fetch all available post categories
$all_categories = get_categories( [
	'taxonomy'   => 'category',
	'hide_empty' => true,
] );
?>

<main id="content" class="bizjapan-news-archive-main">
	
	<!-- Hero Section -->
	<section class="news-archive-hero">
		<div class="news-hero-container">
			<nav class="news-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">首頁</a>
				<span class="sep">/</span>
				<span class="current">最新資訊與專題文章</span>
			</nav>
			<span class="news-hero-badge">NEWS & INSIGHTS</span>
			<h1 class="news-hero-title">最新資訊與專題文章</h1>
			<p class="news-hero-desc">
				掌握香港特別行政區與日本經貿最新動向、特區政府資助基金（BUD / EMF）實務攻略及日本法人設立合規指南。
			</p>
		</div>
	</section>

	<!-- Main Archive Content -->
	<div class="news-archive-container">

		<!-- Category Filter Tabs -->
		<div class="news-category-filters">
			<a href="<?php echo esc_url( get_permalink() ); ?>" class="filter-tab <?php echo empty( $current_cat ) ? 'active' : ''; ?>">
				全部最新 (All)
			</a>
			<?php foreach ( $all_categories as $cat ) : ?>
				<?php $is_active = ( $current_cat === $cat->slug ); ?>
				<a href="<?php echo esc_url( add_query_arg( 'cat_filter', $cat->slug, get_permalink() ) ); ?>" class="filter-tab <?php echo $is_active ? 'active' : ''; ?>">
					<?php echo esc_html( $cat->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>

		<!-- News Grid -->
		<?php if ( $news_query->have_posts() ) : ?>
			<div class="bizjapan-news-grid">
				<?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
					<?php
					$categories = get_the_category();
					$cat_name   = ! empty( $categories ) ? $categories[0]->name : '最新動向';
					$date_str   = get_the_date( 'Y年m月d日' );
					?>
					<article class="bizjapan-news-card">
						<div class="news-card-header">
							<span class="news-cat-badge"><?php echo esc_html( $cat_name ); ?></span>
							<time class="news-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( $date_str ); ?>
							</time>
						</div>
						<h2 class="news-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="news-excerpt">
							<?php echo wp_trim_words( get_the_excerpt(), 45, '...' ); ?>
						</div>
						<div class="news-footer">
							<a href="<?php the_permalink(); ?>" class="news-read-more">
								<span>閱讀完整文章 (Read More)</span>
								<span class="arrow">➔</span>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<!-- Pagination -->
			<?php if ( $news_query->max_num_pages > 1 ) : ?>
				<div class="news-pagination-wrap">
					<?php
					echo paginate_links( [
						'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						'format'    => '?paged=%#%',
						'current'   => max( 1, get_query_var( 'paged' ) ),
						'total'     => $news_query->max_num_pages,
						'prev_text' => '← 上一頁',
						'next_text' => '下一頁 →',
					] );
					?>
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		<?php else : ?>
			<div class="news-no-results">
				<p>目前該分類下尚無相關文章，請查看其他分類或點擊上方「全部最新」。</p>
			</div>
		<?php endif; ?>

		<!-- Conversion Banner -->
		<div class="bizjapan-article-cta-box">
			<div class="article-cta-badge">日本商業落地專屬支援</div>
			<h3 class="article-cta-title">準備拓展日本市場或申請政府資助？</h3>
			<p class="article-cta-desc">
				商業代辦服務 by OSCAR 團隊為您提供日本公司註冊、.jp域名、日語官網、簽證及 BUD / EMF 基金申請報價一站式支援服務。
			</p>
			<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="article-cta-btn">
				<span>免費商業諮詢 (Online Inquiry)</span>
				<span class="cta-arrow">➔</span>
			</a>
		</div>

	</div>
</main>

<?php
get_footer();
