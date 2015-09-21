<?php

/**
TEST 3:
Given a string:
1,2,3,4,5,6

Create a function that gets the numbers on the left and the right of the given element.
Here are some expected outputs:

pagination(1); // array('prev' => null, 'next' => 2);
pagination(2); // array('prev' => 1, 'next' => 3);
pagination(6); // array('prev' => 5, 'next' => null);

please do so "without" making use of explode() since the string can be comprised of thousands of elements when exploded and that would be slow on the memory.
only use string manipulation functions.
*/

// string to search
$subject = '1,2,3,4,5,6';

/**
 * @param $search
 */
function pagination($search) {
    global $subject; // using it globally to match the requested function definition
    $result = array('prev' => null, 'next' => null);

    if (!$search) return $result;

    // if the elem to search is found multiple times, I will only consider the first match (from left to right)
    $pattern = '/([0-9])?.?('.$search.').?([0-9])?/';

    preg_match($pattern, $subject, $matches);

    switch (sizeof($matches)) {
        case 4:
            // full match or found in first position
            $result['next'] = intval($matches[3]); // next is always on this position
            if ('' !== $matches[1]) {
                // full match, need to set also prev
                $result['prev'] = intval($matches[1]);
            }
            break;
        case 3:
            // found in last position
            $result['prev'] = intval($matches[1]); // only need to set prev
            break;
        default:
            // nothing matched
    }

    return $result;
}

?>
