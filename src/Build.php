<?php

namespace Circle;

use Circle\Iterator\MapIterator;

class Build {

  /**
   * The build info directly from the API.
   *
   * @var array
   */
  protected $buildInfo;

  /**
   * An array of fields to use when rendering this build as an array.
   *
   * @var array
   */
  protected $displayFields;

  /**
   * Construct a new Circle Build.
   *
   * @param array $build
   *   The build info.
   */
  public function __construct(array $build, $display_fields = []) {
    $this->buildInfo = $build;
    $this->displayFields = $display_fields;
  }

  /**
   * Gets the current build status.
   *
   * @return string
   *   The build status.
   */
  public function getStatus() {
    return $this->buildInfo['status'];
  }

  /**
   * Gets the last successful build time in seconds.

   * @return float
   *   The build time.
   */
  public function getPreviousSuccessfulBuildTime() {
    return $this->buildInfo['previous_successful_build']['build_time_millis'] / 1000;
  }

  /**
   * Gets the last successful build formatted as minutes:seconds.

   * @return string
   *   The build time formatted.
   */
  public function getPreviousSuccessfulBuildTimeFormatted() {
    return gmdate('i:s', $this->getPreviousSuccessfulBuildTime());
  }

  /**
   * Gets the build start time as a unix timestamp.
   *
   * @return int
   *   The unix timestamp for when the build begun.
   */
  public function getStartTime() {
    return strtotime($this->buildInfo['start_time']);
  }

  /**
   * Gets the very last step, last action's status.
   *
   * @return string
   *   The status string.
   */
  public function getLastActionStatus() {
    $last_step = $this->getLastStep();
    $last_action = $last_step->getLastAction();
    return $last_action->getStatus();
  }

  /**
   * Gets the build steps.
   *
   * @return \Circle\Step[]
   *   An iterator for the build steps.
   */
  public function getSteps() {
    return new MapIterator($this->buildInfo['steps'], function($step) {
      return new Step($step);
    });
  }

  /**
   * Gets the last step in the build. This can change if a build is running.
   *
   * @return \Circle\Step
   *   The current last step in this  build.
   */
  public function getLastStep() {
    $step = array_pop($this->buildInfo['steps']);
    $this->buildInfo['steps'][] = $step;
    return new Step($step);
  }

  /**
   * Check if our build is finished.
   *
   * @return bool
   *   TRUE is the build is finished otherwise FALSE.
   */
  public function isFinished() {
    return $this->buildInfo['lifecycle'] === 'finished';
  }

  /**
   * Gets the build as an array.
   *
   * @return array
   *   The build info.
   */
  public function toArray() {
    $results = array_intersect_key($this->buildInfo, array_flip($this->displayFields));
    $display_fields = $this->displayFields;

    // We sort the output using the keys so the result is the same as what is
    // defined in config.
    uksort($results, function($a, $b) use ($display_fields) {
      // We don't need to check for equality because we cannot have two keys
      // with the exact same value.
      return array_search($a, $display_fields) > array_search($b, $display_fields);
    });

    return $results;
  }

}
