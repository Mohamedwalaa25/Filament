<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogRelationManager extends RelationManager
{
    protected static string $relationship = 'blogs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Blog Details')
                    ->description('Put the blog details here')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('status')
                            ->onColor('success')
                            ->offColor('danger')
                            ->label('Publish Status'),


                        Repeater::make('galleries')
                            ->relationship()
                            ->schema([
                                FileUpload::make('image')
                                    ->image()


                            ]),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                            ->required(),

                        Select::make('tags_id')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->required(),
                    ])

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->searchable(),

                ToggleColumn::make('status'),

                Tables\Columns\ImageColumn::make('galleries.image')

                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->limitedRemainingText(),



                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tags.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Action::make('Change Status')
                    ->icon('heroicon-o-pencil')
                    ->action(function (Blog $record) {
                        $record->status = !$record->status;
                        $record->save();
                        Notification::make()
                            ->title('Status Changed')
                            ->success()
                            ->body('The status has been changed successfully')
                            ->send();

                    }),



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function isReadOnly(): bool
    {
        return false;
    }
}
