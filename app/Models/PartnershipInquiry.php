<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnershipInquiry extends Model
{
    /** @use HasFactory<\Database\Factories\PartnershipInquiryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'organization_name',
        'contact_person',
        'email',
        'phone',
        'inquiry_type',
        'message',
        'status',
    ];
}
