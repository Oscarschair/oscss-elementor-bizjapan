<?php
/**
 * The template for displaying all single posts
 *
 * @package oscss-elementor-bizjapan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$categories = get_the_category();
	$cat_name   = ! empty( $categories ) ? $categories[0]->name : '最新動向';
	$date_str   = get_the_date( 'Y年m月d日' );
	?>

<main id="content" class="bizjapan-single-main">
	<div class="bizjapan-single-container">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'bizjapan-single-article' ); ?>>
			
			<!-- Breadcrumb Navigation -->
			<nav class="bizjapan-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bc-link">首頁</a>
				<span class="bc-sep">/</span>
				<span class="bc-cat"><?php echo esc_html( $cat_name ); ?></span>
				<span class="bc-sep">/</span>
				<span class="bc-current"><?php echo esc_html( wp_trim_words( get_the_title(), 10, '...' ) ); ?></span>
			</nav>

			<!-- Article Header -->
			<header class="bizjapan-article-header">
				<div class="article-meta-bar">
					<span class="article-cat-badge"><?php echo esc_html( $cat_name ); ?></span>
					<time class="article-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<span class="meta-icon">📅</span> <?php echo esc_html( $date_str ); ?>
					</time>
					<span class="article-author">
						<span class="meta-icon">✍️</span> 商業代辦服務 by OSCAR
					</span>
				</div>
				<h1 class="bizjapan-single-title"><?php the_title(); ?></h1>
			</header>

			<!-- Article Body Content -->
			<div class="bizjapan-article-content">
				<?php the_content(); ?>
			</div>

			<!-- Article Footer: Tags & Return Link -->
			<footer class="bizjapan-article-footer">
				<?php
				$tags = get_the_tags();
				if ( ! empty( $tags ) ) :
					?>
					<div class="article-tags-wrap">
						<span class="tags-label">相關標籤：</span>
						<div class="tags-list">
							<?php foreach ( $tags as $tag ) : ?>
								<span class="tag-pill">#<?php echo esc_html( $tag->name ); ?></span>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="article-nav-bottom">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-return-home">
						<span class="arrow">←</span> 返回官方首頁 (Back to Home)
					</a>
				</div>
			</footer>

		</article>
	</div>
</main>

<?php
endwhile;

get_footer();
