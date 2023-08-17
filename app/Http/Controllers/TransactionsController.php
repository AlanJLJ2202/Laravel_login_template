<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessController extends Controller
{

    public function get_transactions(){
        try {
            $transactions = Transaction::where('user_id', Auth::user()->id)->get();

            $data = [
                'status' => 'success',
                'transactions' => $transactions
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {
            $data = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];

            return response()->json($data, 500);
        }
    }


    public function register_transaction(Request $request){
        try {
            $request->validate([
                'amount' => 'required|numeric',
            ]);

            DB::beginTransaction();

            $transaction = new Transaction();
            $transaction->user_id = Auth::user()->id;
            $transaction->amount = $request->amount;
            $transaction->save();

            $balance = Balance::where('user_id', Auth::user()->id)->first();
            if ($balance) {
                $balance->amount = $balance->amount + $request->amount;
                $balance->save();
            } else {
                $balance = new Balance();
                $balance->user_id = Auth::user()->id;
                $balance->amount = $request->amount;
                $balance->save();
            }

            DB::commit();

            $data = [
                'status' => 'success',
                'transaction' => $transaction,
                'balance' => $balance
            ];

            return response()->json($data, 201);
        } catch (\Exception $e) {
            DB::rollback();

            $data = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];

            return response()->json($data, 500);
        }
    }



}