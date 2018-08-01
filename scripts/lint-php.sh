#!/bin/bash
#
# Lint php files.
#
set -e

echo 'Use the following if you get false negatives:'
echo '// @codingStandardsIgnoreStart'
echo '...'
echo '// @codingStandardsIgnoreEnd'


docker run -v "$(pwd)":/code dcycle/php-lint \
  --standard=DrupalPractice /code
docker run -v "$(pwd)":/code dcycle/php-lint \
  --standard=Drupal /code
