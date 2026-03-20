<?php

use App\Livewire\ContactForm;
use Livewire\Livewire;

it('renders the seeded contact details and form', function () {
    $this->get(route('contact.index'))
        ->assertOk()
        ->assertSeeText('Contact Us')
        ->assertSeeText('+20 100 555 7788')
        ->assertSeeText('info@masjidalhuda.test')
        ->assertSeeText('Send Us a Message');
});

it('validates required contact form fields before storing a submission', function () {
    Livewire::test(ContactForm::class)
        ->set('data.name', '')
        ->set('data.email', 'not-an-email')
        ->set('data.subject', '')
        ->set('data.message', 'short')
        ->call('submit')
        ->assertHasErrors(['data.name', 'data.email', 'data.subject', 'data.message']);

    $this->assertDatabaseMissing('contact_submissions', [
        'email' => 'not-an-email',
    ]);
});

it('stores contact submissions from the livewire contact form', function () {
    Livewire::test(ContactForm::class)
        ->set('data.name', 'Test Visitor')
        ->set('data.email', 'test.visitor@example.com')
        ->set('data.phone', '+20 100 999 0000')
        ->set('data.subject', 'Volunteer request')
        ->set('data.message', 'I would like to volunteer during the upcoming community iftar event.')
        ->call('submit')
        ->assertDispatched('contact-submitted');

    $this->assertDatabaseHas('contact_submissions', [
        'email' => 'test.visitor@example.com',
        'subject' => 'Volunteer request',
    ]);
});
