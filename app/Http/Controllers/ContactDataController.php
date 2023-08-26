<?php

namespace App\Http\Controllers;

use App\Models\ContactData;
use App\Http\Requests\StoreContactDataRequest;
use App\Http\Requests\UpdateContactDataRequest;

class ContactDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($data, $model_string, $model)
    {
        $contactData = ContactData::create([
            'label' => $data['label'],
            'type' => $data['type'],
            'value' => $data['value'],
            'is_main' => $data['is_main'],
            'contactable_type' => $model_string,
            'contactable_id' => $model->id
        ]);

        return $contactData;
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactData $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactDataRequest $request, ContactData $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactData $contact)
    {
        //
    }
}
