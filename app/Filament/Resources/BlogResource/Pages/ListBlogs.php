<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array{

        return [
            'All' => Tab::make(),
            'Published' => Tab::make('Published')
                ->modifyQueryUsing(function ($query) {
                    $query->where('status', 1);
                }),
            'unPublished' => Tab::make('Un Published')
                ->modifyQueryUsing(function ($query) {
                    $query->where('status', 0);
                }),
        ];
    }
}
