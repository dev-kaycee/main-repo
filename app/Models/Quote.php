<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
	protected $fillable = [
			'client_name',
			'client_address',
			'issue_date',
			'expiry_date',
			'quote_number',
			'vat_number',
			'company_name',
			'company_address',
			'total_amount',
	];

	protected $casts = [
			'issue_date' => 'date',
			'expiry_date' => 'date',
			'total_amount' => 'decimal:2',
	];

	// Define the relationship to QuoteItem
	public function items()
	{
		return $this->hasMany(QuoteItem::class);
	}
}