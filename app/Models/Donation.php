<?php

namespace App\Models;

use Database\Factories\DonationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Donation extends Model
{
    /** @use HasFactory<DonationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'campaign_id',
        'donor_id',
        'invoice_number',
        'amount',
        'payment_method',
        'payment_channel',
        'payment_status',
        'paid_at',
        'message',
        'proof_url',
        'external_transaction_id',
        'is_anonymous',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'is_anonymous' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        $sync = function (Donation $donation): void {
            $campaignIds = array_unique(array_filter([
                $donation->campaign_id,
                $donation->getOriginal('campaign_id'),
            ]));

            DonationCampaign::whereIn('id', $campaignIds)
                ->get()
                ->each(fn (DonationCampaign $campaign) => $campaign->syncCollectedAmount());
        };

        static::saved($sync);
        static::deleted($sync);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(DonationCampaign::class, 'campaign_id');
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $candidate = 'DON-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));

            if (! static::query()->where('invoice_number', $candidate)->exists()) {
                return $candidate;
            }
        }

        // Fallback ke UUID jika semua attempt collision (sangat tidak mungkin terjadi)
        return 'DON-'.Str::upper(str_replace('-', '', (string) Str::uuid()));
    }
}
