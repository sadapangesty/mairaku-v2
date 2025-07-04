<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Get all customers
     */
    public function index(): JsonResponse
    {
        try {
            $customers = Customer::paginate(10);
            return response()->json([
                'success' => true,
                'data' => $customers
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching customers: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching customers'
            ], 500);
        }
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate input data
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:50',
            'address' => 'nullable|string|max:500',
            'image'   => 'nullable|string'
        ]);

        try {
            // Handle image upload
            if (isset($validatedData['image']) && strpos($validatedData['image'], 'data:image') === 0) {
                // Extract image data from base64 string
                $imageData = base64_decode(explode(',', $validatedData['image'])[1]);

                // Generate unique filename
                $filename = 'customer-' . time() . '.png';

                // Create directory if it doesn't exist
                $imagePath = public_path('assets/images');
                if (!file_exists($imagePath)) {
                    mkdir($imagePath, 0755, true);
                }

                // Save image to public/assets/images
                file_put_contents($imagePath . '/' . $filename, $imageData);

                $validatedData['image'] = $filename;
            } else {
                // Set default image if not provided
                $validatedData['image'] = $validatedData['image'] ?? 'profile-35.png';
            }

            // Create customer record
            $customer = Customer::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Customer added successfully',
                'data'    => $customer
            ], 201);
        } catch (\Exception $e) {
            // Log the error details for debugging
            Log::error("Error adding customer: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error adding customer, please try again later.'
            ], 500);
        }
    }

    /**
     * Delete a customer
     */
    /**
     * Update a customer
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:50',
            'address' => 'nullable|string|max:500',
            'image'   => 'nullable|string'
        ]);

        try {
            $customer = Customer::findOrFail($id);

            // Handle image upload
            if (isset($validatedData['image']) && strpos($validatedData['image'], 'data:image') === 0) {
                // Extract image data from base64 string
                $imageData = base64_decode(explode(',', $validatedData['image'])[1]);

                // Generate unique filename
                $filename = 'customer-' . time() . '.png';

                // Create directory if it doesn't exist
                $imagePath = public_path('assets/images');
                if (!file_exists($imagePath)) {
                    mkdir($imagePath, 0755, true);
                }

                // Save image to public/assets/images
                file_put_contents($imagePath . '/' . $filename, $imageData);

                $validatedData['image'] = $filename;
            } else {
                // Keep existing image if no new image is uploaded
                unset($validatedData['image']);
            }

            $customer->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data'    => $customer
            ]);
        } catch (\Exception $e) {
            Log::error("Error updating customer: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating customer'
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error("Error deleting customer: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting customer'
            ], 500);
        }
    }
}
