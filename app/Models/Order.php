<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = [
		'code',
		'customer_name',
		'customer_email',
		'customer_phone_number',
		'customer_address',
		'date_depart',
		'quantity_people',
		'quantity_children',
		'quantity_baby',
		'discount_code',
		'discount_percent',
		'total',
		'discount',
		'total_amount',
		'note',
		'status',
		'reason_cancel',
		'is_paid',
		'payment_id',
		'tour_id',
		'user_id',
	];

	public function payment()
	{
		return $this->belongsTo(Payment::class)->withDefault();
	}

	public function tour()
	{
		return $this->belongsTo(Tour::class)->withDefault();
	}

	public function user()
	{
		return $this->belongsTo(User::class)->withDefault();
	}

	public function scopeCountOrderByMonth($q, $month)
	{
		$currentYear = date('Y');

		return $q->whereMonth('created_at', $month)
			->whereYear('created_at', $currentYear)
			->count();
	}

	public function scopeCountOrder($q)
	{
		return $q->count();
	}

	public function scopeTotalRevenueByMonth($q, $month)
	{
		$currentYear = date('Y');

		return $q->where('status', 3)
			->whereMonth('created_at', $month)
			->whereYear('created_at', $currentYear)
			->sum('total_amount');
	}

	public function scopeTotalRevenue($q)
	{
		return $q->where('status', 3)->sum('total_amount');
	}

	public function scopeTotalOrderByCategory($q, $categoryId)
	{
		return $q->whereHas('tour', function ($q) use ($categoryId) {
			$q->where('category_id', $categoryId);
		})->count();
	}

	public function scopeSearch($query, $search)
	{
		if (!$search) {
			return $query;
		}
		return $query->whereRaw('searchtext @@ to_tsquery(\'english\', ?)', [$search])
			->orderByRaw('ts_rank(searchtext, to_tsquery(\'english\', ?)) DESC', [$search]);
	}
}
