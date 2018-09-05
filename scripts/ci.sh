#!/bin/bash
#
# Run continuous integration script
#
set -e

./scripts/lint-php.sh
./scripts/lint-sh.sh
./scripts/unit-tests.sh
