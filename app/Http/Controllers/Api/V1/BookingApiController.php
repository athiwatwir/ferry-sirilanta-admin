<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\SequenceNumberHelper;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;
use App\Http\Resources\StationResource;

class BookingApiController extends Controller
{

    public function index(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $agent = $request->user();

        $bookings = Booking::select(['id', 'departdate', 'created_at', 'adult_passenger', 'child_passenger', 'infant_passenger', 'totalamt', 'subtotal', 'discount', 'status', 'bookingno as invoiceno', 'ticketno'])
            ->where('agent_id', $agent->id)
            ->where('departdate', '>=', $startDate)
            ->where('departdate', '<=', $endDate)
            ->get()->toArray();

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $bookings
        ], 201);
    }

    public function create(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'departdate' => 'required|date|after_or_equal:today',
                'adult_passenger' => 'required|integer|min:1|max:20',
                'child_passenger' => 'integer|min:0|max:20',
                'infant_passenger' => 'integer|min:0|max:20',
                'user_id' => 'nullable|string|size:36',
                //'totalamt' => 'required|numeric|min:0',
                //'subtotal' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'trip_type' => 'nullable|in:O,R,M',
                'note' => 'nullable|string',
                'book_channel' => 'nullable|string|max:45',
                'ispremiumflex' => 'nullable|in:Y,N',
                'promotion_id' => 'nullable|string|size:36',
                'api_merchant_id' => 'nullable|string|size:36',
                'referenceno' => 'nullable|string|max:100',

                // Customer data validation
                'customers' => 'required|array|min:1',
                'customers.*.fullname' => 'required|string|max:100',
                'customers.*.type' => 'nullable|string|max:45',
                'customers.*.passportno' => 'nullable|string|max:45',
                'customers.*.email' => 'nullable|email|max:45',
                'customers.*.mobile' => 'nullable|string|max:45',
                'customers.*.fulladdress' => 'nullable|string|max:255',
                'customers.*.mobile_code' => 'nullable|string|max:10',
                'customers.*.country' => 'nullable|string|max:100',
                'customers.*.title' => 'nullable|string|max:10',
                'customers.*.mobile_th' => 'nullable|string|max:45',
                'customers.*.birth_day' => 'nullable|date',
                'customers.*.isdefault' => 'nullable|in:Y,N',

                'routes' => 'required|array|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $totalamt = 0;
            $passenger = $request->adult_passenger ?? 1;
            $childPassenger = $request->child_passenger ?? 0;
            $infantPassenger = $request->infant_passenger ?? 0;
            $discount = $request->discount ?? 0;
            $agent = $request->user();

            // Start database transaction
            DB::beginTransaction();

            // Generate booking ID and booking number
            $bookingId = Str::uuid()->toString();
            $bookingNo = SequenceNumberHelper::getDocumentno('booking');
            $tripType = $request->trip_type ?? 'O';

            // Prepare booking data
            $bookingData = [
                'id' => $bookingId,
                'departdate' => $request->departdate,
                'adult_passenger' => $passenger,
                'child_passenger' => $childPassenger,
                'infant_passenger' => $infantPassenger,
                'user_id' => $request->user_id ?? null,
                'totalamt' => 0,
                'subtotal' => 0,
                'discount' => $discount,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'ispayment' => 'N',
                'trip_type' => $tripType,
                'note' => $request->note,
                'status' => 'DR', // Draft status
                'bookingno' => $bookingNo,
                'book_channel' => $request->book_channel ?? 'API',
                'ispremiumflex' => $request->ispremiumflex ?? 'N',
                //'promotion_id' => $request->promotion_id,
                'isconflict' => 'N',
                'amend' => 0,
                //'api_merchant_id' => $request->api_merchant_id,
                'isemailsent' => 'N',
                'referenceno' => $request->referenceno ?? null,
                'agent_id' => $agent->id
            ];

            // Insert booking
            DB::table('bookings')->insert($bookingData);

            // Process customers
            $customers = $request->customers;
            $customerIds = [];

            foreach ($customers as $index => $customerData) {
                $customerId = Str::uuid()->toString();
                $customerIds[] = $customerId;

                // Prepare customer data
                $customer = [
                    'id' => $customerId,
                    'fullname' => $customerData['fullname'],
                    'type' => $customerData['type'] ?? 'ADULT',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'passportno' => $customerData['passportno'] ?? null,
                    'email' => $customerData['email'] ?? null,
                    'mobile' => $customerData['mobile'] ?? null,
                    'fulladdress' => $customerData['fulladdress'] ?? null,
                    'mobile_code' => $customerData['mobile_code'] ?? null,
                    'country' => $customerData['country'] ?? null,
                    'title' => $customerData['title'] ?? null,
                    'mobile_th' => $customerData['mobile_th'] ?? null,
                    'birth_day' => $customerData['birth_day'] ?? null
                ];

                // Insert customer
                DB::table('customers')->insert($customer);

                // Create booking_customer relationship
                $bookingCustomerId = Str::uuid()->toString();
                $bookingCustomer = [
                    'id' => $bookingCustomerId,
                    'booking_id' => $bookingId,
                    'customer_id' => $customerId,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'isdefault' => ($index === 0) ? 'Y' : ($customerData['isdefault'] ?? 'N') // First customer is default
                ];

                DB::table('booking_customers')->insert($bookingCustomer);
            }


            //Process routes
            $routes = $request->routes;


            foreach ($routes as $index => $routeData) {
                $bookingRouteId = Str::uuid()->toString();
                $type = $tripType;
                if ($tripType != 'O') {
                    $type .= $index;
                }

                $_price = $routeData['price'] ?? 1;
                $_child = $routeData['child_price'] ?? 0;
                $_infant = $routeData['infant_price'] ?? 0;

                $_data = [
                    'id' => $bookingRouteId,
                    'sub_route_id' => $routeData['id'],
                    'type' => $type,
                    'traveldate' => $routeData['traveldate'],
                    'price' => $routeData['price'] ?? 0,
                    'infant_price' => $routeData['infant_price'] ?? 0,
                    'child_price' => $routeData['child_price'] ?? 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'booking_id' => $bookingId
                ];

                $totalamt += ($passenger * $_price);
                $totalamt += ($childPassenger * $_child);
                $totalamt += ($infantPassenger * $_infant);

                DB::table('booking_sub_routes')->insert($_data);
            }

            // Commit transaction
            DB::commit();

            //update booking
            $booking = Booking::whereId($bookingId)->first();
            $booking->subtotal = $totalamt;
            $booking->totalamt = $totalamt - $discount;
            $booking->save();

            // Prepare response data
            $responseData = [
                'booking_id' => $bookingId,
                'bookingno' => $bookingNo,
                'invoiceno' => $bookingNo,
                'status' => 'DR',
                'total_passengers' => $request->adult_passenger + ($request->child_passenger ?? 0) + ($request->infant_passenger ?? 0),
                'created_at' => Carbon::now()->toISOString(),
                'qrcode_url' => sprintf('%s/b/%s', $agent->site_url, $bookingNo)
            ];

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully, please request to complete payment.',
                'data' => $responseData
            ], 201);
        } catch (Exception $e) {
            // Rollback transaction on error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booking by ID
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $invoiceno)
    {
        $agent = $request->user();

        try {
            $booking = Booking::where('bookingno', $invoiceno)->with([
                'bookingCustomers',
                'bookingSubRoutes'
            ])->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            $bookingData = [
                'id' => $booking->id,
                'departdate' => Carbon::parse($booking->departdate)->format('Y-m-d'),
                'adult_passenger' => $booking->adult_passenger,
                'child_passenger' => $booking->child_passenger,
                'infant_passenger' => $booking->infant_passenger,
                'totalamt' => $booking->totalamt,
                'subtotal' => $booking->subtotal,
                'discount' => $booking->discout,
                'ispayment' => $booking->ispayment,
                'trip_type' => $booking->trip_type,
                'note' => $booking->note,
                'status' => $booking->status,
                'bookingno' => $booking->bookingno,
                'invoiceno' => $booking->bookingno,
                'referenceno' => $booking->referenceno,
                'complete_date' => Carbon::parse($booking->complete_date)->format('Y-m-d H:i'),
                'cancel_date' => Carbon::parse($booking->cancel_date)->format('Y-m-d H:i'),
                'reason' => $booking->reason,
                'qrcode_url' => sprintf('%s/b/%s', $agent->site_url, $booking->bookingno),
                'customers' => [],
                'routes' => []
            ];

            foreach ($booking->bookingCustomers as $customer) {
                $bookingData['customers'][] = [
                    'id' => $customer->id,
                    'fullname' => $customer->fullname,
                    'type' => $customer->type,
                    'passportno' => $customer->passportno,
                    'email' => $customer->email,
                    'mobile' => $customer->mobile,
                    'mobile_th' => $customer->mobile_th,

                    'country' => $customer->country,
                ];
            }

            foreach ($booking->bookingSubRoutes as $subRoute) {
                $bookingData['routes'][] = [
                    'id' => $subRoute->id,
                    'departure_time' => Carbon::parse($subRoute->depart_time)->format('H:i'),
                    'arrival_time' => Carbon::parse($subRoute->arrival_time)->format('H:i'),
                    'departure_timezone' => $subRoute->origin_timezone,
                    'arrival_timezone' => $subRoute->destination_timezone,
                    'boat_type' => $subRoute->boat_type,
                    'departure_station' => StationResource::collection([$subRoute->route->departStation])[0],
                    'destination_station' => StationResource::collection([$subRoute->route->destStation])[0],
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $bookingData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update booking status
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'invoiceno' => 'required|string',
                'payment_method' => 'required|string',
                'note' => 'nullable|string',
                'referenceno' => 'nullable|string'
            ]);

            $invoiceno = $request->invoiceno;
            $agent = $request->user();

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $booking =  Booking::where('bookingno', $invoiceno)->where('status', 'DR')->where('agent_id', $agent->id)->first();
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking cannot be completed.',
                    'errors' => []
                ], 422);
            }

            $ticketno = SequenceNumberHelper::getDocumentno('ticket', $agent->id);

            $updateData = [
                'status' => 'CO',
                'updated_at' => Carbon::now(),
                'ispayment' => 'Y',
                'complete_date' => Carbon::now(),
                'ticketno' => $ticketno
            ];

            if ($request->has('referenceno')) {
                $updateData['referenceno'] = $request->referenceno;
            }

            if ($request->has('note')) {
                $updateData['note'] = $request->note;
            }

            if ($request->has('payment_method')) {
                $updateData['payment_method'] = $request->payment_method;
            }

            $updated = DB::table('bookings')
                ->where('bookingno', $invoiceno)
                ->update($updateData);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or no changes made'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => [
                    'ticketno' => $ticketno,
                    'status' => 'CO',
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update booking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function cancel(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'invoiceno' => 'required|string',
                'reason' => 'nullable|string',
            ]);

            $invoiceno = $request->invoiceno;

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'status' => 'VO',
                'updated_at' => Carbon::now(),
                'ispayment' => 'N',
                'cancel_date' => Carbon::now()
            ];

            if ($request->has('reason')) {
                $updateData['reason'] = $request->reason;
            }

            $updated = DB::table('bookings')
                ->where('bookingno', $invoiceno)
                ->update($updateData);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found or no changes made'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking cancel successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
