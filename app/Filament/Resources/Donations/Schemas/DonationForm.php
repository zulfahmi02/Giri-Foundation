<?php

namespace App\Filament\Resources\Donations\Schemas;

use App\Support\AdminStateOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('campaign_id')
                    ->label('Kampanye donasi')
                    ->relationship('campaign', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('donor_id')
                    ->label('Donatur')
                    ->relationship('donor', 'full_name')
                    ->searchable()
                    ->preload(),
                TextInput::make('invoice_number')
                    ->label('Nomor invoice')
                    ->required()
                    ->helperText('Diisi otomatis saat donasi dibuat. Ubah hanya jika diperlukan.'),
                TextInput::make('amount')
                    ->label('Jumlah donasi (Rp)')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->label('Metode pembayaran')
                    ->required()
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'e_wallet' => 'E-Wallet',
                        'cash' => 'Tunai',
                    ]),
                TextInput::make('payment_channel')
                    ->label('Channel pembayaran')
                    ->helperText('Contoh: BCA, GoPay, Mandiri, BRI.'),
                Select::make('payment_status')
                    ->label('Status pembayaran')
                    ->required()
                    ->options(AdminStateOptions::donationPaymentStatuses())
                    ->default('pending')
                    ->helperText('Ubah ke "Lunas" setelah pembayaran dikonfirmasi.'),
                DateTimePicker::make('paid_at')
                    ->label('Waktu pembayaran dikonfirmasi'),
                Toggle::make('is_anonymous')
                    ->label('Donasi anonim')
                    ->default(false)
                    ->helperText('Jika aktif, nama donatur tidak ditampilkan di publik.'),
                Textarea::make('message')
                    ->label('Pesan donatur')
                    ->columnSpanFull(),
                TextInput::make('proof_url')
                    ->label('URL bukti transfer')
                    ->url()
                    ->helperText('Link foto/screenshot bukti transfer jika ada.')
                    ->columnSpanFull(),
                TextInput::make('external_transaction_id')
                    ->label('ID transaksi eksternal')
                    ->helperText('ID dari payment gateway atau sistem pembayaran pihak ketiga.'),
            ]);
    }
}
