<?php

namespace App\Filament\Widgets;

use App\Models\City;
use App\Models\Employee;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class CitiesTable extends BaseWidget
{
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(City::query())
            ->defaultSort('name', 'asc')
            ->columns([
               Tables\Columns\TextColumn::make('name'),
               Tables\Columns\TextColumn::make('state.name'),

            ]);
    }
}
