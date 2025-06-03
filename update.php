<?php
echo '<pre>';

// Runs "git pull" and returns the last output line into $last_line.
// Stores the return value of the shell command in $retval.
$last_line = system('git pull 2>&1', $retval);

// Printing additional info
echo '
</pre>
<hr />Last line of the output: ' . $last_line . '
<hr />Return value: ' . $retval . '
<hr /><a href="'. $_SERVER['PHP_SELF'] .'">Update</a>';
