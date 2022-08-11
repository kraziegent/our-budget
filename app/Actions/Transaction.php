<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Transaction as TransactionModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Transaction
{
    /**
     * Store transactions for a user
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \App\Models\Transaction
     */
    public function store(User $user, array $data)
    {
        $account = $user->accounts()->find($data['account_id']);

        if (! $account) {
            throw ValidationException::withMessages([
                'account_id' => 'Seems we were unable to find this account for the user!'
            ]);
        }

        $category = $user->categories()->find($data['category_id']);

        if (! $category) {
            throw ValidationException::withMessages([
                'category_id' => 'Seems we were unable to find this category for the user!'
            ]);
        }

        $is_cleared = isset($data['is_cleared']) ? $data['is_cleared'] : false;
        $transaction_date = isset($date['transaction_date']) ? $date['transaction_date'] : now();

        if (isset($data['payee_id'])) {
            $payee_id = $data['payee_id'];
        } else {
            $payee_id = $user->payees()->firstOrCreate(['name' => $data['payee_name']])->uuid;
        }

        $transaction = $user->transactions()->create([
            'category_id' => $category->uuid,
            'account_id' => $account->uuid,
            'payee_id' => $payee_id,
            'amount' => makeMoney($data['amount'], $account->currency),
            'type' => $data['type'],
            'is_cleared' => $is_cleared,
            'transaction_date' => $transaction_date,
            'description' => $data['description'],
        ]);

        return $transaction;
    }

    /**
     * Store transactions for a user
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function storeMany(User $user, array $data)
    {
        $transactions = collect();

        DB::beginTransaction();
            foreach($data as $value) {
                $transaction = $this->store($user, $value);

                $transactions->push($transaction);
            }
        DB::commit();

        return $transactions;
    }

    /**
     * Update a transaction in the DB
     *
     * @param \App\Models\Transaction $transaction
     * @param array $data
     * @return \App\Models\Transaction|null
     */
    public function update(TransactionModel $transaction, array $data)
    {
        if (isset($data['category_id'])) {
            $transaction->category_id = $data['category_id'];
        }

        if (isset($data['account_id'])) {
            $transaction->account_id = $data['account_id'];
        }

        if (isset($data['payee_id'])) {
            $transaction->payee_id = $data['payee_id'];
        }

        if (isset($data['amount'])) {
            $amount = $transaction->amount;
            $currency = $transaction->amount->getCode();

            $transaction->amount = makeMoney($data['amount'], $currency);
        }

        if (isset($data['type'])) {
            $transaction->type = $data['type'];
        }

        if (isset($data['is_cleared'])) {
            $transaction->is_cleared = $data['is_cleared'];
        }

        if (isset($data['transaction_date'])) {
            $transaction->transaction_date = $data['transaction_date'];
        }

        if (isset($data['description'])) {
            $transaction->description = $data['description'];
        }

        if ($transaction->save()) {
            if ($transaction->is_checked && isset($amount) && ! $amount->equals($transaction->amount)) {

            }

            return $transaction;
        }

        return null;
    }
}
