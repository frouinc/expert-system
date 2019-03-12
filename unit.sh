#!/usr/bin/env bash

RED='\033[0;31m'
GREEN='\033[0;32m'
CYAN='\033[0;36m'
NC='\033[0m'

function checkFolder()
{
	echo -e ""
	TITLE=`echo $1 | tr a-z A-Z`
	echo -e "${CYAN}--- $TITLE ---${NC}"

	for i in $(seq 1 $2);
	do
		php -f execute.php "tests/$1/$i" > tests/$1/$i.testresult
		if cmp -s "tests/$1/$i.result" "tests/$1/$i.testresult" ; then
			echo -e "${GREEN}☑ $1 $i${NC}"
		else
			echo -e "${RED}☒ $1 $i${NC}"
		fi
	done
}

echo -e "${CYAN}---- START UNIT TESTS ----"
echo -e "__________________________${NC}"


checkFolder and 2
checkFolder not 4
checkFolder or 4
checkFolder parenthesis 11
checkFolder same 4
checkFolder xor 4