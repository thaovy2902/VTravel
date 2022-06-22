<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
  Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::post('forgot-password', 'ForgotPasswordController@sendMail');
    Route::put('reset-password', 'ResetPasswordController@process');
    Route::put('update-details', 'ProfileController@setUserData');
    Route::put('update-password', 'ProfileController@setPassword');
    Route::put('update-avatar', 'ProfileController@setAvatar');
  });

  //Owner
  Route::group([
    'namespace' => 'Owner',
    'prefix' => 'owner',
    'middleware' => 'jwt.role:owner'
  ], function () {
    Route::get('roles', 'RoleController');
    Route::apiResource('feedbacks', 'FeedbackController')->except(['store', 'show']);
    Route::get('permissions', 'PermissionController@index');
    Route::put('permissions/{user}', 'PermissionController@update');
  });

  //Admin
  Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin',
    'middleware' => 'jwt.role:admin'
  ], function () {
    Route::apiResource('users', 'UserController')->except(['show']);
    Route::apiResource('tours', 'TourController')->except(['show']);
    Route::apiResource('slides', 'SlideController')->except(['show']);
    Route::apiResource('ratings', 'RatingController')->except(['store', 'show']);
    Route::apiResource('orders', 'OrderController')->except(['show']);
    Route::apiResource('discount-codes', 'DiscountCodeController')->only(['index', 'store']);

    //Send mail discount code
    Route::post('send-mail-discount-code', 'DiscountCodeController@send');

    //Update active controller
    Route::put('update-active-slide/{slide}', 'SlideController@updateIsActive');
    Route::put('update-active-user/{user}', 'UserController@updateIsActive');

    //Update featured controller
    Route::put('update-status-tour/{tour}', 'TourController@updateStatus');
  });

  //User
  Route::group(['namespace' => 'User'], function () {
    Route::get('slides', 'SlideController');
    Route::get('tours-new', 'TourController@getTourNew');
    Route::get('tours-featured', 'TourController@getTourFeatured');
    Route::get('related-tour', 'TourController@getRelatedTour');
    Route::get('categories', 'CategoryController');
    Route::get('payments', 'PaymentController');
    Route::get('tours', 'TourController@index');
    Route::get('tours/{slug}', 'TourController@show');
    Route::get('tours/{slug}/ratings', 'RatingController@index');
    Route::post('tours/{slug}/ratings', 'RatingController@store');
    Route::put('ratings/{rating}', 'RatingController@update');
    Route::delete('ratings/{rating}', 'RatingController@destroy');
    Route::post('send-feedback', 'SendFeedbackController@send');

    Route::get('top-rating', 'RatingController@getTopRating');

    //Get avg rating tour
    Route::get('avg-rating/{slug}', 'RatingController@getAvgRating');

    //Order
    Route::post('send-mail-verify-transaction', 'SendVerifyTransactionController@sendMail');
    Route::post('verify-transaction', 'VerifyTransactionController@verify');
    Route::post('apply-discount', 'ApplyDiscountController@apply');
    Route::apiResource('orders', 'OrderController')->except(['destroy', 'show']);
    Route::get('get-tour-data/{tour}', 'TourController@getTourData');

    //Payment online vnpay
    Route::post('create-vnpay', 'VNPayController@create');

    //Profile
    Route::get('profile', 'ProfileController')->middleware('jwt.auth');
  });

  //cities
  Route::get('cities', 'CityController');

  //Notification
  Route::get('notifications', 'NotificationController@index');
  Route::post('markAsRead', 'NotificationController@markAsRead');
  Route::post('markAsReadAll', 'NotificationController@markAsReadAll');
  Route::post('deleteNotification', 'NotificationController@deleteNotification');

  //Dashboar shared to admin and owner
  Route::group([
    'namespace' => 'Shared',
    'prefix' => 'dashboard',
    'middleware' => 'jwt.role:admin,owner'
  ], function () {
    Route::get('statistic', 'DashboardController@statistic');
    Route::get('new-order', 'DashboardController@newOrder');
    Route::put('update-status-order/{order}', 'DashboardController@updateStatusOrder');
    Route::get('ratio-order', 'DashboardController@ratioOrder');
    Route::get('popular-tour', 'DashboardController@popularTour');
    Route::get('revenue', 'DashboardController@revenue');
  });

  //Chatbot
  Route::group(['prefix' => 'chatbot'], function () {
    Route::get('getTourByPrice', 'ChatbotController@getTourByPrice');
    Route::get('getTourByDestination', 'ChatbotController@getTourByDestination');
  });
});
