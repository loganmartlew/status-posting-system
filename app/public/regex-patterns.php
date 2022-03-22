<?php
  $STATUS_CODE_REGEXP = "/^S\d{4}$/";
  // Remove slashes from start and end of string for HTML pattern attribute
  $STATUS_CODE_REGEXP_HTML = substr($STATUS_CODE_REGEXP, 1, -1); 
  $STATUS_CODE_DESC = "Capital S followed by 4 numbers. E.g: S1234.";

  $STATUS_REGEXP = "/^([A-Za-z0-9\,\.\!\?]\s*)+$/";
  // Remove slashes from start and end of string for HTML pattern attribute
  $STATUS_REGEXP_HTML = substr($STATUS_REGEXP, 1, -1); 
  $STATUS_DESC = "Can include alphanumeric characters, ',', '.', '!', and '?'.";