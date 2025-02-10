<?php

namespace App\Filament\Citysaver\Resources;

use App\Filament\Citysaver\Resources\BrandResource\Pages;
use App\Filament\Citysaver\Resources\BrandResource\RelationManagers;
use App\Filament\Citysaver\Resources\BranchResource\RelationManagers\BranchRelationManager;
use App\Filament\Citysaver\Resources\BrandResource\Api\Transformers\BrandTransformer;
use App\Filament\Citysaver\Resources\CategoryResource\RelationManagers\CategoryRelationManager;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Country;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name','email'];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, $state) {
                            $set('slug', Str::slug($state));
                        })
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'The name has already been registered.',
                        ]),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->readonly(),
                    Forms\Components\Select::make('category_id')
                        ->label('Category')
                        ->options(Category::pluck('name','id'))
                        ->live()
                        ->required(),
    
                    Forms\Components\Select::make('sub_category_id')
                        ->label('Sub Category')
                        ->options(fn(Forms\Get $get) => SubCategory::where('category_id', (int) $get('category_id'))->pluck('name','id'))
                        ->disabled(fn(Forms\Get $get) : bool => ! filled($get('category_id')) ),

                    Forms\Components\Select::make('country_id')
                        ->label('Country')
                        ->options(Country::pluck('name','id'))
                        ->searchable()
                        ->live()
                        ->required(),

                    Forms\Components\Select::make('city_id')
                        ->label('City')
                        ->options(fn(Forms\Get $get) => City::where('country_id', (int) $get('country_id'))->pluck('name','id'))
                        ->disabled(fn(Forms\Get $get) : bool => ! filled($get('country_id')) ),
                        
                    Forms\Components\TextInput::make('google_map_link'),
                    Forms\Components\TextInput::make('latitude'),
                    Forms\Components\TextInput::make('longitude'),
                    Forms\Components\TextInput::make('address'),
                    Forms\Components\TextInput::make('address2'),
                    Forms\Components\MarkdownEditor::make('description')
                        ->columnSpanFull(),
                ])->columns(columns:3),

                Forms\Components\Section::make('Gallery')
                ->schema([
                    Forms\Components\FileUpload::make('main_image')
                        ->image(),
                    Forms\Components\FileUpload::make('logo')
                        ->image(),
                    Forms\Components\FileUpload::make('gallery')
                        ->image()
                        ->multiple(),
                ])->columns(columns:3),
                
                Forms\Components\Section::make('Business Information')
                ->schema([
                    Forms\Components\TextInput::make('image_url'),
                    Forms\Components\TextInput::make('email'),
                    Forms\Components\TextInput::make('phone_number'),
                    Forms\Components\TextInput::make('open'),
                    Forms\Components\TextInput::make('close'),
                    Forms\Components\TextInput::make('no_of_rate'),
                    Forms\Components\TextInput::make('rating'),
                ])->columns(columns:3),

                Forms\Components\Section::make('Address & Link')
                ->schema([
                    Forms\Components\TextInput::make('website_link'),
                    Forms\Components\TextInput::make('google_map_business_link')
                    ->label('Google Business Link'),
                    Forms\Components\TextInput::make('youtube_link'),
                    Forms\Components\TextInput::make('facebook_link'),
                    Forms\Components\TextInput::make('instagram_link'),
                    Forms\Components\TextInput::make('x_link'),
                    Forms\Components\TextInput::make('pinterest_link'),
                    Forms\Components\TextInput::make('tiktok_link'),
                ])->columns(columns:3),

                Forms\Components\Section::make('Social Posts')
                ->schema([
                    Forms\Components\TextInput::make('fb_social_1'),
                    Forms\Components\TextInput::make('fb_social_2'),
                    Forms\Components\TextInput::make('fb_social_3'),
                    Forms\Components\TextInput::make('tik_social_1'),
                    Forms\Components\TextInput::make('tik_social_2'),
                    Forms\Components\TextInput::make('tik_social_3'),
                    Forms\Components\TextInput::make('ig_social_1'),
                    Forms\Components\TextInput::make('ig_social_2'),
                    Forms\Components\TextInput::make('ig_social_3'),
                    Forms\Components\TextInput::make('youtube_social_1'),
                    Forms\Components\TextInput::make('youtube_social_2'),
                    Forms\Components\TextInput::make('youtube_social_3'),
                ])->columns(columns:3)
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('sub_category.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('country.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('no_of_rate')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('rating')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('website_link')
                    ->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category','name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('country_id')
                    ->label('Country')
                    ->relationship('country','name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('city_id')
                    ->label('City')
                    ->relationship('city','name')
                    ->searchable()
                    ->preload(),
                    //->options(Country::all()->pluck('name','id'))
                    // ->multiple()
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(100);
    }

    public static function getRelations(): array
    {
        return [
            //
            BranchRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }

    public static function getApiTransformer()
    {
        return BrandTransformer::class;
    }
    
}
