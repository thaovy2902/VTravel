<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\NotificationResource;

class NotificationController extends BaseController
{
  public function __construct()
  {
    $this->middleware('jwt.auth');
  }

  public function index()
  {
    return [
      'read' => NotificationResource::collection(auth()->user()->readNotifications),
      'unRead' => NotificationResource::collection(auth()->user()->unreadNotifications),
    ];
  }

  public function markAsRead()
  {
    auth()->user()->unreadNotifications->where('id', request()->id)->markAsRead();
  }

  public function markAsReadAll()
  {
    auth()->user()->unreadNotifications->markAsRead();
  }

  public function deleteNotification()
  {
    auth()->user()->notifications()->delete();
  }
}
