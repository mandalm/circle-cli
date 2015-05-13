[![Circle CI](https://circleci.com/gh/code-drop/Circle-CLI.svg?style=svg)](https://circleci.com/gh/code-drop/Circle-CLI)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/code-drop/Circle-CLI/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/code-drop/Circle-CLI/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/code-drop/Circle-CLI/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/code-drop/Circle-CLI/?branch=master)
[![License](https://poser.pugx.org/codedrop/circle-cli/license.svg)](https://packagist.org/packages/codedrop/circle-cli)

# Circle CLI

This is a CLI utility built with Symfony Console to query Circle CI projects.

# Installation

    # Install with composer.
    composer require "codedrop/circle-cli ~1.0"

    # Copy the sample config file into your home directory (or your project)
    cp vendor/codedrop/circle-cli/circle-cli.yml ~/

    # Copy the sample private file, then add your token to this file.
    cp vendor/codedrop/circle-cli/circle-cli.private.yml.sample ./

# Commands

I'm open to adding new commands, feel free to create an issue.

### Status Command

This command uses the [most recent builds](https://circleci.com/docs/api#recent-builds-project) endpoint
to provide a quick status overview of the project. The table fields and the number of results to display
is all configurable.

##### Example

    circle status [-p|--project-name[="..."]] [-u|--username[="..."]]

![Status command - Table](https://raw.githubusercontent.com/code-drop/Circle-CLI/master/assets/status.jpg)

### Progress Command

This command allows you to view the live results of a running build by polling the server. The output
can be formatted as either a table with all the results or a simple progress bar.

#### Example

    circle progress [-o|--build-num[="..."]] [-p|--project-name[="..."]] [-u|--username[="..."]] [-r|--refresh-interval[="..."]] [-f|--format[="..."]]

    # Show the progress of the last started build with username/project from config
    # and the default format.
    circle progress

![Progress command - Table](https://raw.githubusercontent.com/code-drop/Circle-CLI/master/assets/progress-table.jpg)

### Add SSH Key

This command allows you to add an SSH deploy key to a project.

#### Example

    circle add-key [-u|--username[="..."]] [-p|--project-name[="..."]] [-f|--private-key[="..."]] [--hostname[="..."]]

### Build Command

This command allows you to trigger a new build on a given branch.

#### Example

    circle build [-u|--username[="..."]] [-p|--project-name[="..."]] [-b|--branch[="..."]]

### List Projects Command

This command provides a list of all projects within your Circle CI account.

##### Example

    circle projects

### Retry Build Command

This command starts a "retry" of a given build. You can use "latest" to retry
the last build and using the "ssh" retry-method option to rebuild with SSH enabled.

##### Example

    circle retry [-o|--build-num[="..."]] [-m|--retry-method[="..."]] [-p|--project-name[="..."]] [-u|--username[="..."]]

# Configuration

Circle-CLI has sensible defaults but is highly configurable. Configuration files can live
in three different places.

* ~/circle-cli.yml - A global configuration file in your home directory.
* circle-cli.yml - A local configuration file that takes precedence over the global file.
* circle-cli.private.yml  - A local configuration file that can be used for your circle-token and excluded in .gitignore.

*Request configuration* are the parameters that are passed as the URL params and are different per endpoint.

*Display configuration* is a list of display fields from the endpoint response.

*Globals configuration* is merged into each endpoint configuration as an easy way to manage request or display config
that you want to be the same across all commands.

Both request and response documentation are available on the Circle CI website at
[https://circleci.com/docs/api](https://circleci.com/docs/api). The fields can be used directly
from the response under the display fields in the circle-cli.yml configuration file.

# TODO

## Features

* Add new command to [cancel a running build](https://circleci.com/docs/api#cancel-build).
* Add a filter to the RetryCommand, StatusCommand & CancelCommand to filter by branch.
* [Trigger a new build](https://circleci.com/docs/api#new-build) given any branch.
* Release a phar download? Use box?

## Improvements

* See if trigger new build endpoint can help with "latest" feature.
* Don't require empty config entries for endpoints that don't need any.
* Update progress to be minutes rather than seconds.
* Investigate solutions for redrawing errors with Symfony progress bar.
* Status output after a cancelled during the progress command should highlight the build that was cancelled.
* Improve output filtering, using $build->toArray() more often.
