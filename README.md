Consolidator for Drupal 7
=====

**This is a proof of concept and not yet meant for production use**.

Consolidate information from a number of sources, for example REST APIs or the Drupal database, into a simple report.

Comes with an example report, but requires development to create custom reports based on the example provided.

Usage
-----

Install consolidator_example, go to /admin/reports/consolidator, and select the report "Star wars characters average height vs. average air quality measurements in cities". Submit and view the report results.

Extending this module
-----

Build your own reports by copy-pasting the consolidator_example module and creating your own module based on that code. Clear cache, and your reports will appear in /admin/reports/consolidator.

Development and demos
-----

Install Docker and run:

    ./scripts/deploy.sh

This will give you a login link to a development environment.

To run a drush command, run:

    docker-compose exec web /bin/bash -c 'drush help'

To temporarily shut down your development environment, run:

    docker-compose down

Bring it back up by typing

    ./scripts/deploy.sh

To destroy your development environment, run:

    docker-compose down -v
