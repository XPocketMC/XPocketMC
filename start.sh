#!/usr/bin/env bash
DIR="$(cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
cd "$DIR"

while getopts "p:f:l" OPTION 2> /dev/null; do
	case ${OPTION} in
		p)
			PHP_BINARY="$OPTARG"
			;;
		f)
			XPOCKETMC_FILE="$OPTARG"
			;;
		l)
			DO_LOOP="yes"
			;;
		\?)
			break
			;;
	esac
done

if [ "$PHP_BINARY" == "" ]; then
	if [ -f ./bin/php7/bin/php ]; then
		export PHPRC=""
		PHP_BINARY="./bin/php7/bin/php"
	elif [[ -n $(type php 2> /dev/null) ]]; then
		PHP_BINARY=$(type -p php)
	else
		echo "Couldn't find a PHP binary in system PATH or $PWD/bin/php7/bin"
		echo "Please refer to the installation instructions at https://doc..io/en/rtfd/installation.html"
		exit 1
	fi
fi

if [ "$XPOCKETMC_FILE" == "" ]; then
	if [ -f ./XPocketMC.mp ]; then
		XPOCKETMC_FILE="./XPocketMC.mp"
	else
		echo "XPocketMC.mp not found"
		echo "Downloads can be found at https://github.com/XPocketMC/XPOCKETMC/releases"
		exit 1
	fi
fi

LOOPS=0

set +e

if [ "$DO_LOOP" == "yes" ]; then
	while true; do
		if [ ${LOOPS} -gt 0 ]; then
			echo "Restarted $LOOPS times"
		fi
		"$PHP_BINARY" "$XPOCKETMC_FILE" "$@"
		echo "To escape the loop, press CTRL+C now. Otherwise, wait 5 seconds for the server to restart."
		echo ""
		sleep 5
		((LOOPS++))
	done
else
	exec "$PHP_BINARY" "$XPOCKETMC_FILE" "$@"
fi