<?php

namespace Drupal\time_block;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Service to provide helper functions for the module.
 */
class TimeBlockHelper {

  /**
   * Function for convert the time to the timezone that the user selected.
   */
  public function convert(string $timezone):string {
    $current_time = new DrupalDateTime('now', $timezone);
    return $current_time->format('dS M Y - h:i A');
  }

}
