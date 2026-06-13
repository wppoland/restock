<?php

declare(strict_types=1);

namespace Restock\Admin;

defined('ABSPATH') || exit;

use Restock\Contract\HasHooks;

/**
 * Admin settings page registered under the WooCommerce menu.
 *
 * Stores settings in the `restock_settings` option (array).
 */
final class Settings implements HasHooks
{
    private const OPTION = 'restock_settings';
    private const PAGE   = 'restock-settings';
    private const SECTION_GENERAL = 'restock_general';
    private const SECTION_FORM    = 'restock_form';
    private const SECTION_EMAIL   = 'restock_email';

    public function registerHooks(): void
    {
        add_action('admin_menu', [$this, 'addMenuPage']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addMenuPage(): void
    {
        add_submenu_page(
            'woocommerce',
            __('Restock Settings', 'restock'),
            __('Restock', 'restock'),
            'manage_woocommerce',
            self::PAGE,
            [$this, 'renderPage'],
        );
    }

    public function registerSettings(): void
    {
        register_setting(
            self::PAGE,
            self::OPTION,
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'sanitize'],
            ],
        );

        // ── General ──────────────────────────────────────────────────────────
        add_settings_section(
            self::SECTION_GENERAL,
            __('General', 'restock'),
            '__return_false',
            self::PAGE,
        );

        add_settings_field(
            'allow_guests',
            __('Allow guest subscriptions', 'restock'),
            [$this, 'renderCheckbox'],
            self::PAGE,
            self::SECTION_GENERAL,
            [
                'id'    => 'allow_guests',
                'label' => __('Allow visitors who are not logged in to subscribe.', 'restock'),
            ],
        );

        add_settings_field(
            'show_on_single',
            __('Show form on product page', 'restock'),
            [$this, 'renderCheckbox'],
            self::PAGE,
            self::SECTION_GENERAL,
            [
                'id'    => 'show_on_single',
                'label' => __('Display the waitlist form on single product pages.', 'restock'),
            ],
        );

        // ── Form labels ───────────────────────────────────────────────────────
        add_settings_section(
            self::SECTION_FORM,
            __('Form labels', 'restock'),
            '__return_false',
            self::PAGE,
        );

        add_settings_field(
            'email_label',
            __('Email field label', 'restock'),
            [$this, 'renderText'],
            self::PAGE,
            self::SECTION_FORM,
            [
                'id'          => 'email_label',
                'placeholder' => __('Email address', 'restock'),
            ],
        );

        add_settings_field(
            'email_placeholder',
            __('Email field placeholder', 'restock'),
            [$this, 'renderText'],
            self::PAGE,
            self::SECTION_FORM,
            [
                'id'          => 'email_placeholder',
                'placeholder' => __('Your email address', 'restock'),
            ],
        );

        add_settings_field(
            'privacy_label',
            __('Consent checkbox label', 'restock'),
            [$this, 'renderText'],
            self::PAGE,
            self::SECTION_FORM,
            [
                'id'          => 'privacy_label',
                'placeholder' => __('I consent to receiving back-in-stock notifications.', 'restock'),
            ],
        );

        add_settings_field(
            'button_text',
            __('Button text', 'restock'),
            [$this, 'renderText'],
            self::PAGE,
            self::SECTION_FORM,
            [
                'id'          => 'button_text',
                'placeholder' => __('Join Waitlist', 'restock'),
            ],
        );

        // ── Email ─────────────────────────────────────────────────────────────
        add_settings_section(
            self::SECTION_EMAIL,
            __('Notification email', 'restock'),
            static function (): void {
                echo '<p>' . esc_html__(
                    'These texts are used when a product comes back in stock and subscribers are notified.',
                    'restock',
                ) . '</p>';
            },
            self::PAGE,
        );

        add_settings_field(
            'notify_subject',
            __('Email subject', 'restock'),
            [$this, 'renderText'],
            self::PAGE,
            self::SECTION_EMAIL,
            [
                'id'          => 'notify_subject',
                'placeholder' => __('Product back in stock - {product_name}', 'restock'),
                'description' => __('Use {product_name} as a placeholder for the product title.', 'restock'),
            ],
        );

        add_settings_field(
            'notify_intro_text',
            __('Email intro text', 'restock'),
            [$this, 'renderText'],
            self::PAGE,
            self::SECTION_EMAIL,
            [
                'id'          => 'notify_intro_text',
                'placeholder' => __('Product {product_name} is back in stock.', 'restock'),
                'description' => __('Use {product_name} as a placeholder for the product title.', 'restock'),
            ],
        );

        add_settings_field(
            'notify_outro_text',
            __('Email closing text', 'restock'),
            [$this, 'renderText'],
            self::PAGE,
            self::SECTION_EMAIL,
            [
                'id'          => 'notify_outro_text',
                'placeholder' => __('If you no longer wish to receive these messages, simply ignore this email.', 'restock'),
            ],
        );
    }

    public function renderPage(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields(self::PAGE);
                do_settings_sections(self::PAGE);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Renders a text input for a settings field.
     *
     * @param array<string, string> $args
     */
    public function renderText(array $args): void
    {
        $options = (array) get_option(self::OPTION, []);
        $id      = $args['id'] ?? '';
        $value   = isset($options[$id]) ? (string) $options[$id] : '';
        $placeholder = $args['placeholder'] ?? '';
        $description = $args['description'] ?? '';

        printf(
            '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" placeholder="%4$s" class="regular-text" />',
            esc_attr($id),
            esc_attr(self::OPTION),
            esc_attr($value),
            esc_attr($placeholder),
        );

        if ($description !== '') {
            printf('<p class="description">%s</p>', esc_html($description));
        }
    }

    /**
     * Renders a checkbox for a settings field.
     *
     * @param array<string, string> $args
     */
    public function renderCheckbox(array $args): void
    {
        $options = (array) get_option(self::OPTION, []);
        $id      = $args['id'] ?? '';
        $default = in_array($id, ['allow_guests', 'show_on_single'], true);
        $checked = isset($options[$id]) ? (bool) $options[$id] : $default;
        $label   = $args['label'] ?? '';

        printf(
            '<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1" %3$s /> %4$s</label>',
            esc_attr($id),
            esc_attr(self::OPTION),
            checked($checked, true, false),
            esc_html($label),
        );
    }

    /**
     * Sanitizes and coerces the incoming POST values before they are saved.
     *
     * @param mixed $raw
     * @return array<string, mixed>
     */
    public function sanitize(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        return [
            'allow_guests'      => ! empty($raw['allow_guests']),
            'show_on_single'    => ! empty($raw['show_on_single']),
            'email_label'       => sanitize_text_field((string) ($raw['email_label'] ?? '')),
            'email_placeholder' => sanitize_text_field((string) ($raw['email_placeholder'] ?? '')),
            'privacy_label'     => sanitize_text_field((string) ($raw['privacy_label'] ?? '')),
            'button_text'       => sanitize_text_field((string) ($raw['button_text'] ?? '')),
            'notify_subject'    => sanitize_text_field((string) ($raw['notify_subject'] ?? '')),
            'notify_intro_text' => sanitize_text_field((string) ($raw['notify_intro_text'] ?? '')),
            'notify_outro_text' => sanitize_text_field((string) ($raw['notify_outro_text'] ?? '')),
        ];
    }
}
