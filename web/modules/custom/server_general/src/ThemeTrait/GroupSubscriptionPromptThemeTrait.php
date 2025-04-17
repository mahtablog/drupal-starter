<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Helper method for building Title and labels of a content.
 */
trait GroupSubscriptionPromptThemeTrait {

  use ElementWrapThemeTrait;

  /**
   * Build the group subscribed title.
   *
   * @param string $msg
   *   The Message.
   *
   * @return array
   *   The render array.
   */
  protected function buildSubscriptionPrompt(array $msg): array {
    return [
      '#theme' => 'server_theme_subscription_prompt',
      '#user_name' => $msg['user_name'],
      '#link' => $msg['link'],
      '#group_name' =>  $msg['group_name'],
    ];
  }

}
