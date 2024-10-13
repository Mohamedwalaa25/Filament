<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Filament\Resources\BlogResource\RelationManagers\CategoryRelationManager;
use App\Filament\Resources\CategoryResource\RelationManagers\BlogRelationManager;
use App\Models\Blog;
use Filament\Actions\DeleteAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Products';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Blog Details')
                    ->description('Put the blog details here')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('description')
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

    public static function table(Table $table): Table
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
            ])->defaultSort("name")
            ->filters([

                Filter::make('Un Publish')
                    ->query(fn (Builder $query): Builder => $query->where('status', 0)),


                Filter::make('Publish')
                    ->query(fn (Builder $query): Builder => $query->where('status', 1)),


//                SelectFilter::make('status')
//                    ->options([
//                        1 => 'published',
//                        0 => 'Un published',
//                    ])


            ])
            ->actions([
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

    public static function getRelations(): array
    {
        return [
            CategoryRelationManager::class,
            RelationManagers\TagsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
            'form' =>Pages\FormsBlogs::route('/form'),

        ];
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
