<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Menuitem;
use App\Models\SProperty;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menuitem::insertIfNotExists('transactions', 'bi bi-currency-euro');
        Module::insertIfNotExists('Transaction');
        SProperty::insertIfNotExists(1321, 'transactiontype', 'income', 10, 'I');
        SProperty::insertIfNotExists(1322, 'transactiontype', 'expense', 20, 'E');
        SProperty::insertIfNotExists(1322, 'transactiontype', 'move to account', 30, 'M');
        SProperty::insertIfNotExists(1322, 'transactiontype', 'move from account', 40, 'M');
        SProperty::insertIfNotExists(1331, 'transactionstate', 'open', 10, 'O');
        SProperty::insertIfNotExists(1332, 'transactionstate', 'done', 20, 'O');
    }
}
