<?php
  function generateSlug($string) {
    // Convert to lowercase
    $slug = strtolower($string);

    // Remove special characters
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

    // Replace whitespace and dashes with single dash
    $slug = preg_replace('/[\s-]+/', '-', $slug);

    // Trim dashes
    $slug = trim($slug, '-');

    return $slug;
}

?>