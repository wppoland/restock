<?php
/**
 * Waitlist form.
 *
 * @var \WC_Product          $restock_product
 * @var array<string, mixed> $restock_settings
 * @var string               $restock_email
 *
 * @package Restock/Templates
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

?>
<div class="restock-waitlist" data-restock-waitlist>
    <?php if (! empty($restock_settings['show_title'])) : ?>
        <h3><?php echo esc_html((string) ($restock_settings['title'] ?? '')); ?></h3>
    <?php endif; ?>
    <?php if (! empty($restock_settings['show_intro']) && ! empty($restock_settings['intro_text'])) : ?>
        <p><?php echo esc_html((string) $restock_settings['intro_text']); ?></p>
    <?php endif; ?>
    <form class="restock-waitlist-form">
        <input type="hidden" name="product_id" value="<?php echo esc_attr((string) $restock_product->get_id()); ?>" />
        <label>
            <span class="screen-reader-text"><?php echo esc_html((string) ($restock_settings['email_label'] ?? __('Email address', 'restock'))); ?></span>
            <input type="email" name="email" value="<?php echo esc_attr($restock_email); ?>" placeholder="<?php echo esc_attr((string) ($restock_settings['email_placeholder'] ?? __('Your email address', 'restock'))); ?>" required />
        </label>
        <label class="restock-waitlist__privacy">
            <input type="checkbox" name="privacy" value="1" required />
            <span><?php echo esc_html((string) ($restock_settings['privacy_label'] ?? __('I consent to receiving back-in-stock notifications.', 'restock'))); ?></span>
        </label>
        <button type="submit" class="button alt"><?php echo esc_html((string) ($restock_settings['button_text'] ?? __('Join Waitlist', 'restock'))); ?></button>
        <p class="restock-waitlist__message" data-restock-waitlist-message hidden></p>
    </form>
</div>
