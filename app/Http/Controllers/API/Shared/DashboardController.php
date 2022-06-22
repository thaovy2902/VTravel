<?php

namespace App\Http\Controllers\API\Shared;

use App\Models\Tour;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\OrderResource;
use App\Http\Controllers\API\BaseController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends BaseController
{
  private $prevMonth;
  private $currentMonth;
  private $currentDay;

  public function __construct()
  {
    $this->prevMonth = date('m', strtotime('first day of last month'));
    $this->currentMonth = date('m');
    $this->currentDay = date('Y-m-d');
  }

  private function countUser()
  {
    return [
      'total' => User::countUser(3),
      'prevMonth' => User::countUserByMonth(3, $this->prevMonth),
      'currentMonth' => User::countUserByMonth(3, $this->currentMonth),
    ];
  }

  private function countTour()
  {
    return [
      'total' => Tour::countTour(),
      'prevMonth' => Tour::countTourByMonth($this->prevMonth),
      'currentMonth' => Tour::countTourByMonth($this->currentMonth),
    ];
  }

  private function countOrder()
  {
    return [
      'total' => Order::countOrder(),
      'prevMonth' => Order::countOrderByMonth($this->prevMonth),
      'currentMonth' => Order::countOrderByMonth($this->currentMonth),
    ];
  }

  private function totalRevenue()
  {
    return [
      'total' => (float) Order::totalRevenue(),
      'prevMonth' => (float) Order::totalRevenueByMonth($this->prevMonth),
      'currentMonth' => (float) Order::totalRevenueByMonth($this->currentMonth),
    ];
  }

  public function statistic()
  {
    $countUser = $this->countUser();
    $countTour = $this->countTour();
    $countOrder = $this->countOrder();
    $totalRevenue = $this->totalRevenue();

    return $this->respondData([
      'countUser' => $countUser,
      'countTour' => $countTour,
      'countOrder' => $countOrder,
      'totalRevenue' => $totalRevenue,
    ]);
  }

  public function newOrder()
  {
    $data = Order::with(['payment', 'tour', 'user'])
      ->whereDate('created_at', $this->currentDay);
    if (request()->q) {
      $data = $data->search(request()->q);
    }
    $data = $data->oldest('status')->latest()->paginate(5);

    return OrderResource::collection($data);
  }

  public function updateStatusOrder(Request $request, Order $order)
  {
    $order->update($request->only('status'));
    Mail::to($order->customer_email)->send(new OrderStatusUpdatedMail($order));

    return $this->respondData(new OrderResource($order->load(['payment', 'tour', 'user'])), Response::HTTP_ACCEPTED);
  }

  public function ratioOrder()
  {
    $data = [
      'totalOrderDomestic' => Order::totalOrderByCategory(1),
      'totalOrderForeign' => Order::totalOrderByCategory(2),
    ];

    return $this->respondData($data);;
  }

  public function popularTour()
  {
    $popularTour = DB::table('tours')
      ->join('orders', 'tours.id', '=', 'orders.tour_id')
      ->selectRaw('tours.name as tour_name, COUNT(*) as times_order')
      ->groupBy('tour_name')
      ->latest('times_order')
      ->take(10)
      ->get();

    return $this->respondData($popularTour);
  }

  public function revenue()
  {
    $type = request()->type;
    $month = request()->month;
    $quarter = request()->quarter;
    $year = request()->year;

    $results = Order::where('status', 3);

    if (!is_null($year)) {
      if ($type == 'month' && !is_null($month)) {
        $results = $results->whereMonth('created_at', $month)
          ->whereYear('created_at', $year)
          ->selectRaw('DATE(created_at) as label, SUM(total_amount) as total')
          ->groupBy('label')->oldest('label')->get();

        $data = $this->getDaysInYearMonth($month, $year, $results);
      }

      if ($type == 'quarter' && !is_null($quarter)) {
        $dates = $this->getDatesOfQuarter($quarter, $year);

        $results = $results->whereBetween('created_at', [$dates['startDate'], $dates['endDate']])
          ->selectRaw('EXTRACT(MONTH FROM created_at) as label, SUM(total_amount) as total')
          ->groupBy('label')->oldest('label')->get();

        $data = $this->getMonthsInQuarter($results);
      }

      if ($type == 'year') {
        $results = $results->whereYear('created_at', $year)
          ->selectRaw('EXTRACT(MONTH FROM created_at) as label, SUM(total_amount) as total')
          ->groupBy('label')->oldest('label')->get();

        $data = $this->getMonthsInYear($results);
      }

      return $this->respondData($data);
    }
  }

  private function getDatesOfQuarter($quarter, $year)
  {
    switch ((int) $quarter) {
      case 1:
        $startMonth = 'January';
        $endMonth = 'April';
        break;
      case 2:
        $startMonth = 'April';
        $endMonth = 'July';
        break;
      case 3:
        $startMonth = 'July';
        $endMonth = 'October';
        break;
      case 4:
        $startMonth = 'October';
        $endMonth = 'January';
        break;
    }

    return [
      'startDate' => date('Y-m-d', strtotime('first day of ' . $startMonth . ' ' . $year)),
      'endDate' => date('Y-m-d', strtotime('first day of ' . $endMonth . ' ' . $year)),
    ];
  }

  private function getMonthsInQuarter($results)
  {
    $data = [];

    foreach ($results as $value) {
      array_push($data, [
        'label' => 'Tháng ' . $value->label,
        'total' => $value->total,
      ]);
    }

    return $data;
  }

  private function getDaysInYearMonth($month, $year, $results)
  {
    $start_date = "01-" . $month . "-" . $year;
    $start_time = strtotime($start_date);

    $end_time = strtotime("+1 month", $start_time);

    $list = [];

    for ($i = $start_time; $i < $end_time; $i += 86400) {
      array_push($list, [
        'label' => date('Y-m-d', $i),
        'total' => '0'
      ]);
    }

    for ($i = 0; $i < count($results); $i++) {
      for ($j = 0; $j < count($list); $j++) {
        if ($results[$i]['label'] == $list[$j]['label']) {
          $list[$j]['total'] = $results[$i]['total'];
        }
      }
    }

    return $list;
  }

  private function getMonthsInYear($results)
  {
    $list = [];
    for ($i = 1; $i <= 12; $i++) {
      array_push($list, [
        'label' => strval($i),
        'total' => "0"
      ]);
    }

    for ($i = 0; $i < count($results); $i++) {
      for ($j = 0; $j < count($list); $j++) {
        if ($results[$i]['label'] == $list[$j]['label']) {
          $list[$j]['total'] = $results[$i]['total'];
        }
      }
    }

    for ($i = 0; $i < 12; $i++) {
      $list[$i]['label'] = 'Tháng ' . $list[$i]['label'];
    }

    return $list;
  }
}
