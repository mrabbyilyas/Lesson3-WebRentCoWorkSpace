<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Models\BookingTransaction;
use App\Models\OfficeSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingTransactionController extends Controller
{
    //
    public function store(StoreBookingTransactionRequest $request)
    {
        $validatedData = $request->validated();

        $officeSpace = OfficeSpace::find($validatedData['office_space_id']);
        if (!$officeSpace) {
            return response()->json(['error' => 'Office Space not found.'], 404);
        }

        try {
            $validatedData['is_paid'] = false;
            $validatedData['booking_trx_id'] = BookingTransaction::generateUniqueTrxId();
            $validatedData['duration'] = $officeSpace->duration;

            $validatedData['ended_at'] = (new \DateTime($validatedData['started_at']))
                ->modify("+{$officeSpace->duration} days")
                ->format('Y-m-d');

            $bookingTransaction = BookingTransaction::create($validatedData);

            return response()->json([
                'message' => 'Booking created successfully.',
                'data' => $bookingTransaction,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating booking:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while creating the booking.'], 500);
        }
    }
}
