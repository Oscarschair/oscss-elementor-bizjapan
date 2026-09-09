<?php
/**
 * BizJapan SEO & Hong Kong (zh-HK) Localization Enhancement
 *
 * @package oscss-elementor-bizjapan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Force language attribute to zh-HK
 */
function bizjapan_language_attributes( $output ) {
	return 'dir="ltr" lang="zh-HK"';
}
add_filter( 'language_attributes', 'bizjapan_language_attributes', 999 );

/**
 * 2. SEO Title Optimization
 */
function bizjapan_filter_title( $title ) {
	if ( is_front_page() || is_home() ) {
		return '日本設立公司・商業代辦服務｜助香港企業一站式進軍日本市場｜OSCAR';
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'bizjapan_filter_title', 999 );
add_filter( 'aioseo_title', 'bizjapan_filter_title', 999 );

/**
 * 3. SEO Meta Description Optimization
 */
function bizjapan_filter_description( $description ) {
	if ( is_front_page() || is_home() ) {
		return '【香港企業專屬】專業代辦日本公司註冊、經營管理簽證、.jp域名申請及日本多言語網站製作。即使身在香港、非日本居民亦能順利在日設立「株式會社」。更可配合香港政府BUD專項基金及EMF資助，助您半價拓展日本業務。立即免費諮詢！';
	}
	return $description;
}
add_filter( 'aioseo_description', 'bizjapan_filter_description', 999 );

/**
 * 4. OGP & Twitter Card Optimization (zh_HK)
 */
function bizjapan_aioseo_og_locale( $locale ) {
	return 'zh_HK';
}
add_filter( 'aioseo_opengraph_locale', 'bizjapan_aioseo_og_locale', 999 );

function bizjapan_output_ogp_meta_tags() {
	if ( ! ( is_front_page() || is_home() ) ) {
		return;
	}
	$title = '日本設立公司・商業代辦服務｜助香港企業一站式進軍日本市場｜OSCAR';
	$desc  = '【香港企業專屬】專業代辦日本公司註冊、經營管理簽證、.jp域名申請及日本多言語網站製作。即使身在香港、非日本居民亦能順利在日設立「株式會社」。更可配合香港政府BUD專項基金及EMF資助。';
	$url   = home_url( '/' );
	$og_img = get_template_directory_uri() . '/assets/images/service-scheme-relation-zhhk.jpg';

	echo "\n<!-- BizJapan Standalone OpenGraph & Twitter Meta Tags -->\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:locale" content="zh_HK">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $og_img ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta name="twitter:image" content="' . esc_url( $og_img ) . '">' . "\n";
}
add_action( 'wp_head', 'bizjapan_output_ogp_meta_tags', 5 );

/**
 * 5. Structured Data (JSON-LD) for Services and FAQ
 */
function bizjapan_output_structured_data() {
	if ( ! ( is_front_page() || is_home() ) ) {
		return;
	}

	$schema = [
		'@context' => 'https://schema.org',
		'@graph'   => [
			[
				'@type'        => 'Service',
				'@id'          => home_url( '/#service-company-formation' ),
				'name'         => '代辦設立日本公司 (株式會社 / 合同會社)',
				'serviceType'  => 'Company Incorporation in Japan',
				'provider'     => [
					'@type' => 'Organization',
					'name'  => '商業代辦服務 by OSCAR',
					'url'   => home_url( '/' ),
				],
				'areaServed'   => [
					[
						'@type' => 'Country',
						'name'  => 'Hong Kong',
					],
					[
						'@type' => 'Country',
						'name'  => 'Japan',
					],
				],
				'description'  => '為身處香港之企業家代辦日本法務局公司註冊申請，非日本居民亦可順利合法成立日本法人。',
			],
			[
				'@type'        => 'Service',
				'@id'          => home_url( '/#service-jp-domain' ),
				'name'         => '代辦取得日本.jp專屬域名',
				'serviceType'  => 'JP Domain Registration Service',
				'provider'     => [
					'@type' => 'Organization',
					'name'  => '商業代辦服務 by OSCAR',
					'url'   => home_url( '/' ),
				],
				'description'  => '代辦註冊日本在地.jp專屬域名，提升品牌在日公信力與搜尋引擎權重。',
			],
			[
				'@type'        => 'Service',
				'@id'          => home_url( '/#service-web-development' ),
				'name'         => '建立日本本地化官方網站',
				'serviceType'  => 'Japanese Localization & Web Design',
				'provider'     => [
					'@type' => 'Organization',
					'name'  => '商業代辦服務 by OSCAR',
					'url'   => home_url( '/' ),
				],
				'description'  => '打造符合日本當地商業習慣與美學的高品質日語官網，傳遞品牌價值。',
			],
			[
				'@type'        => 'FAQPage',
				'@id'          => home_url( '/#faq' ),
				'mainEntity'   => [
					[
						'@type'          => 'Question',
						'name'           => '身在香港、沒有日本永住權或長期居留身份，可以在日本註冊公司嗎？',
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => '可以。根據日本法務省修例規定，即使全體代表董事皆為非日本居住者，外國投資者同樣可在日本合法登記註冊「株式會社」或「合同會社」。',
						],
					],
					[
						'@type'          => 'Question',
						'name'           => '設立日本公司時，沒有日本地址怎麼辦？',
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => '我們提供合規的日本登記地址及虛擬辦公室租賃方案，並可協助辦理商業登記所需之實體事務所地址手續。',
						],
					],
					[
						'@type'          => 'Question',
						'name'           => '香港人可以在日本買樓置業嗎？',
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => '可以。日本對外國人購置房地產並無身分限制，非居民亦可擁有永久產權土地與建築物。',
						],
					],
					[
						'@type'          => 'Question',
						'name'           => '如何透過設立公司申請日本「經營・管理」簽證？',
						'acceptedAnswer' => [
							'@type' => 'Answer',
							'text'  => '通常需具備在日本的實體營運場所、500萬日圓以上資本金（或聘請2名以上全職員工），並配合一份具體且可行的商業營銷計劃書。我們提供專業的行政書士合作網絡全力協助辦理。',
						],
					],
				],
			],
		],
	];

	echo "\n<!-- BizJapan Structured Data (JSON-LD) -->\n";
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "</script>\n";
}
add_action( 'wp_head', 'bizjapan_output_structured_data', 20 );

/**
 * 6. Mobile Floating Sticky CTA Bar (WhatsApp / Free Consultation)
 */
function bizjapan_mobile_sticky_cta() {
	if ( is_admin() ) {
		return;
	}
	?>
	<div id="bizjapan-mobile-cta" class="bizjapan-sticky-bar">
		<a href="https://api.whatsapp.com/send?text=你好，我想查詢日本設立公司及商業代辦服務" target="_blank" rel="noopener noreferrer" class="bizjapan-cta-btn bizjapan-cta-whatsapp">
			<span class="cta-icon">💬</span>
			<span class="cta-text">WhatsApp 查詢</span>
		</a>
		<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="bizjapan-cta-btn bizjapan-cta-contact">
			<span class="cta-icon">✉️</span>
			<span class="cta-text">免費諮詢</span>
		</a>
	</div>
	<?php
}
add_action( 'wp_footer', 'bizjapan_mobile_sticky_cta' );

/**
 * 7. Modern Image Auto-Replacement Filter for e-con-inner Content
 * Transparently replaces outdated legacy Firefly/Flow images with generated high-resolution 3D images.
 */
function bizjapan_modernize_outdated_images( $content ) {
	if ( is_admin() ) {
		return $content;
	}

	$theme_images_uri = get_template_directory_uri() . '/assets/images/';

	$replacements = [
		// 1. Service: Company Incorporation (formerly back of a man)
		'/https?:\/\/[^\s"\']+\/Firefly-Portrait-Photograph-in-blue-color-scheme-from-far-far-far-far-far-far-angle-Back-of-a-man-w[^\s"\']*\.jpg/i'
			=> $theme_images_uri . 'service-incorporation.jpg',

		// 2. Service: JP Domain (formerly wooden building blocks)
		'/https?:\/\/[^\s"\']+\/Firefly-Portrait-Photograph-in-blue-color-scheme-building-blocks-represent-word-\.jp[^\s"\']*\.jpg/i'
			=> $theme_images_uri . 'service-domain.jpg',

		// 3. Service: Web Design (formerly old laptop coding)
		'/https?:\/\/[^\s"\']+\/Firefly-Firefly-Portrait-Photograph-a-notebook-coding-to-bulid-a-website-with-blue-color-scheme[^\s"\']*\.jpg/i'
			=> $theme_images_uri . 'service-web-design.jpg',

		// 4. Service: Visa & Property (formerly cargo ship)
		'/https?:\/\/[^\s"\']+\/Firefly-Portrait-Photograph-departure-of-a-cargo-ship[^\s"\']*\.jpg/i'
			=> $theme_images_uri . 'service-visa-property.jpg',

		// 5. Overall Service Scheme (formerly flow-2.png: 3-party partnership scheme diagram)
		'/https?:\/\/[^\s"\']+\/flow-2[^\s"\']*\.png/i'
			=> $theme_images_uri . 'service-scheme-relation-zhhk.jpg',

		// 6. Company Launch 5-Step Workflow (formerly flow2.png: 5-step process flowchart)
		'/https?:\/\/[^\s"\']+\/flow2[^\s"\']*\.png/i'
			=> $theme_images_uri . 'service-workflow-flow-zhhk.jpg',
	];

	foreach ( $replacements as $pattern => $replacement ) {
		$content = preg_replace( $pattern, $replacement, $content );
	}

	return $content;
}
add_filter( 'the_content', 'bizjapan_modernize_outdated_images', 15 );


