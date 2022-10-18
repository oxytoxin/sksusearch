<?php

namespace App\Filament\Resources\PettyCashFundResource\Pages;

use App\Filament\Resources\PettyCashFundResource;
use App\Models\PettyCashFund;
use App\Models\PettyCashFundRecord;
use DB;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePettyCashFund extends CreateRecord
{
    protected static string $resource = PettyCashFundResource::class;
}
