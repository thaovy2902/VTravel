<?php

namespace App\Http\Controllers\API\User;

use App\Models\Feedback;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Feedback\SendFeedbackRequest;

class SendFeedbackController extends BaseController
{
  public function send(SendFeedbackRequest $request)
  {
    $feedback = Feedback::create($request->all());
    if ($feedback) {
      return $this->respondSuccess(config('message.send_success'), Response::HTTP_CREATED);
    }
  }
}
