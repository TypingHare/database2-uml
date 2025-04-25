<?php

require_once "../minimal.php";

/**
 * Retrieves all courses for the current semester/year
 *
 * @api
 * @author Alexis Marx
 */
handle(HttpMethod::GET, function () {
    $courses = get_all_sections_semester_year("Fall", "2025");
    success_response("Retrieved all courses.", [
        "list" => array_values($courses)
    ]);
});
