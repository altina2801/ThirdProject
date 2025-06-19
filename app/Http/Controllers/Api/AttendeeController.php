<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AttendeeResource;
use App\Models\Event;
use App\Models\Attendee;
use App\Http\Traits\CanLoadRelationships;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user'];

    public function index(Event $event)
    {
        // Load relationships and query
        $attendees = $this->loadRelationship(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created attendee for the event.
     */
    public function store(Request $request, Event $event)
    { 
        $attendee = $event->attendees()->create([
            'user_id' => 1  // Replace with actual authenticated user ID if needed
        ]);

        return new AttendeeResource($attendee);
    }

    public function show(Event $event, Attendee $attendee)
    { 
        $this->loadRelationships($attendee);

        return new AttendeeResource($attendee);
    }

    public function update(Request $request, string $id)
    {
        // Implement update logic here
    }

    public function destroy(Event $event, Attendee $attendee)
    { 
        $attendee->delete();

        return response()->noContent(); // returns HTTP 204
    }
}
