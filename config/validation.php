<?php

return [
  //user
  'user_name' => 'required|string|max:255',
  'email' => 'required|string|email|max:255',
  'password' => 'required|between:6,255',
  'phone_number' => 'between:10,12',
  'address' => 'string|max:255',

  //tour
  'tour_name' => 'required|string|max:255',

  //ulti
  'title' => 'required|string|max:255',
  'boolean' => 'required|boolean',
  'numeric' => 'required|numeric',
];
