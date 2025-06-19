<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;

class EventController extends Controller
{
    use CanLoadRelationships;

    // Define the relations that can be loaded
    private array $relations = ['user', 'attendees', 'attendees.user'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Resolve which relations to load based on the request query string
        $relations = $this->resolveRelations($this->relations);

        // Build query and load requested relationships
        $query = $this->loadRelationships(Event::query(), $relations);

        // Return paginated events with loaded relations
        return EventResource::collection(
            $query->latest()->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]),
            'user_id' => 1, // Hardcoded for now
        ]);

        // Load relations before returning resource
        return new EventResource($this->loadRelationships($event, $this->relations));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Load relations before returning resource
        return new EventResource($this->loadRelationships($event, $this->relations));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
            ])
        );

        // Load relations before returning resource
        return new EventResource($this->loadRelationships($event, $this->relations));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response(status: 204);
    }
}
