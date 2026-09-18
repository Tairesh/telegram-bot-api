<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * This object contains information about changes to a user payment subscription toward the current bot.
 */
final readonly class BotSubscriptionUpdated extends Type
{
    protected function __construct(
        /**
         * User who subscribed for payments toward the bot
         */
        public User $user,

        /**
         * Bot-specified invoice payload
         */
        public string $invoicePayload,

        /**
         * The new state of the subscription. Currently, it can be one of "canceled" if the user canceled the subscription,
         * "active" if the user re-enabled a previously canceled subscription, or "failed" if payment for the subscription failed.
         */
        public string $state,
    ) {
    }
}
