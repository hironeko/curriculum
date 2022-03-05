<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyBilling extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_kana',
        'post_code',
        'prefecture',
        'address',
        'tel',
        'department',
        'billing_first_name',
        'billing_last_name',
        'billing_first_name_kana',
        'billing_last_name_kana',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
