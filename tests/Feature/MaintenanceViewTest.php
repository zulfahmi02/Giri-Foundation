<?php

test('maintenance page uses the public site identity and standalone styling', function () {
    config()->set('seo.site_name', 'GIRI Foundation');
    config()->set('seo.site_alternate_name', 'Yayasan Giri Nusantara Sejahtera');

    $this->view('errors.503')
        ->assertSee('Pemeliharaan Situs')
        ->assertSee('Kami sedang merapikan sistem.')
        ->assertSee('GIRI Foundation')
        ->assertSee('Yayasan Giri Nusantara Sejahtera')
        ->assertSee('Status')
        ->assertSee('503')
        ->assertSee('image/logo.png', false)
        ->assertSee('rel="icon"', false)
        ->assertSee('name="robots" content="noindex,follow"', false)
        ->assertSee('mailto:girinusantarasejahtera@gmail.com', false)
        ->assertDontSee('@vite');
});
