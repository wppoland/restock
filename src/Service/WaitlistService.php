<?php

declare(strict_types=1);

namespace Restock\Service;

defined('ABSPATH') || exit;

use Restock\Contract\HasHooks;
use Restock\Repository\WaitlistRepository;
use Restock\Util\TemplateLoader;
use WPPoland\StorefrontKit\Waitlist\WaitlistEngine;

final class WaitlistService implements HasHooks
{
    private readonly WaitlistEngine $engine;

    public function __construct(
        private readonly WaitlistRepository $repository,
        private readonly TemplateLoader $templateLoader,
    ) {
        $this->engine = new WaitlistEngine(
            repository: $this->repository,
            ajaxAction: 'restock_waitlist_subscribe',
            nonceAction: 'restock_waitlist',
            scriptObjectName: 'restockWaitlist',
            assetHandle: 'restock-waitlist',
            styleUrl: \Restock\Plugin::instance()->url('assets/css/waitlist.css'),
            scriptUrl: \Restock\Plugin::instance()->url('assets/js/waitlist.js'),
            version: \Restock\VERSION,
            templateName: 'single-product/waitlist-form',
            defaultMessages: [
                'generic_error' => __('Something went wrong. Please try again.', 'restock'),
                'product_not_found' => __('Product not found.', 'restock'),
                'disabled' => __('Waitlist is unavailable for this product.', 'restock'),
                'invalid_email' => __('Provide a valid email address.', 'restock'),
                'privacy_error' => __('You must accept the consent for email contact.', 'restock'),
                'login_required' => __('Login to join the waitlist.', 'restock'),
                'success' => __('Thank you. You have been added to the waitlist.', 'restock'),
                'notify_subject' => __('Product back in stock - {product_name}', 'restock'),
                'notify_intro' => __('Product {product_name} is back in stock.', 'restock'),
                'notify_outro' => __('If you no longer wish to receive these messages, simply ignore this email.', 'restock'),
            ],
            isEnabled: fn (): bool => $this->isEnabled(),
            settings: fn (): array => $this->getSettings(),
            renderTemplate: function (string $template, array $data): void {
                $this->templateLoader->include($template, $data);
            },
        );
    }

    public function registerHooks(): void
    {
        $this->engine->registerHooks();
    }

    public function isEnabled(): bool
    {
        // Simple MVP is always enabled.
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        $defaults = [
            'allow_guests' => true,
            'show_on_single' => true,
        ];

        $options = get_option('restock_settings', []);

        return array_merge($defaults, is_array($options) ? $options : []);
    }
}
