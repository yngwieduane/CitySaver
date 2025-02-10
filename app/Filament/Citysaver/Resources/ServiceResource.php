<?php

namespace App\Filament\Citysaver\Resources;

use App\Filament\Citysaver\Resources\ServiceResource\Pages;
use App\Filament\Citysaver\Resources\ServiceResource\RelationManagers;
use App\Models\Category;
use App\Models\Service;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 20;
    
    protected static ?int $navigationSort = 1;

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
                        ->maxLength(250)
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

                    Forms\Components\Toggle::make('isprimary')
                        ->label('Is Primary Service')
                        ->helperText('Primary or Secondary'),

                    Forms\Components\MarkdownEditor::make('description')
                        ->columnSpanFull(),
                ])->columns(columns:2),
                
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('sub_category.name')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('isprimary')
                    ->boolean()
                    ->sortable()
                    ->label('Primary'),
                Tables\Columns\TextColumn::make('created_at')
                ->date()
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
