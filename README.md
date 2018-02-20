Consolidator
=====

Consolidate information from a number of sources, for example REST APIs or the Drupal database, into a simple report.

Comes with an example report, but requires development to create custom reports based on the example provided.

Development
-----

Install Docker and run:

    ./scripts/deploy.sh

This will give you a login link to a development environment.

To run a drush command, run:

    docker-compose exec web /bin/bash -c 'drush help'

To destroy your development environment, run:

    docker-compose down -v
