#!/bin/bash

#my_script --p_out /some/path --arg_1 5

while [ $# -gt 0 ]; do

   if [[ $1 == *"--"* ]]; then
        v="${1/--/}"
        declare $v="$2"
   fi

  shift
done

echo relaunching the import job...

cd /home/web/import/job

/bin/bash import_data.sh --directory $directory --attempts number_attempts

echo import job finished !
