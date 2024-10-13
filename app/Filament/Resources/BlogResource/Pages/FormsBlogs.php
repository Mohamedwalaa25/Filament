<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use App\Models\Blog;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FormsBlogs extends Page implements HasForms
{
    use InteractsWithForms;

    protected
    static string $resource = BlogResource::class;
    public ?array $data = [];
    protected static ?string $model = Blog::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Products';

    protected static string $view = 'filament.resources.blog-resource.pages.forms-blogs';

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug'),

                RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),

                Toggle::make('status')
                    ->onColor('success')
                    ->offColor('danger')
                    ->label('Publish Status'),


//
                Select::make('category_id')
                    ->options(fn(Get $get) => Category::query()
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->label('category')
                    ->columnSpanFull()
                    ->required(),


            ])->statePath('data');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),


            ]);
    }

    public function getActions(): array
    {
        return [

            Action::make('create')->submit("create")
        ];
    }

    public function create()
    {
        $data = $this->form->getState();
        dd($data);
//        $blog = Blog::create([
//            'name' => $data['name'],
//            'slug'=> $data['slug'],
//            'description'=>$data['description'],
//            'status'=>$data['status'],
//            'category_id'=>$data['category_id']
//        ]);
//        Notification::make()->title('Created !')
//            ->success()
//            ->body('The Blog Created successfully')
//            ->send();

    }
}
