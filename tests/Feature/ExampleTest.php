<?php

use Database\Seeders\GiriFoundationSeeder;

test('the homepage renders successfully', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('GIRI FOUNDATION')
        ->assertSee('Pemberdayaan masyarakat')
        ->assertSee('kesejahteraan')
        ->assertSee('Indonesia');
});
