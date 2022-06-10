<?php

return [
  'frontend_url' => env('MIX_APP_URL', 'http://localhost:8000'),

  'response_password_reset_url' => env('RESPONSE_PASSWORD_RESET_URL', '/response-password-reset?token='),
];
