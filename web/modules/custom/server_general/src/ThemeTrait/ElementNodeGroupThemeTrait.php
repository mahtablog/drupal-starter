<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

use Drupal\intl_date\IntlDate;
use Drupal\server_general\EntityDateTrait;

/**
 * Helper method for building the Node news element.
 */
trait ElementNodeGroupThemeTrait {

  use ElementWrapThemeTrait;
  use EntityDateTrait;
  use InnerElementLayoutThemeTrait;
  use LineSeparatorThemeTrait;
  use LinkThemeTrait;
  use ElementLayoutThemeTrait;
  use SocialShareThemeTrait;
  use TagThemeTrait;
  use TitleAndLabelsThemeTrait;
  use GroupSubscriptionPromptThemeTrait;

  /**
   * Build the Node news element.
   *
   * @param string $title
   *   The node title.
   * @param string $label
   *   The label (e.g. `News`).
   * @param int $timestamp
   *   The timestamp.
   * @param array $image
   *   The responsive image render array.
   * @param array $body
   *   The body render array.
   * @param array $tags
   *   The tags, rendered with `TagThemeTrait::buildElementTags`.
   * @param array $social_share
   *   The render array of the Social share buttons.
   *
   * @return array
   *   The render array.
   *
   * @throws \IntlException
   */
  protected function buildElementGroupNode(string $title, string $label, int $timestamp, array $image, array $body, array $subscription_prompt): array {
    $elements = [];

    // Header.
    $element = $this->buildHeader(
      $title,
      $label,
      $timestamp,
      $subscription_prompt
    );
    $elements[] = $this->wrapContainerWide($element);

    // Main content and sidebar.
    $element = $this->buildMainWihtoutSidebar(
      $image,
      $this->wrapProseText($body),
    );
    $elements[] = $this->wrapContainerWide($element);

    $elements = $this->wrapContainerVerticalSpacingBig($elements);
    return $this->wrapContainerBottomPadding($elements);
  }

  /**
   * Build the header.
   *
   * @param string $title
   *   The node title.
   * @param string $label
   *   The label (e.g. `News`).
   * @param int $timestamp
   *   The timestamp.
   *
   * @return array
   *   Render array.
   *
   * @throws \IntlException
   */
  private function buildHeader(string $title, string $label, int $timestamp, array $subscription_prompt): array {
    $elements = [];

    $elements[] = $this->buildPageTitle($title);

    // Show offer to Subscribe to this group.
    if(!empty($subscription_prompt)) {
      $elements[] = $this->buildSubscriptionPrompt($subscription_prompt);
    }

    // Show the node type as a label.
    $elements[] = $this->buildLabelsFromText([$label]);

    // Date.
    $element = IntlDate::formatPattern($timestamp, 'long');

    // Make text bigger.
    $elements[] = $this->wrapTextResponsiveFontSize($element, 'lg');

    $elements = $this->wrapContainerVerticalSpacing($elements);

    return $this->wrapContainerMaxWidth($elements, '3xl');
  }

  /**
   * Build the Main content and the sidebar.
   *
   * @param array $image
   *   The responsive image render array.
   * @param array $body
   *   The body render array.
   * @param array $tags
   *   The tags, rendered with `TagThemeTrait::buildElementTags`.
   * @param array $social_share
   *   The render array of the Social share buttons.
   *
   * @return array
   *   Render array
   */
  private function buildMainWihtoutSidebar(array $image, array $body): array {
    $main_elements = [];

    $main_elements[] = $image;
    $main_elements[] = $body;

    return $this->buildElementLayoutMainWihtoutSidebar(
      $this->wrapContainerVerticalSpacingBig($main_elements),
    );
  }

}
