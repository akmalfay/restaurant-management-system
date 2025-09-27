<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json([
            "success" => true,
            "data" => $customers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                "name" => "required|string",
                "email" => "required|email|unique:customers,email",
                "phone" => "required|string|min:11|unique:customers,phone",
                "password" => "required|min:8|confirmed",
                "image" => "nullable|image|mimes:jpg,jpeg,png|max:2048",
            ],
            [
                "email.unique" => "Email ini sudah terdaftar.",
                "phone.unique" => "Nomor telepon ini sudah terdaftar.",
            ],
        );

        try {
            $imageUrl = "image/profile.jpg";

            if ($request->hasFile("image")) {
                $file = $request->file("image");
                $fileName =
                    Str::uuid() . "." . $file->getClientOriginalExtension();
                $path = $file->storeAs("profile", $fileName, "public");
                $imageUrl = $path;
            }

            $user = Customer::create([
                "name" => $request->name,
                "phone" => $request->phone,
                "email" => $request->email,
                "points" => 10000,
                "password" => Hash::make($validated["password"]),
                "image" => $imageUrl,
            ]);

            return response()->json([
                "success" => true,
                "data" => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Internal server error",
                ],
                500,
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            return response()->json([
                "success" => true,
                "data" => $customer,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => null,
                    "message" => "Customer with ID " . $id . " not found",
                ],
                404,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
