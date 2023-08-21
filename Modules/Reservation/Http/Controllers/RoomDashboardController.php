<?php

namespace Modules\Reservation\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\RoomManagement\Entities\Room;
use Illuminate\Http\Request;

class RoomDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function showTimeline() {
        $rooms = Room::all();

        $groups = [];
        $items = [];

        foreach ($rooms as $room) {
            $group = [
                'id' => $room->id,
                'content' => 'Room ' . $room->name,
                'order' => $room->id,
            ];

            $room_reservations = $room->room_reservations;

            foreach ($room_reservations as $reservation) {
                 $item = [
                    'id' => $reservation->id,
                    'group' => $room->id,
                    'start' => $reservation->room_check_in_date,
                    'end' => $reservation->room_check_out_date,
                    'type' => 'range',
                    'content' => 'Reservation' . $reservation->id
                ];

                $items[] = $item;
            }

            $groups[] = $group;
        }

        return view('reservation::reservation.room_dashboard', compact('groups', 'items'));
    }
}
