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
	$body .= "■ 聯絡電話 / WhatsApp: " . $phone . "\n";
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
	$reply_body .= "我們的專業顧問團隊將於 1-2 個工作天內檢閱您的需求，並透過電郵或 WhatsApp 與閣下聯絡，提供專屬建議。\n\n";
	$reply_body .= "--------------------------------------------------\n";
	$reply_body .= "■ 登記服務類別: " . $service . "\n";
	$reply_body .= "■ 您的聯絡電話: " . $phone . "\n";
	$reply_body .= "--------------------------------------------------\n\n";
	$reply_body .= "如有緊急事項，亦歡迎隨時透過 WhatsApp 與我們即時溝通。\n\n";
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
 * 3. Render Form HTML
 */
function bizjapan_render_contact_form() {
	$result = bizjapan_process_contact_form();

	ob_start();
	?>
	<div class="bizjapan-form-container">
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

					<!-- Phone / WhatsApp -->
					<div class="form-group">
						<label for="contact_phone">聯絡電話 / WhatsApp <span class="required">*</span></label>
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
						<span class="btn-text">確認送出表格 (Submit)</span>
						<span class="btn-arrow">➔</span>
					</button>
					<p class="form-privacy-note">🔒 閣下所提供的個人資料將嚴格保密，僅用於處理是次商業諮詢。</p>
				</div>
			</form>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'bizjapan_contact_form', 'bizjapan_render_contact_form' );

/**
 * 4. Automatically replace Google Form iframe on contact-us page
 */
function bizjapan_auto_replace_google_form( $content ) {
	if ( is_page( 'contact-us' ) || is_page( 'contact' ) ) {
		// If page contains google forms iframe, replace it with our custom form
		if ( preg_match( '/<iframe[^>]*docs\.google\.com\/forms[^>]*>.*?<\/iframe>/is', $content ) ) {
			$form_html = bizjapan_render_contact_form();
			$content = preg_replace( '/<iframe[^>]*docs\.google\.com\/forms[^>]*>.*?<\/iframe>/is', $form_html, $content );
		}
	}
	return $content;
}
add_filter( 'the_content', 'bizjapan_auto_replace_google_form', 20 );
