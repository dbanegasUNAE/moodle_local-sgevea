<?php
$user_roles = getUserRolesID($USER->id);
$allowed_roles = get_config('local_sgevea', 'generalsettings_my_top_roles');
$allowed_roles = explode(',', $allowed_roles);
if (!empty(array_intersect($user_roles, $allowed_roles))) {
    // Show content
    $viewTopCont = get_config('local_sgevea', 'generalsettings_my_top_cont');
    echo format_text($viewTopCont, FORMAT_HTML);
}
