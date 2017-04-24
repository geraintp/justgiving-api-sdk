<?php

namespace Klever\JustGivingApiSdk\Tests;

use Klever\JustGivingApiSdk\ResourceClients\Models\Event;

class EventTest extends Base
{
    /** @test */
    public function it_retrieves_an_event_given_an_event_id()
    {
        $response = $this->client->event->getById(479546)->getBodyAsObject();

        $this->assertEquals($response->name, 'Virgin London Marathon 2011 - Applying for a charity place');
    }

    /** @test */
    public function it_retrieves_an_event_listing()
    {
        $response = $this->client->event->getById(479546)->getBodyAsObject();

        $this->assertEqualAttributes(Event::class, $response);
    }

    /** @test */
    public function it_retrieves_fundraising_pages_for_a_given_event()
    {
        $response = $this->client->event->getPages(479546)->getBodyAsObject();

        $this->assertObjectHasAttributes(['companyAppealId', 'createdDate', 'currencyCode'], $response->fundraisingPages[0]);
    }

    /** @test */
    public function it_retrieves_a_list_of_types_of_events()
    {
        $response = $this->client->event->getTypes();

        $this->assertTrue(is_array($response->body->eventTypes));
        $this->assertObjectHasAttributes(['description', 'eventType', 'id', 'name'], $response->body->eventTypes[0]);
    }
}
