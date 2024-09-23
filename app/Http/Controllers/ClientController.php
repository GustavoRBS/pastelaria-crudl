<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function getListClients()
    {
        $listClients = Client::all()->whereNull('deleted_at');

        return ApiHelper::sendResponse($listClients, 'Data returned successfully!', 200);
    }

    public function createClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:clients,email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        if ($validator->fails())
            return ApiHelper::sendError($validator->errors(), "Validation Error", 422);

        $client = Client::create($request->all());

        return ApiHelper::sendResponse($client, 'Client created successfully!!', 201);
    }

    public function getClient($clientId)
    {
        $client = Client::where('id', $clientId)->whereNull('deleted_at')->first();

        if (!$client)
            return ApiHelper::sendError([], "Client not found.", 404);

        return ApiHelper::sendResponse($client, 'Data returned successfully!', 200);
    }

    public function updateClient(Request $request, $clientId)
    {
        $client = Client::where('id', $clientId)->whereNull('deleted_at')->first();

        if (!$client)
            return ApiHelper::sendError([], "Client not found.", 404);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:255',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        if ($validator->fails())
            return ApiHelper::sendError($validator->errors(), "Validation Error", 422);

        $client->update($request->all());

        return ApiHelper::sendResponse($client, 'Client updated successfully!!', 200);
    }

    public function deleteClient($clientId)
    {
        $client = Client::find($clientId);
        
        if (!$client)
            return ApiHelper::sendError([], "Client not found.", 404);

        if ($client) {
            $client->delete();
            return ApiHelper::sendResponse($clientId, 'Client deleted successfully!', 202);
        }

        return ApiHelper::sendError($clientId, 'Error deleting client. Client not found.', 404);
    }
}
