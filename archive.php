<?php
/**
 * The template for displaying archive pages (Category, Tag, Date)
 *
 * @package oscss-elementor-bizjapan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$archive_title = get_the_archive_title();
$archive_desc  = get_the_archive_description();
?>

<main id="content" class="bizjapan-news-archive-main">
	
	<!-- Archive Hero -->
	<section class="news-archive-hero">
		<div class="news-hero-container">
			<nav class="news-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">首頁</a>
				<span class="sep">/</span>
				<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">最新資訊與專題文章</a>
				<span class="sep">/</span>
				<span class="current"><?php echo esc_html( single_cat_title( '', false ) ?: single_tag_title( '', false ) ?: '專題分類' ); ?></span>
			</nav>
			<span class="news-hero-badge">ARTICLE ARCHIVE</span>
			<h1 class="news-hero-title"><?php echo esc_html( single_cat_title( '', false ) ?: single_tag_title( '', false ) ?: '分類文章' ); ?></h1>
			<?php if ( ! empty( $archive_desc ) ) : ?>
				<p class="news-hero-desc"><?php echo esc_html( wp_strip_all_tags( $archive_desc ) ); ?></p>
			<?php else : ?>
				<p class="news-hero-desc">探索與本專題相關之最新政策、商業策略與日本落地實務指引。</p>
			<?php endif; ?>
		</div>
	</section>

	<div class="news-archive-container">

		<!-- Return to All News Filter -->
		<div class="news-category-filters">
			<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="filter-tab">
				← 查看所有分類 (All Categories)
			</a>
		</div>

		<!-- News Grid -->
		<?php if ( have_posts() ) : ?>
			<div class="bizjapan-news-grid">
				<?php while ( have_posts() ) : the_post(); ?>
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
			<div class="news-pagination-wrap">
				<?php
				echo paginate_links( [
					'prev_text' => '← 上一頁',
					'next_text' => '下一頁 →',
				] );
				?>
			</div>

		<?php else : ?>
			<div class="news-no-results">
				<p>目前該分類下尚無相關文章。</p>
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
