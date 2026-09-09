<?php
/**
 * BizJapan Custom Contact Form & Email Notification System
 * Sends inquiries directly to contact@oscarchair.jp with Primary Blue Branding
 *
 * @package oscss-elementor-bizjapan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const BIZJAPAN_ADMIN_EMAIL = 'contact@oscarchair.jp';

/**
 * 1. Contact Form Submission Handler
 */
function bizjapan_process_contact_form() {
	if ( ! isset( $_POST['bizjapan_contact_action'] ) || 'submit' !== $_POST['bizjapan_contact_action'] ) {
		return null;
	}

	// Verify Nonce
	if ( ! isset( $_POST['bizjapan_nonce'] ) || ! wp_verify_nonce( $_POST['bizjapan_nonce'], 'bizjapan_contact_form_nonce' ) ) {
		return [ 'status' => 'error', 'message' => '安全驗證失敗，請重新整理頁面後再試一次。' ];
	}

	// Spam check (Honeypot)
	if ( ! empty( $_POST['bizjapan_honeypot'] ) ) {
		// Silent reject for bots
		return [ 'status' => 'success', 'message' => '感謝閣下的查詢，我們已收到您的資料！' ];
	}

	// Sanitize Inputs
	$name     = sanitize_text_field( $_POST['contact_name'] ?? '' );
	$company  = sanitize_text_field( $_POST['contact_company'] ?? '' );
	$email    = sanitize_email( $_POST['contact_email'] ?? '' );
	$phone    = sanitize_text_field( $_POST['contact_phone'] ?? '' );
	$service  = sanitize_text_field( $_POST['contact_service'] ?? '' );
	$message  = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

	// Validation
	if ( empty( $name ) || empty( $email ) || ! is_email( $email ) || empty( $phone ) || empty( $service ) || empty( $message ) ) {
		return [ 'status' => 'error', 'message' => '請完整填寫所有必填欄位（*），並確認電郵地址格式正確。' ];
	}

	// Prepare Admin Email
	$to          = BIZJAPAN_ADMIN_EMAIL;
	$subject     = sprintf( '【商業代辦服務 by OSCAR】網站收到新的商業諮詢表格（%s 様）', $name );
	$date_time   = current_time( 'Y-m-d H:i:s' );
	
	$body  = "==================================================\n";
	$body .= "【商業代辦服務 by OSCAR】網站收到新的客戶諮詢登記\n";
	$body .= "==================================================\n\n";
	$body .= "■ 諮詢時間: " . $date_time . "\n";
	$body .= "■ 姓名 / 聯絡人: " . $name . "\n";
	$body .= "■ 公司名稱: " . ( ! empty( $company ) ? $company : '（未填寫）' ) . "\n";
	$body .= "■ 電郵地址: " . $email . "\n";
	$body .= "■ 聯絡電話: " . $phone . "\n";
	$body .= "■ 諮詢服務類別: " . $service . "\n\n";
	$body .= "--------------------------------------------------\n";
	$body .= "■ 諮詢內容與需求:\n";
	$body .= $message . "\n";
	$body .= "--------------------------------------------------\n\n";
	$body .= "※ 回覆此郵件即可直接聯繫客戶：" . $email . "\n";

	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		'From: 商業代辦服務 by OSCAR <no-reply@bizjapan.oscarchair.jp>',
		'Reply-To: ' . $name . ' <' . $email . '>',
	];

	// Send to Admin
	$sent_admin = wp_mail( $to, $subject, $body, $headers );

	// Prepare Auto-Reply to Customer
	$reply_subject = '【商業代辦服務 by OSCAR】已收到閣下的商業諮詢登記';
	$reply_body  = "親愛的 " . $name . " 閣下：\n\n";
	$reply_body .= "感謝閣下聯絡「商業代辦服務 by OSCAR」。我們已順利收到您的諮詢登記！\n\n";
	$reply_body .= "我們的專業顧問團隊將於 1-2 個工作天內檢閱您的需求，並透過電郵與閣下聯絡，提供專屬建議。\n\n";
	$reply_body .= "--------------------------------------------------\n";
	$reply_body .= "■ 登記服務類別: " . $service . "\n";
	$reply_body .= "■ 您的聯絡電話: " . $phone . "\n";
	$reply_body .= "--------------------------------------------------\n\n";
	$reply_body .= "商業代辦服務 by OSCAR 團隊 敬上\n";
	$reply_body .= "網站: https://bizjapan.oscarchair.jp/\n";

	$reply_headers = [
		'Content-Type: text/plain; charset=UTF-8',
		'From: 商業代辦服務 by OSCAR <contact@oscarchair.jp>',
	];

	// Send Auto-reply
	wp_mail( $email, $reply_subject, $reply_body, $reply_headers );

	return [
		'status'  => 'success',
		'message' => '感謝閣下的查詢！我們已收到您的資料，並已發送確認信件至您的電郵地址。專人將於 1-2 個工作天內與您聯絡。',
	];
}

/**
 * 3. Render Form HTML (Modern 2-Column Layout with Trust Cards & FAQ)
 */
function bizjapan_render_contact_form() {
	$result = bizjapan_process_contact_form();

	ob_start();
	?>
	<div class="bizjapan-contact-page-wrapper">
		<!-- Top Trust Badge Strip -->
		<div class="bizjapan-contact-badge-strip">
			<div class="badge-item">
				<span class="badge-icon">⚡</span>
				<span class="badge-text">1-2 個工作天內專人回覆</span>
			</div>
			<div class="badge-item">
				<span class="badge-icon">🔒</span>
				<span class="badge-text">嚴格遵守商業保密 (NDA)</span>
			</div>
			<div class="badge-item">
				<span class="badge-icon">🇭🇰</span>
				<span class="badge-text">廣東話 / 繁體中文全程溝通</span>
			</div>
			<div class="badge-item">
				<span class="badge-icon">💼</span>
				<span class="badge-text">支援香港 BUD / EMF 基金申請報價</span>
			</div>
		</div>

		<!-- Main 2-Column Layout -->
		<div class="bizjapan-contact-layout">
			<!-- Left Column: Trust Info, Channels & FAQ -->
			<aside class="bizjapan-contact-sidebar">
				<div class="sidebar-card feature-card">
					<div class="card-badge">WHY CHOOSE OSCAR</div>
					<h3 class="sidebar-title">日本在地一站式商業落地支援</h3>
					<p class="sidebar-desc">深耕東京與香港的專業顧問團隊，助您跨越語言與法規壁壘，順利在日本開啟全新商業版圖。</p>
					
					<ul class="sidebar-feature-list">
						<li>
							<div class="feat-icon">🏢</div>
							<div class="feat-body">
								<strong class="feat-title">日本法人設立・營運籌備</strong>
								<span class="feat-text">株式會社 / 合同會社登記、章程公證、資本金匯入及銀行開戶指導。</span>
							</div>
						</li>
						<li>
							<div class="feat-icon">🌐</div>
							<div class="feat-body">
								<strong class="feat-title">.jp 專屬域名 & 日語在地化官網</strong>
								<span class="feat-text">代辦日本在地 .jp 域名資質，打造符合日本商業習慣的日語企業官方網站。</span>
							</div>
						</li>
						<li>
							<div class="feat-icon">🛂</div>
							<div class="feat-body">
								<strong class="feat-title">長期「經營・管理」簽證諮詢</strong>
								<span class="feat-text">提供在留資格取得要件審查、事業計劃書策劃及日本物業辦公室租賃支援。</span>
							</div>
						</li>
					</ul>
				</div>

				<div class="sidebar-card channels-card">
					<h4 class="channels-title">直接聯絡與服務時間</h4>
					<div class="channel-row">
						<span class="channel-icon">📧</span>
						<div class="channel-info">
							<span class="channel-label">專屬諮詢電郵</span>
							<a href="mailto:contact@oscarchair.jp" class="channel-link">contact@oscarchair.jp</a>
						</div>
					</div>
					<div class="channel-row">
						<span class="channel-icon">🕒</span>
						<div class="channel-info">
							<span class="channel-label">服務時間</span>
							<span class="channel-value">星期一至五 09:30 - 18:30 (日本時間 GMT+9)</span>
						</div>
					</div>
					<div class="channel-row">
						<span class="channel-icon">🗣️</span>
						<div class="channel-info">
							<span class="channel-label">支援語言</span>
							<span class="channel-value">繁體中文 / 廣東話 / 日本語 / English</span>
						</div>
					</div>
				</div>

				<div class="sidebar-card faq-card">
					<h4 class="faq-title">常見諮詢問題 (FAQ)</h4>
					<div class="faq-item">
						<div class="faq-q">Q: 初步填表諮詢需要收取費用嗎？</div>
						<div class="faq-a">A: 完全免費。我們會先了解您的具體情況，並提供初步評估與方案建議。</div>
					</div>
					<div class="faq-item">
						<div class="faq-q">Q: 人尚未抵達日本，可以開始辦理手續嗎？</div>
						<div class="faq-a">A: 可以。公司設立、章程公證及前期準備均可跨境委託辦理。</div>
					</div>
				</div>
			</aside>

			<!-- Right Column: Interactive Contact Form -->
			<div class="bizjapan-contact-main">
				<div class="bizjapan-form-container">
					<div class="form-header-area">
						<span class="form-header-badge">ONLINE INQUIRY</span>
						<h3 class="form-main-heading">線上商業諮詢登記</h3>
						<p class="form-main-sub">請填妥以下資料，專屬商業顧問將於 1-2 個工作天內透過電郵與您聯絡。</p>
					</div>

					<?php if ( ! empty( $result ) ) : ?>
						<?php if ( 'success' === $result['status'] ) : ?>
							<div class="bizjapan-form-alert bizjapan-alert-success">
								<div class="alert-icon">✅</div>
								<div class="alert-text"><?php echo esc_html( $result['message'] ); ?></div>
							</div>
						<?php else : ?>
							<div class="bizjapan-form-alert bizjapan-alert-error">
								<div class="alert-icon">⚠️</div>
								<div class="alert-text"><?php echo esc_html( $result['message'] ); ?></div>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( empty( $result ) || 'error' === $result['status'] ) : ?>
						<form action="" method="post" class="bizjapan-contact-form" novalidate>
							<?php wp_nonce_field( 'bizjapan_contact_form_nonce', 'bizjapan_nonce' ); ?>
							<input type="hidden" name="bizjapan_contact_action" value="submit">
							
							<!-- Honeypot Field for anti-spam -->
							<div style="display:none !important;" aria-hidden="true">
								<label for="bizjapan_honeypot">Do not fill this</label>
								<input type="text" name="bizjapan_honeypot" id="bizjapan_honeypot" tabindex="-1" autocomplete="off">
							</div>

							<div class="form-grid">
								<!-- Name -->
								<div class="form-group">
									<label for="contact_name">姓名 / 聯絡人 <span class="required">*</span></label>
									<input type="text" name="contact_name" id="contact_name" required placeholder="例：陳大文 (Chan Tai Man)" value="<?php echo esc_attr( $_POST['contact_name'] ?? '' ); ?>">
								</div>

								<!-- Company -->
								<div class="form-group">
									<label for="contact_company">公司名稱 / 商號 <span class="optional">(選填)</span></label>
									<input type="text" name="contact_company" id="contact_company" placeholder="例：ABC Trading Limited" value="<?php echo esc_attr( $_POST['contact_company'] ?? '' ); ?>">
								</div>

								<!-- Email -->
								<div class="form-group">
									<label for="contact_email">電郵地址 <span class="required">*</span></label>
									<input type="email" name="contact_email" id="contact_email" required placeholder="例：name@example.com" value="<?php echo esc_attr( $_POST['contact_email'] ?? '' ); ?>">
								</div>

								<!-- Phone -->
								<div class="form-group">
									<label for="contact_phone">聯絡電話 <span class="required">*</span></label>
									<input type="tel" name="contact_phone" id="contact_phone" required placeholder="例：+852 9123 4567" value="<?php echo esc_attr( $_POST['contact_phone'] ?? '' ); ?>">
								</div>

								<!-- Service Category -->
								<div class="form-group full-width">
									<label for="contact_service">諮詢服務類別 <span class="required">*</span></label>
									<select name="contact_service" id="contact_service" required>
										<option value="">-- 請選擇您感興趣的服務 --</option>
										<option value="代辦設立日本公司 (株式會社/合同會社)">代辦設立日本公司 (株式會社 / 合同會社)</option>
										<option value="代辦取得日本.jp專屬域名">代辦取得日本 .jp 專屬域名</option>
										<option value="建立日本在地化官方網站 (日語)">建立日本在地化官方網站 (日語)</option>
										<option value="日本「經營・管理」簽證諮詢">日本「經營・管理」長期簽證諮詢</option>
										<option value="日本買樓置業及實體辦公室租賃">日本買樓置業及實體辦公室租賃</option>
										<option value="香港政府支援基金 (BUD專項基金 / EMF) 配合申請">香港政府支援基金 (BUD專項基金 / EMF) 配合申請</option>
										<option value="其他客製化商業支援">其他客製化商業支援</option>
									</select>
								</div>

								<!-- Message -->
								<div class="form-group full-width">
									<label for="contact_message">諮詢內容與需求備註 <span class="required">*</span></label>
									<textarea name="contact_message" id="contact_message" rows="5" required placeholder="請簡單說明您的業務現況、預計開展時間或任何疑問..."><?php echo esc_textarea( $_POST['contact_message'] ?? '' ); ?></textarea>
								</div>
							</div>

							<div class="form-submit-wrapper">
								<button type="submit" class="bizjapan-submit-btn">
									<span class="btn-text">確認送出諮詢表格 (Submit)</span>
									<span class="btn-arrow">➔</span>
								</button>
								<p class="form-privacy-note">🔒 閣下所提供的個人資料將嚴格保密，僅用於處理是次商業諮詢及回覆。</p>
							</div>
						</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'bizjapan_contact_form', 'bizjapan_render_contact_form' );

/**
 * 4. Automatically replace Google Form iframe, upgrade Hero image, and format Header on contact-us page
 */
function bizjapan_auto_replace_google_form( $content ) {
	if ( is_page( 'contact-us' ) || is_page( 'contact' ) ) {
		// 1. Upgrade hero image to modern Tokyo office 3D visual
		$modern_hero_url = get_template_directory_uri() . '/assets/images/contact-hero.jpg';
		$content = preg_replace(
			'/src="[^"]*Firefly-20240220121404[^"]*"/i',
			'src="' . esc_url( $modern_hero_url ) . '"',
			$content
		);
		$content = preg_replace(
			'/srcset="[^"]*Firefly-20240220121404[^"]*"/i',
			'',
			$content
		);

		// 2. Enhance H1 Header with modern lead copy
		if ( ! strpos( $content, 'bizjapan-contact-lead' ) ) {
			$lead_html = '<p class="bizjapan-contact-lead">免費商業諮詢 ‧ 助您在日本順利拓展業務<br><span class="bizjapan-contact-sublead">專業顧問團隊將於 1-2 個工作天內回覆，為您提供日本公司設立、法規、簽證與網站一站式規劃。</span></p>';
			$content = preg_replace(
				'/(<h1[^>]*>.*?<\/h1>)/is',
				'$1' . $lead_html,
				$content,
				1
			);
		}

		// 3. Cleanly replace Google Forms iframe with custom modern layout (and unwrap any enclosing H2/heading)
		if ( preg_match( '/<iframe[^>]*docs\.google\.com\/forms[^>]*>.*?<\/iframe>/is', $content ) ) {
			$form_html = bizjapan_render_contact_form();
			
			// Unwrap if encapsulated in heading tags
			$pattern_h = '/<h[1-6][^>]*>\s*(?:<div[^>]*>)?\s*<iframe[^>]*docs\.google\.com\/forms[^>]*>.*?<\/iframe>\s*(?:<\/div>)?\s*<\/h[1-6]>/is';
			if ( preg_match( $pattern_h, $content ) ) {
				$content = preg_replace( $pattern_h, $form_html, $content );
			} else {
				$content = preg_replace( '/<iframe[^>]*docs\.google\.com\/forms[^>]*>.*?<\/iframe>/is', $form_html, $content );
			}
		}
	}
	return $content;
}
add_filter( 'the_content', 'bizjapan_auto_replace_google_form', 20 );

