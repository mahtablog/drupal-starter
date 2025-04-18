<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Helper methods for rendering People/Person Teaser elements.
 */
trait PersonCardsThemeTrait {

  use ElementLayoutThemeTrait;
  use ElementWrapThemeTrait;
  use InnerElementLayoutThemeTrait;
  use CardThemeTrait;

  /**
   * Build Persone cards element.
   *
   * @param string $title
   *   The title.
   * @param array $body
   *   The body render array.
   * @param array $items
   *   The render array built with
   *   `ElementLayoutThemeTrait::buildElementLayoutTitleBodyAndItems`.
   *
   * @return array
   *   The render array.
   */
  protected function buildElementPersonCards(string $title, array $body, array $items): array {
    return $this->buildElementLayoutTitleBodyAndItems(
      $title,
      $body,
      $this->buildCards($items),
    );
  }

  /**
   * Build a Person teaser.
   *
   * @param string $image_url
   *   The image Url.
   * @param string $alt
   *   The image alt.
   * @param string $name
   *   The name.
   * @param string|null $subtitle
   *   Optional; The subtitle (e.g. work title).
   *
   * @return array
   *   The render array.
   */
  protected function buildElementPersonCard(string $image_url, string $alt, string $name, ?string $subtitle = NULL, ?string $role = NULL, ?array $emailcall = []): array {
    $elements = [];
    $element = [
      '#theme' => 'image',
      '#uri' => $image_url,
      '#alt' => $alt,
      '#width' => 128,
    ];

    $elements[] = $this->wrapRoundedCornersFull($element);

    $inner_elements = [];

    $element = $this->wrapTextFontWeight($name, 'bold');
    $element = $this->wrapTextCenter($element);
    $inner_elements[] = $this->wrapTextColor($element, 'dark-gray');


    if ($subtitle) {
      $element = $this->wrapTextResponsiveFontSize($subtitle, 'sm');
      $element = $this->wrapTextCenter($element);
      $inner_elements[] = $this->wrapTextColor($element, 'gray');
    }

    if ($role) {
      $element = $this->wrapTextResponsiveFontSize($role, 'sm');
      $element = $this->wrapTextCenter($element);
      $element = $this->wrapTextColor($element, 'green');
      $inner_elements[] = $this->wrapTextBackground($element, 'light-green');
    }

    $elements[] = $this->wrapContainerVerticalSpacingTiny($inner_elements, 'center');

    $emailcall_elements[] = $this->wrapEmailCall($emailcall, 'text-gray-500');
    $elements[] = $this->wrapContainerVerticalSpacing($emailcall_elements, 'center', 'full');

    return $this->buildInnerElementLayoutCentered($elements, 'pt-4');
  }

}
