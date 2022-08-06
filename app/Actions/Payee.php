<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Payee as PayeeModel;

class Payee
{
    /**
     * Store a new payee for the user
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \App\Models\Payee
     */
    public function store(User $user, array $data)
    {
        $payee = $user->payees()->create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return $payee;
    }

        /**
     * Update a category in the database.
     *
     * @param \App\Models\Payee $payee
     * @param array $data
     * @return \App\Models\Payee|null
     */
    public function update(PayeeModel $payee, array $data)
    {
        if (isset($data['name'])) {
            $payee->name = $data['name'];
        }

        if (isset($data['description'])) {
            $payee->description = $data['description'];
        }

        if ($payee->save()) {
            return $payee;
        }

        return null;
    }
}
