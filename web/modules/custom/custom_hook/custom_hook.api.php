<?php

/**
 * @file
 * Hooks related to modules will be present here.
 */

/**
 * This function is info hook that simply returns a array of data.
 *
 * @param mixed $array
 *   This array contains simply array with few string values.
 *
 * @return mixed
 *   Depending on the requirement user will use the hook info information.
 */
function hook_items_list(array &$array) {
  // Do further work with the array.
}

/**
 * This function provides number of count a entity have in current session.
 *
 * @param int $count
 *   Number of views a entity have.
 *
 * @return mixed
 *   Depending on the requirement user will use the hook info information.
 */
function hook_count_incremented(int $count, object $entity) {
  // Do further work with the array.
}
