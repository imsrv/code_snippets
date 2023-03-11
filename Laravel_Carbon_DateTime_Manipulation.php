<?php

use Carbon\Carbon;

// You can try this if you want date time string:
$current_date_time = Carbon::now()->toDateTimeString(); // Produces something like "2019-03-11 12:25:00"

// If you want timestamp, you can try:
$current_timestamp = Carbon::now()->timestamp; // Produces something like 1552296328
