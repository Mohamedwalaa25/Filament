<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup= 'Products';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Wizard\Step::make('Select Category')
                        ->schema([
                            Select::make('category_id')
                                ->relationship('category', 'name')
                                ->required(),
                        ]),
                    Wizard\Step::make('Create Title')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('slug'),

                        ]),

                    Wizard\Step::make('Create Description')
                        ->schema([
                            Forms\Components\RichEditor::make('description')
                                ->columnSpanFull(),
                        ]),
                    Wizard\Step::make('Create Price')
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->minValue(0),
                        ]),
                    Wizard\Step::make('Create Image')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('image')
                                ->collection('Products')
                                ->multiple(),
                        ]),
                    Wizard\Step::make('Create Status')
                        ->schema([
                            Toggle::make('status')
                        ]),
                ])->columnSpanFull(),



//                Section::make('Product Details')
//                    ->description('set the product details here')
//                    ->schema([
//                        Select::make('category_id')
//                            ->relationship('category', 'name')
//                            ->required(),
//                        Forms\Components\TextInput::make('title')
//                            ->live(onBlur: true)
//                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
//                            ->required()
//                            ->maxLength(255),

//                        Forms\Components\TextInput::make('slug'),

//                        Forms\Components\RichEditor::make('description')
//                            ->columnSpanFull(),

//                        Forms\Components\TextInput::make('price')
//                            ->required()
//                            ->numeric()
//                            ->minValue(0),
//
//                        FileUpload::make('image')
//                            ->directory('Products')
//                            ->disk('public'),

//
//                        SpatieMediaLibraryFileUpload::make('image')
//                            ->collection('Products')
//
//                            ->multiple(),
//                        Toggle::make('status')->columnSpanFull()


                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),


                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),

                IconColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0' => 'heroicon-o-clock',

                    })
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'warning',

                    }),
//                ImageColumn::make('image')
//                    ->disk('public')
//                    ->visibility('private')
//                    ->checkFileExistence(false)
//                    ->size('50px'),


                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('Products')
                    ->size('50px')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
