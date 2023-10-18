<?php
$user_roles = getUserRolesID($USER->id);
$allowed_roles = get_config('local_sgevea', 'generalsettings_my_bottom_roles');
$allowed_roles = explode(',', $allowed_roles);
if (!empty(array_intersect($user_roles, $allowed_roles))) {
    // Show content
    $viewBottomCont = get_config('local_sgevea', 'generalsettings_my_bottom_cont');
    echo format_text($viewBottomCont, FORMAT_HTML);
}
