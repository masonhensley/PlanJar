<!DOCTYPE html>

<html>
<?php
/* The echo construct output text directly to the page */
echo "This is written to the page!!!";

/* Remember header() must be called before any actual output is sent
(normal HTML tags, blank lines in a file, or from PHP) */
header("Location: http://www.faqme.com/planjar");

exit;
?>