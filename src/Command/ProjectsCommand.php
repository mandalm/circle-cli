<?php

namespace Circle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This command provides a list of all projects within your Circle CI account
 * using the [projects](https://circleci.com/docs/api#projects) endpoint.
 *
 * ##### Example
 *
 *     circle projects
 */
class ProjectsCommand extends CommandBase {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('projects')
      ->setDescription('Get a list of all projects');
    ;
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->renderAsTable($this->circle->getAllProjects(), $output);
    $this->finished();
  }

}
